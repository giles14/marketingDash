<?php 
$programas = [];
 $dbname = 'm2iexeed_alumnosactivos';
    try {
        $dsn = "mysql:host=m2.iexe.edu.mx;dbname=$dbname";
        $dbh = new PDO($dsn, 'm2iexeed_aactivos', '~NT*Hob*jnTc');
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e){
        echo $e->getMessage();
    }
//$stmt = $dbh->prepare("SELECT * FROM alumnosActivos WHERE matricula regexp '^mcd' AND estatus != 'Baja Definitiva' AND estatus != 'Baja temporal' OR matricula regexp '^mcda' AND estatus != 'Baja temporal' AND estatus != 'Baja Definitiva'");
$stmt = $dbh->prepare("SELECT * FROM alumnosActivos WHERE matricula regexp '^mcd' AND NOT trimestre regexp '^Baja' OR matricula regexp '^miti' AND  NOT trimestre regexp '^Baja'");
//$stmt->setFetchMode(PDO::FETCH_ASSOC);
$stmt->execute();
$alumnos_tecnologia = $stmt->rowCount();
$stmt = $dbh->prepare("SELECT * FROM alumnosActivos WHERE NOT trimestre regexp '^Baja'");
$stmt->execute();
$alumnos_totales = $stmt->rowCount();
$stmt = $dbh->prepare("SELECT * FROM prope WHERE trimestre NOT LIKE '%baja%' AND NOT EXISTS (select * From alumnosActivos Where alumnosActivos.matricula = prope.matricula)");
$stmt->execute();
$propedeutico = $stmt->rowCount();

$alumnos_totales += $propedeutico;
$ptje = round($alumnos_tecnologia * 100 / $alumnos_totales,2);

echo " Alumnos totales: $alumnos_totales , Alumnos Tecnología $alumnos_tecnologia <br>";
echo "Porcentaje: $ptje";


creaImagenActivos( $alumnos_totales, $ptje);
function creaImagenActivos(int $alumnos_totales, float $ptje){
    $dbh = null;
    $img = imagecreatefromjpeg("coverA.jpg");
    $size = 130; $angle = 0; $y = 200; $x = 530; $quality = 100;
    $color = imagecolorallocate($img, 255, 255, 255);
    $font = __DIR__ .  '/font/impact.ttf';
    $aTotales = number_format($alumnos_totales);
    $ptje = $ptje . "%";
    imagettftext($img, $size, $angle, $y, $x, $color, $font, "$aTotales");
    imagettftext($img, $size-60, $angle, $y, $x+280, $color, $font, "$ptje");
    imagejpeg($img, "output/5.jpg", $quality);
}
function creaImagenLicenciaturas(array $programas){
    $dbh = null;
    $img = imagecreatefromjpeg("coverLic.jpg");
    $size = 60; $angle = 0; $y = 490; $x = 390; $quality = 100;
    $color = imagecolorallocate($img, 255, 255, 255);
    $font = __DIR__ .  '/font/impact.ttf';
    $ubi = 0;
    foreach($programas["licenciaturas"] as $carrera => $numero){
        $numero = number_format($numero);
        imagettftext($img, $size, $angle, $y, $x+$ubi, $color, $font, "$numero");
        $ubi += 142;
    }
    imagejpeg($img, "output/1.jpg", $quality);
}
function creaImagenDoctorados(array $programas){
    $dbh = null;
    $img = imagecreatefromjpeg("coverDoc.jpg");
    $size = 60; $angle = 0; $y = 490; $x = 390; $quality = 100;
    $color = imagecolorallocate($img, 255, 255, 255);
    $font = __DIR__ .  '/font/impact.ttf';
    $ubi = 0;
    foreach($programas["doctorados"] as $carrera => $numero){
        $numero = number_format($numero);
        imagettftext($img, $size, $angle, $y, $x+$ubi, $color, $font, "$numero");
        $ubi += 142;
    }
    imagejpeg($img, "output/2.jpg", $quality);
}
function creaImagenMaestrias(array $programas){
    $dbh = null;
    $img = imagecreatefromjpeg("coverMae.jpg");
    $size = 48; $angle = 0; $y = 460; $x = 370; $quality = 100;
    $color = imagecolorallocate($img, 255, 255, 255);
    $font = __DIR__ .  '/font/impact.ttf';
    $ubi = 0;
    $ubi2 = 0;
    $iteY = 0;
    foreach($programas["maestrias"] as $carrera => $numero){
        $numero = number_format($numero);
        if ($iteY < 5){
            imagettftext($img, $size, $angle, $y, $x+$ubi, $color, $font, "$numero");
        }else{
            imagettftext($img, $size, $angle, $y+795, $x+$ubi2, $color, $font, "$numero");
            $ubi2 += 117;
        }
        $iteY++;
        $ubi += 117;
    }
    imagejpeg($img, "output/3.jpg", $quality);
}
function creaImagenMasters(array $programas){
    $dbh = null;
    $img = imagecreatefromjpeg("coverMas.jpg");
    $size = 60; $angle = 0; $y = 490; $x = 390; $quality = 100;
    $color = imagecolorallocate($img, 255, 255, 255);
    $font = __DIR__ .  '/font/impact.ttf';
    $ubi = 0;
    foreach($programas["masters"] as $carrera => $numero){
        $numero = number_format($numero);
        imagettftext($img, $size, $angle, $y, $x+$ubi, $color, $font, "$numero");
        $ubi += 142;
    }
    imagejpeg($img, "output/4.jpg", $quality);
}
    

    echo '<br>';
    
      $keys = array( 'licenciaturas' => array('lsp', 'ld', 'lcp', 'lae'), 'maestrias' => array('mspp', 'map', 'miti', 'man', 'mcd', 'mfp', 'mig', 'mais', 'mepp'),
      'masters' => array('mag', 'mspa', 'mmpo', 'mgpm'), 'doctorados' => array('dpp', 'dsp'));
    $it = 0;
    foreach($keys as $area => $especialidad){       
        foreach($especialidad as $programa){
            $programas[$area][$programa] = getActiveStudents($programa);
        }
        echo '<br>';
    }

    echo '<pre>';
    print_r($keys);
    echo '</pre>';
    echo '<pre>';
    print_r($programas);
    echo '</pre>';
    creaImagenLicenciaturas($programas);
    creaImagenDoctorados($programas);
    creaImagenMaestrias($programas);
    creaImagenMasters($programas);

    function getActiveStudents($key){
        //$matricula = substr($key,0,3);
        $matricula = $key;
        $dbname = 'm2iexeed_alumnosactivos';
        try {
            $dsn = "mysql:host=m2.iexe.edu.mx;dbname=$dbname";
            $dbh = new PDO($dsn, 'm2iexeed_aactivos', '~NT*Hob*jnTc');
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e){
            echo $e->getMessage();
        }
        $stmt = $dbh->prepare("SELECT * FROM alumnosActivos WHERE matricula regexp '^".$matricula."' AND NOT trimestre regexp '^Baja'");
        //$stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        $alumnos = $stmt->rowCount();
        
        // echo strtoupper($key)  ." $alumnos <br>";
        return $alumnos;
    }

    