<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/style.css">
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
$historico = $datos->getDifferenceBetweenDays('mfp','02-05-2022',4);
 
 ?>
    <div class="container mt-4">
        <div class="row">
            <!-- <div class="col-md-3">150 <br> Nuevas Ordenes</div>
            <div class="col-md-3">150 <br> Nuevas Ordenes</div>
            <div class="col-md-3">150 <br> Nuevas Ordenes</div>
            <div class="col-md-3">150 <br> Nuevas Ordenes</div> -->
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3" >
                    <div class="card-header text-center">Licenciaturas</div>
                    <div class="card-body">
                      <h5 class="card-title text-center">2,459</h5>
                      <!-- <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p> -->
                    </div>
                  </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-secondary mb-3">
                    <div class="card-header text-center">Maestrías</div>
                    <div class="card-body">
                      <h5 class="card-title text-center">2,526</h5>
                      <!-- <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p> -->
                    </div>
                  </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header text-center">Doctorados</div>
                    <div class="card-body">
                      <h5 class="card-title text-center">157</h5>
                      <!-- <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p> -->
                    </div>
                  </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-header text-center">Masters</div>
                    <div class="card-body">
                      <h5 class="card-title text-center">4</h5>
                      <!-- <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p> -->
                    </div>
                  </div>
            </div>
        </div>
    </div>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <h1 class="text-center">Licenciaturas</h1>
                <div>
                    <canvas id="myChart"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <h1 class="text-center">Maestrías</h1>
                <div>
                    <canvas id="myChartTecnologicos"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h1 class="text-center">Doctorados</h1>
                <div>
                    <canvas id="myChartDoctorados"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <h1 class="text-center">Masters</h1>
                <div>
                    <canvas id="myChartMasters"></canvas>
                </div>
            </div>
        </div>
    </div>
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
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>
</body>
</html>