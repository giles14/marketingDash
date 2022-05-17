<?php
/**
 * Class Pantalla, originally made to update images with information to show it in screen kiosks, with further update is now used to store information in a localDB to keep metrics.
 *
 * @author  Alexandro Giles
 * @license MIT
 */
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
//echo $_SERVER['DBUSER'] . "\n";
Class Pantalla{
    protected $connection;
    protected $query;
    public $activeStudents = [];
    private $localConnection;
    public $studentNumber;
    private $debug = false;
    protected $keys = array( 'licenciaturas' => array('lsp', 'ld', 'lcp', 'lae'), 'maestrias' => array('mspp', 'map', 'miti', 'man', 'mcd', 'mfp', 'mig', 'mais', 'mepp'),
    'masters' => array('mag', 'mspa', 'mmpo', 'mgpm'), 'doctorados' => array('dpp', 'dsp'));
    
    public function __construct(){
        $this->getActiveStudents();
        $this->localDB();
        $this->saveValuesInDB();
        $this->getActiveStudentsNumber();
    }
    private function getActiveStudents(){
      //$dbname = 'm2iexeed_alumnosactivos';
      //$dbusr = $_ENV['DBUSER'];
      //$dbpass = $_ENV['DBPASS'];
        try {
            //$dsn = $_ENV['DB'];
            $dbh = new PDO($_ENV['DB'], $_ENV['DBUSER'], $_ENV['DBPASS']);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->exec("set names utf8");
        } catch (PDOException $e){
            echo $e->getMessage();
        }
        $stmt = $dbh->prepare("SELECT * FROM alumnosActivos WHERE NOT trimestre regexp '^Baja'");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        $arr = [];
        $itt = 0;
        while ( $row = $stmt->fetch()){
            $arr[$itt] = array( "matricula" => $row["matricula"], "trimestre"=>$row["trimestre"], "periodo" => $row["periodo"], "email" => $row["email"]);
            $itt++;
        }
        $this->activeStudents = $arr;
    }
    private function localDB(){
        $dbname = 'pantallas';
        try {
            $dsn = "mysql:host=localhost;dbname=$dbname";
            $dbh = new PDO($dsn, 'root', '');
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->exec("set names utf8");
            $this->localConnection = $dbh;
            //return $dbh;
        } catch (PDOException $e){
            echo $e->getMessage();
        }
    }
    public function saveValuesInDB(){
        $dbh = $this->localConnection;
        $idActive = $this->getArrayForAcademicProgram();
        if(!$this->dateExists($dbh)){
            $row = [];
            $day = date('d');
            $month = date('m');
            $itt = 1;
            foreach ($idActive as $id => $activeStudents ){
                $row[$itt] = array("id_programa" => $id, "active_students" => $activeStudents, "dayOfMonth" => $day, "monthNumber" => $month);
                $itt++;
            }
            if($this->debug){
                echo "<pre>";
                print_r($row);
                echo "</pre>";
            }
            $stmt = $dbh->prepare("INSERT INTO activos(id_programa, cantidad_estudiantes, dia, mes) values (:id_programa, :active_students, :dayOfMonth, :monthNumber )");
            foreach($row as $rowNumber){
                $stmt->execute($rowNumber);
            }
        }
        
    }
    public function getActiveStudentsNumber(){
        $student = 0;
        $arr = $this->activeStudents;
        foreach($arr as $student){
            $student++;
        }
        $this->studentNumber = intval($student);
    }
    private function dateExists(PDO $dbh){
        $day = date('d');
        $month = date('m');
        $stmt = $dbh->prepare("SELECT dia, mes FROM activos WHERE activos.dia = '".$day."' AND activos.mes = '".$month."'");
        $stmt->execute();
        if($stmt->rowCount() > 0){
           $exists = true; 
        }else{
            $exists = false;
        }
        return $exists;

    }

    /**
     * @param  string $key the key of the required academic program
     * 
     * @return int $ocurr the number of active students  in the
     */
    public function getStudentsByKey(string $key){
        $ocurr = 0;
        foreach($this->activeStudents as $student){
            if( preg_match('/^'.$key.'/', $student["matricula"]) ){
                $ocurr++;
            }
        }
        return $ocurr;
    }
    /**
     *  @param string $program the requested program.
     * 
     *  @param int $type a number between 0 to 1, where 0 returns the total of active students of the requested program, 
     *  1 return a comma separated array with two keys: "names" and "numbers", with all academic subprograms names and numbers of the requested program.
     *  
     *  @return int  if param $type = 0: the total of active students.
     * 
     *  @return array if param $type = 1: all of the subprograms, names and numbers of the requested program.
    */
    public function getStudentsByProgram(string $program, int $type = 0){
        $individualProgram = [];

        foreach($this->keys as $area => $especialidad){       
            foreach($especialidad as $programa){
                $programas[$area][$programa] = $this->getStudentsByKey($programa);
            }
        }
        if ($type == 0){
            $programCount = 0;
            $itterator = 0;
            foreach($programas[$program] as $program){
                if(is_numeric($program)){
                    $programCount += $program;
                }
                $itterator ++;
            }
            return $programCount;            
        }

        if($type == 1){
            if($this->debug){
                echo "type 1 <br>";
            }
            foreach($programas[$program] as $program => $numberStudents){
                $individualProgram[$program] = $numberStudents;
            }
            if($this->debug){
                echo "before format:";
            }
            
            $formated = $this->formatResult($individualProgram);
            if($this->debug){
                echo "<pre>";
                print_r($formated);
                echo "</pre>";
            }
            return $formated;
        }
           
    }
    /**
     * Creates an array of all active students in every subprogram mapped as: program -> subprogram, and prints it as a preformated html <pre> item.
     */
    public function getAllStudentsByProgram(){
        foreach($this->keys as $area => $especialidad){       
            foreach($especialidad as $programa){
                $programas[$area][$programa] = $this->getStudentsByKey($programa);
            }
        }
        if($this->debug){
            echo '<pre>';
            print_r($programas);
            echo '</pre>';
        }
    }
    public function getArrayForAcademicProgram(){
        $dbh = $this->localConnection;
        $stmt = $dbh->prepare("SELECT * FROM programa");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        $arr = [];
        while($row = $stmt->fetch()){
            $arr[$row["id"]] = $row["clave"];
        }
        if($this->debug){
            echo "<pre>";
            print_r($arr);
            echo "</pre> <br>";
        }
        $individualProgram = [];
        $itt = 1;
        $idActive = [];
        foreach($this->keys as $program => $programKey ){
            foreach($programKey as $key){
                foreach($arr as $id => $keyName){
                    if((strcasecmp($key,$keyName)) == "0"){
                        $idActive[$id] = $this->getStudentsByKey($key);
                    }
                }
            }
            
        }
        //print_r($idActive);
        return $idActive;
    }
    public function getProgramNameFromKey($key){
        $dbh = $this->localConnection;
        $stmt = $dbh->prepare("SELECT nombre FROM programa WHERE programa.clave LIKE '".$key."%' ");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        $name = $stmt->fetch();
        
        return $name;
    }

    public function formatResult(array $items, string $separator = ','){
        $programNames = "";
        $programNumbers = "";
        $program = [];
        //print_r($items);
        $count = count($items);
        
        foreach ($items as $program => $number){
            $count--;
            if ($count < 1) {
                $programNames .= "'" . $program ."'";
                $programNumbers .= $number;
                
            }else{
                $programNames .= "'" . $program ."'" . ",";
                $programNumbers .= $number . ",";
            }
             
        }
        $program = array ("names" =>  $programNames,
        "numbers" => $programNumbers);

        return $program;
    }
    public function historicalData(int $day = 99, int $month = 99){
        if($day == 99) {
            $day = date('d') - 1;
        }
        if($month == 99) {
            $month = date('m');
        }
        
        $dbh = $this->localConnection;
        $stmt = $dbh->prepare("SELECT programa.clave, activos.cantidad_estudiantes FROM programa INNER JOIN activos ON activos.id_programa = programa.id WHERE activos.dia = '".$day."' AND activos.mes = '".$month."'"); 
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        $arr = [];
        while($row = $stmt->fetch()){
            $arr[$row["clave"]] = $row["cantidad_estudiantes"];
        }
        if($this->debug){
            echo "<pre>";
            print_r($arr);
            echo "</pre>";
        }
    }
    public function getHistoricalDataByKey(string $key = 'miti', int $day = 99, int $month = 99){
        //echo 'Requested:' . $key . '<br>';
        if($day == 99) {
            $day = date('d') - 1;
        }
        if($month == 99) {
            $month = date('m');
        }else{
            $day = $day;
            $month = $month;
        }
        
        $dbh = $this->localConnection;
        $key = substr($key, 0,4);
        $stmt = $dbh->prepare("SELECT programa.clave, activos.cantidad_estudiantes FROM activos INNER JOIN programa ON programa.id = activos.id_programa WHERE programa.clave LIKE '".$key."%' AND activos.dia = $day AND activos.mes = $month"); 
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        $individualProgramHistorical = [];
        while($row = $stmt->fetch()){
            $number = $row["cantidad_estudiantes"];
        }
        if(isset($number)){
            return $number;
        }
        
        
    }
    public function getHistoricalDataByPeriod(string $key="miti",int $period = 1,string $startDate = "25-04-2022"){
        $date = $startDate;
        $period = 100;
        echo date('d-m-Y', strtotime($date. ' + '. $period .' days'));

    }

    public function historicalDataByProgram(string $program = 'licenciatura', int $day = 99, int $month = 99){
        if($day == 99) {
            $day = date('d') - 1;
        }
        if($month == 99) {
            $month = date('m');
        }
        
        $dbh = $this->localConnection;
        //$stmt = $dbh->prepare("SELECT programa.clave, activos.cantidad_estudiantes FROM programa INNER JOIN activos ON activos.id_programa = programa.id WHERE activos.dia = '".$day."' AND activos.mes = '".$month."'"); 
        $stmt = $dbh->prepare("SELECT tipo.tipo, programa.clave, activos.cantidad_estudiantes FROM activos INNER JOIN programa ON activos.id_programa = programa.id INNER JOIN tipo ON programa.pertenece = tipo.id WHERE activos.dia = '".$day."' AND activos.mes = '".$month."' AND tipo.tipo ='".$program."'  ORDER BY cantidad_estudiantes DESC"); 
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        $individualProgramHistorical = [];
        while($row = $stmt->fetch()){
            $individualProgramHistorical[$row["clave"]] = $row["cantidad_estudiantes"];
        }
        if($this->debug){
            echo "<pre>";
            print_r($individualProgramHistorical);
            echo "</pre>";
        }
        $formated = $this->formatResult($individualProgramHistorical);

        return $formated;
        
    }
    public function getDifferenceBetweenDays(string $program = 'miti', string $startDate = "25-04-2022", int $period = 1){
        $date = date($startDate);
        $period = $period;
        //echo date('d-m-Y', strtotime($date. ' + '. $period .' days'));
        for($i = 0; $i <= $period; $i++){
            $currentDay = date('d', strtotime($date. ' + '. $i .' days'));
            $currentMonth = date('m', strtotime($date. ' + '. $i .' days'));
            if($i == 0){
                $initNumber = $this->getHistoricalDataByKey($program, $currentDay, $currentMonth);
                //$info[$i] = $initNumber;
                $info[$currentDay ."-".$currentMonth] = 0;
            }else{
                $current = $this->getHistoricalDataByKey($program, $currentDay, $currentMonth);
                $info[$currentDay ."-".$currentMonth] =   $current - $initNumber ;
                $initNumber = $this->getHistoricalDataByKey($program, $currentDay, $currentMonth);
                // $current = abs($initNumber - $this->getHistoricalDataByKey($program, $currentDay, $currentMonth)) - $current;
            }
            
            
        }
        if($this->debug){
            echo "<pre>";
            print_r($info);
            echo "<pre>";
        }
        return $this->formatResult($info);
    }
    public function getHistoBetweenDays(string $program = 'miti', string $startDate = "25-04-2022", int $period = 1){
        $date = date($startDate);
        $period = $period;
        //echo date('d-m-Y', strtotime($date. ' + '. $period .' days'));
        for($i = 0; $i <= $period; $i++){
            $currentDay = date('d', strtotime($date. ' + '. $i .' days'));
            $currentMonth = date('m', strtotime($date. ' + '. $i .' days'));
            if($i == 0){
                $initNumber = $this->getHistoricalDataByKey($program, $currentDay, $currentMonth);
                //$info[$i] = $initNumber;
                $info[$currentDay.".".$currentMonth] = $initNumber;
            }else{
                $current = $this->getHistoricalDataByKey($program, $currentDay, $currentMonth);
                $info[$currentDay . "-" . $currentMonth] =   $current;
                // $current = abs($initNumber - $this->getHistoricalDataByKey($program, $currentDay, $currentMonth)) - $current;
            }
            
            
        }
        if($this->debug){
            echo "<pre>";
            print_r($info);
            echo "<pre>";
        }
        return $this->formatResult($info);
    }
    
    function __destruct(){
        $this->localConnection = "";
    }
    
}
// $pantalla = new Pantalla;
// $pantalla->saveValuesInDB();
// $pantalla->historicalDataByProgram();
// print_r($pantalla->getHistoricalDataByKey('MITI'));
// $pantalla->getHistoricalDataByPeriod();
// print_r($pantalla->getDifferenceBetweenDays('lsp','02-05-2022',4));

// $pantalla->getArrayForAcademicProgram();
/*
$pantalla = new Pantalla;
$pantalla->getAllStudentsByProgram();
echo $pantalla->getStudentsByKey('mcd');
echo '<br>';
echo $pantalla->getStudentsByKey('miti');
echo '<br>';
echo $pantalla->getStudentsByProgram("maestrias");
echo '<pre>';
//print_r($pantalla->activeStudents);
echo '</pre>';
*/
