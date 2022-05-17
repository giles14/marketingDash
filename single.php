<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/style.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-annotation/1.4.0/chartjs-plugin-annotation.min.js"></script>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Metrics</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <a class="nav-link" href="#">Inicio <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Dashboard</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
                Métricas
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="#">Una</a>
                <a class="dropdown-item" href="#">Dos</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Tres</a>
              </div>
            </li>
          </ul>
        </div>
      </nav>
      <?php include_once ('./Pantalla.php');
$datos = new Pantalla;
?>
<?php $datos->getStudentsByProgram("maestrias");
$datos->getAllStudentsByProgram();
$lic = $datos->getStudentsByProgram("licenciaturas",1);
$mae = $datos->getStudentsByProgram("maestrias",1);
$doc = $datos->getStudentsByProgram("doctorados", 1);
$mas = $datos->getStudentsByProgram("masters", 1);
$licP = $datos->historicalDataByProgram("licenciatura",25,04);
$maeP = $datos->historicalDataByProgram("maestria",25,04);
$historico = $datos->getDifferenceBetweenDays($_GET["programa"],'02-05-2022',11);
$historicoNum = $datos->getHistoBetweenDays($_GET["programa"],'02-05-2022',11);
$datos->getActiveStudentsNumber()
 ?>
<section id="generales">
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                <h1 class="nombre-dato text-center">Programa</h1>
                <h1 class="nombre-programa text-center"><?php echo $datos->getProgramNameFromKey($_GET["programa"])["nombre"] ?></h1>
                <h3 class="clave-programa text-center"><?php echo $_GET["programa"]; ?></h3>
            </div>
            <div class="col-md-3">
                <h1 class="nombre-dato text-center">Alumnos Activos</h1>
                <p class="activos text-center"><?php echo $datos->getHistoricalDataByKey($_GET["programa"], date("d"), date("m")); ?></p>
            </div>
            <div class="col-md-3">
                <h1 class="nombre-dato text-center">Selección de programa</h1>
                <form action="">
                    <div class="form-group">
                        <select id="program" class="form-control" name="programa" onchange="changeProgram()" id="programas">
                            <option disabled selected value> -- Seleccione un Programa -- </option>
                            <optgroup label="Licenciaturas">
                                <option value="LSP">Seguridad Pública</option>
                                <option value="LD">Derecho</option>
                                <option value="LCP">Ciencias Políticas</option>
                                <option value="LAE">Administración de Empresas</option>
                            </optgroup>
                            <optgroup label="Maestrías">
                                <option value="MSPP">Seguridad Pública y Políticas Públicas</option>
                                <option value="MAP">Administración y Políticas Públicas</option>
                                <option value="MEPP">Evaluación de Políticas Públicas</option>
                                <option value="MIT">Ingenieria en Tecnologías de la Información	</option>
                                <option value="MAN">Administración de Negocios MBA</option>
                                <option value="MCD">Ciencia de Datos Aplicada</option>
                                <option value="MFP">Finanzas Públicas</option>
                                <option value="MIG">Innovación y Gestión Educativa</option>
                                <option value="MAIS">Administración de Instituciones de Salud</option>
                            </optgroup>
                            <optgroup label="Masters">
                                <option value="MAG">Auditoría Gubernamental</option>
                                <option value="MSPA">Sistema Penal Acusatorio</option>
                                <option value="MMPO">Marketing Político y Opinión Pública</option>
                                <option value="MGPM">Gestión Pública Municipal</option>
                            </optgroup>
                            <optgroup label="Doctorados">
                                <option value="DPP">Políticas Públicas</option>
                                <option value="DSP">Seguridad Pública</option>
                            </optgroup>
                        </select>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</section>
<section id="grafica" class="mt-4 mb-4">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <canvas id="myChartPeriod"></canvas>
                
            </div>
            <div class="col-md-4 informacion">
                <h1 class="nombre-dato text-center">Información del mes</h1>
                <p class="anterior text-center">Respecto al día anterior <span class="ganancia">&#9652; 3%</span> <!-- &#9662; --> </p>
                <p class="rango text-center">Min: <span class="minimo"><?php  echo min( explode("," , $historicoNum["numbers"])) ?></span> Max: <span class="maximo"><?php  echo max( explode("," , $historicoNum["numbers"]))+3 ?></span></p>
                <p class="mes-actual text-center">Mayo</p>
                <p>Total de Alumnos <?php echo  $datos->studentNumber; ?></p>
            </div>
        </div>
    </div>
</section>
<section id="grafica-normal" class="mb-5">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <canvas id="programDaily"></canvas>
            </div>
        </div>
    </div>
</section>
    <footer id="main">
      <div class="container">
        <div class="row pt-5 pb-5">
          <div class="col-4">
            <h3>Grupo</h3>
            <ul>
              <li><a href="#">Link 1</a></li>
              <li><a href="#">Link 1</a></li>
              <li><a href="#">Link 1</a></li>
            </ul>
          </div>
          <div class="col-4">
            <h3>Link</h3>
          </div>
          <div class="col-4">
            <h3>Link</h3>
          </div>
        </div>
      </div>
    </footer>
    <footer id="bottom">
      <div class="container-fluid">
        <div class="row pt-5 pb-4">
          <div class="col-md-4">s</div>
          <div class="col-md-4"></div>
          <div class="col-md-2"></div>
          <div class="col-md-2"><p>Dashboard IEXE 1.0</p></div>
        </div>
      </div>
    </footer>
    <script>
        const labelsHistoNum = [
        <?php echo $historicoNum["names"]; ?>
    ];

  const dataHistoNum = {
    labels: labelsHistoNum,
    datasets: [{
      label: 'cambio diario de alumnos activos',
      backgroundColor: 'rgb(255, 99, 132)',
      borderColor: 'rgb(255, 99, 132)',
      data: [ <?php echo $historicoNum["numbers"]; ?> ],
    }
  ]
  };
  const configHistoNum = {
    type: 'line',
    data: dataHistoNum,
    options: {
        scales: {
            y: {
                ticks: {
                    stepSize: 1
                },
                suggestedMax: <?php  echo max( explode("," , $historicoNum["numbers"]))+3 ?>,
                suggestedMin: <?php echo min( explode("," , $historicoNum["numbers"]))-3 ?>
            }
        },
        borderWidth:1,
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
    type: 'bar',
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
          const myChartHisto = new Chart(
                document.getElementById('programDaily'),
                configHisto
            );
            const myChartHistoNum = new Chart(
                document.getElementById('myChartPeriod'),
                configHistoNum
            );
    </script>
    <script>
        function changeProgram(){
            var selected = document.getElementById("program").value;
            window.location.href = "/single.php?programa=" + selected;
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>
</body>
</html>