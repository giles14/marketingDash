<?php include_once ('./Pantalla.php');
$datos = new Pantalla;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-annotation/1.4.0/chartjs-plugin-annotation.min.js"></script>
    <title>Document</title>
</head>
<body>
    <head>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Features</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Pricing</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled">Disabled</a>
                </li>
                </ul>
            </div>
        </nav>
    </head>
    <div class="container">
        <div class="row">
            <div class="col-12">
                
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <h1>Estudiantes por tipo de programa</h1>
                <div>
                    <canvas id="myChart"></canvas>
                </div>
            </div>
            <div class="col-6">
                <h1>Estudiantes planes Tecnológicos</h1>
                <div>
                    <canvas id="myChartTecnologicos"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-3">
                <h1>Licenciaturas</h1>
                <div>
                    <canvas id="myChartLicenciaturas"></canvas>
                </div>
            </div>
            <div class="col-3">
                <h1>Maestrías</h1>
                <div>
                    <canvas id="myChartMaestrias"></canvas>
                </div>
            </div>
            <div class="col-3">
                <h1>Doctorados</h1>
                <div>
                    <canvas id="myChartDoctorados"></canvas>
                </div>
            </div>
            <div class="col-3">
                <h1>Masters</h1>
                <div>
                    <canvas id="myChartMasters"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-6">
                <h1>Licenciaturas hoy vs 25 de Abril</h1>
                <div>
                    <canvas id="myChartLicVs"></canvas>
                </div>
            </div>
            <div class="col-6">
                <h1>Maestrias hoy vs 25 de Abril</h1>
                <div>
                    <canvas id="myChartTecnologicosVs"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-6">
                <h3>Evolución por periodo: 2 Mayo a 9 de Mayo</h3>
                <div>
                    <canvas id="myChartPeriod"></canvas>
                </div>
            </div>
            <div class="col-6">
                <!-- <h1>Maestrias hoy vs 25 de Abril</h1>
                <div>
                    <canvas id="myChartTecnologicosVs"></canvas>
                </div> -->
            </div>
        </div>
    </div>
<?php echo $datos->getStudentsByProgram("maestrias")?>
<?php $datos->getAllStudentsByProgram(); ?>
 <?php $lic = $datos->getStudentsByProgram("licenciaturas",1);
 $mae = $datos->getStudentsByProgram("maestrias",1);
 $doc = $datos->getStudentsByProgram("doctorados", 1);
 $mas = $datos->getStudentsByProgram("masters", 1);
 $licP = $datos->historicalDataByProgram("licenciatura",25,04);
 $maeP = $datos->historicalDataByProgram("maestria",25,04);
 $historico = $datos->getDifferenceBetweenDays('mcd','02-05-2022',7);
 
 ?>

<script>
  const labels = [
    'Licenciatura',
    'Maestria',
    'Master',
    'Doctorado'
  ];

  const data = {
    labels: labels,
    datasets: [{
      label: 'Alumnos por tipo',
      backgroundColor: 'rgb(255, 99, 132)',
      borderColor: 'rgb(255, 99, 132)',
      data: [<?php echo $datos->getStudentsByProgram("licenciaturas")?>,<?php echo $datos->getStudentsByProgram("maestrias")?>, <?php echo $datos->getStudentsByProgram("masters")?>, <?php echo $datos->getStudentsByProgram("doctorados")?>],
    }],
      pointStyle: 'circle',
      pointRadius: 10,
      pointHoverRadius: 15
  };
  const config = {
    type: 'line',
    data: data,
    options: {}
  };
  const labelsLic = [
    <?php echo $lic['names']; ?>
  ];

  const dataLic = {
    labels: labelsLic,
    datasets: [{
      label: 'Alumnos activos',
      backgroundColor: 'rgb(255, 99, 132)',
      borderColor: 'rgb(255, 99, 132)',
      data: [<?php echo $lic["numbers"] ?>],
    }]
  };
  const configLic = {
    type: 'line',
    data: dataLic,
    options: {}
  };
  

  const labelsMae = [
    <?php echo $mae['names']; ?>
  ];

  const dataMae = {
    labels: labelsMae,
    datasets: [{
      label: 'Alumnos activos',
      backgroundColor: 'rgb(255, 99, 132)',
      borderColor: 'rgb(255, 99, 132)',
      data: [<?php echo $mae["numbers"] ?>],
    }]
  };
  const configMae = {
    type: 'line',
    data: dataMae,
    options: {}
  };

  const labelsDoc = [
    <?php echo $doc['names']; ?>
  ];

  const dataDoc = {
    labels: labelsDoc,
    datasets: [{
      label: 'Alumnos activos',
      backgroundColor: 'rgb(255, 99, 132)',
      borderColor: 'rgb(255, 99, 132)',
      data: [<?php echo $doc["numbers"] ?>],
    }]
  };
  const configDoc = {
    type: 'line',
    data: dataDoc,
    options: {}
  };
  const labelsMas = [
    <?php echo $mas['names']; ?>
  ];

  const dataMas = {
    labels: labelsMas,
    datasets: [{
      label: 'Alumnos activos',
      backgroundColor: 'rgb(255, 99, 132)',
      borderColor: 'rgb(255, 99, 132)',
      data: [<?php echo $mas["numbers"] ?>],
    }]
  };
  const configMas = {
    type: 'line',
    data: dataMas,
    options: {}
  };
  const labelsTecno = [
    'mcda',
    'miti'
  ];

  const dataTecno = {
    labels: labelsTecno,
    datasets: [{
      label: 'Alumnos sector tecnológico',
      backgroundColor: 'rgb(255, 99, 132)',
      borderColor: 'rgb(255, 99, 132)',
      data: [ <?php echo $datos->getStudentsByKey('mcd') ?>, <?php echo $datos->getStudentsByKey('miti')  ?> ],
    }]
  };
  const configTecno = {
    type: 'line',
    data: dataTecno,
    options: {}
  };

  const labelsLicP = [
    <?php echo $licP['names']; ?>
  ];

  const dataLicP = {
    labels: labelsLic,
    datasets: [
        {
            label: 'hoy',
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',
            borderWidth: 1,
            data: [<?php echo $lic["numbers"]; ?>],
        },
        {
      label: 'Anterior',
      backgroundColor: 'rgb(54, 162, 235)',
      borderColor: 'rgb(54, 162, 235)',
      borderWidth: 1,
      data: [<?php echo $licP["numbers"]; ?>],
    }]
  };
  const configLicP = {
    type: 'line',
    data: dataLicP,
    options: {}
  };
  const labelsMaeP = [
    <?php echo $maeP['names']; ?>
  ];

  const dataMaeP = {
    labels: labelsMaeP,
    datasets: [
        {
            label: 'hoy',
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',
            borderWidth: 1,
            data: [<?php echo $mae["numbers"]; ?>],
        },
        {
      label: 'Anterior',
      backgroundColor: 'rgb(54, 162, 235)',
      borderColor: 'rgb(54, 162, 235)',
      borderWidth: 1,
      data: [<?php echo $maeP["numbers"]; ?>],
    }]
  };
  const configMaeP = {
    type: 'line',
    data: dataMaeP,
    options: {
      
    },
    plugins: ['chartjs-plugin-annotation']
  };
  const labelsHisto = [
    <?php echo $historico["names"]; ?>
  ];

  const dataHisto = {
    labels: labelsHisto,
    datasets: [{
      label: 'cambio diario de alumnos activos',
      backgroundColor: 'rgb(255, 99, 132)',
      borderColor: 'rgb(255, 99, 132)',
      data: [ <?php echo $historico["numbers"]; ?> ],
    }
  ]
  };
  const configHisto = {
    type: 'line',
    data: dataHisto,
    options: {
        scales: {
            y: {
                ticks: {
                    stepSize: 1
                }
            }
        },
        plugins: {
        autocolors: false,
        annotation:{
          annotations: {
            box1: {
              adjustScaleRange: false,
              type: 'line',
              yMin: 0,
              yMax: 0,
              borderColor: 'rgba(0,0,0,1)',
              borderDash: [10],
              borderWidth: 0.5,
              label: {
                content: 'cero',
                backgroundColor: 'rgba(0,0,0,0.3)',
                enabled: false
              }
            }
          }

        }
      }
    },
    plugins: ['chartjs-plugin-annotation']
  };

</script>
<script>
  const myChart = new Chart(
    document.getElementById('myChart'),
    config
  );
  const myChartLic = new Chart(
    document.getElementById('myChartLicenciaturas'),
    configLic
  );
  const myChartMae = new Chart(
    document.getElementById('myChartMaestrias'),
    configMae
  );
  const myChartDoc = new Chart(
    document.getElementById('myChartDoctorados'),
    configDoc
  );
  const myChartMas = new Chart(
    document.getElementById('myChartMasters'),
    configMas
  );
  const myChartTecno = new Chart(
    document.getElementById('myChartTecnologicos'),
    configTecno
  );
  const myChartLicP = new Chart(
    document.getElementById('myChartLicVs'),
    configLicP
  );
  const myChartMaeP = new Chart(
    document.getElementById('myChartTecnologicosVs'),
    configMaeP
  );
  const myChartHisto = new Chart(
    document.getElementById('myChartPeriod'),
    configHisto
  );
</script>
</body>
</html>