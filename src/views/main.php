<?php
session_start();
if(isset($_SESSION['email'])){
  $email = $_SESSION['email'];
  include( "../config/connection.php" );
  $sql = "SELECT id FROM users WHERE email = '".$email."'";
  $result = $connect -> query($sql);
  $user_id = $result->fetch_assoc()['id'];
  $query = 'SELECT bill_amount, revenue_amount FROM total_amounts WHERE user_id="'.$user_id.'"';
  $result = $connect -> query($query);
  if( $result->num_rows > 0 ){
    $total = $result -> fetch_assoc();
  
?>

<!DOCTYPE html>
<html lang="en">
<?php
  include('../config/header.html');
  ?>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../../public/dist/img/controlGastosLogo.png" alt="AdminLTELogo" height="350" width="350">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="main.php" class="nav-link">Inicio</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>

      <!-- Notifications Dropdown Menu -->
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->
<?php
include('../config/navbar.php');
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Inicio</a></li>
              <li class="breadcrumb-item active">Dashboard v1</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php echo $total['revenue_amount']; ?><sup style="font-size: 20px">$</sup></h3>

                <p>Ingresos</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?php echo $total['bill_amount']; ?><sup style="font-size: 20px">$</sup></h3>

                <p>Gastos</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?php echo ((double)$total['revenue_amount']-(double)$total['bill_amount']);?><sup style="font-size: 20px">$</sup></h3>

                <p>Rentabilidad</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
          <section class="col-lg-7 connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->
            
            <?php
            $sqlBill = "SELECT MONTH(date) AS mes, YEAR(date) AS anio, SUM(amount) AS total FROM bill WHERE user_id=$user_id GROUP BY mes, anio";
            $resultBill = $connect -> query($sqlBill);
            $sqlRevenue = "SELECT MONTH(date) AS mes, YEAR(date) AS anio, SUM(amount) AS total FROM revenue WHERE user_id=$user_id GROUP BY mes, anio";
            $resultRevenue = $connect -> query($sqlRevenue);
            $labels = array();
            $dataBill = array();
            $dataRevenue = array();

            foreach ($resultBill as $row) {
              $mes = $row['mes'];
              $anio = $row['anio'];
              $totalBill = $row['total'];
          
              $label = date("F", mktime(0, 0, 0, $mes, 1)) . " " . $anio;
              $labels[] = $label;
              $dataBill[] = $totalBill;
          }
          
          foreach ($resultRevenue as $row) {
              $mes = $row['mes'];
              $anio = $row['anio'];
              $totalRevenue = $row['total'];
          
              $label = date("F", mktime(0, 0, 0, $mes, 1)) . " " . $anio;
              $index = array_search($label, $labels);
          
              if ($index === false) {
                  $labels[] = $label;
                  $dataRevenue[] = $totalRevenue;
                  $dataBill[] = 0; 
              } else {
                  $dataRevenue[$index] = $totalRevenue;
              }
          }
            ?>

            <canvas id="lineChart"></canvas>
            <script>
              var ctx = document.getElementById('lineChart').getContext('2d');
              var  Chart = new Chart(ctx, {
                type: 'line',
                data: {
                  labels: <?php echo json_encode($labels);?>,
                  datasets: [
                    {
                      label: 'Gastos',
                      data: <?php echo json_encode($dataBill); ?>,
                      backgroundColor: 'rgba(255, 99, 132, 0.2)',
                      borderColor: 'rgba(255, 99, 132, 1)',
                      borderWidth: 1
                    },
                    {
                      label: 'Ingresos',
                      data: <?php echo json_encode($dataRevenue); ?>,
                      backgroundColor: 'rgba(54, 162, 235, 0.2)',
                      borderColor: 'rgba(54, 162, 235, 1)',
                      borderWidth: 1
                    }
                  ]
                },
                options: {
                  scales: {
                    y: {
                      beginAtZero: true
                    }
                  }
                }
              });
            </script>
            <?php
            $sql = "SELECT MONTH(date) AS mes, YEAR(date) as anio, category, SUM(amount) AS total
                    FROM bill
                    WHERE user_id=$user_id
                    GROUP BY mes, anio, category";
            $result = $connect -> query($sql);

            $labels = array();
            $dataSets = array();

            foreach ($result as $row){
              $mesC = $row['mes'];
              $anio = $row['anio'];
              $categoria = $row['category'];
              $total = $row['total'];

              $label = date("F", mktime(0, 0, 0, $mes, 1)). " " . $anio;

              if(!in_array($label, $labels)){
                $labels[] = $label;
              }

              $clave_categoria = array_search($categoria, array_column($dataSets, 'label'));

              if($clave_categoria == false){
                $dataSets[] = array(
                  'label' => $categoria,
                  'data' => array_fill(0, count($labels),0)
                );
                $clave_categoria = count($dataSets) - 1;
              }

              $indice_mes = array_search($label, $labels);

              if($indice_mes !== false){
                $dataSets[$clave_categoria]['data'][$indice_mes] = $total;
              } else {
                $datasets[$clave_categoria]['data'][] = 0;
              }

              
            }
            ?>
            <div class="col-sm-6">
              <h1 class="m-0">Por categoría</h1>
            </div><!-- /.col -->
            <canvas id="barChart"></canvas>
            <script>
              document.addEventListener('DOMContentLoaded', function() {
                var ctx = document.getElementById('barChart').getContext('2d');
                var chart = new Chart(ctx, {
                  type: 'bar',
                  data: {
                    labels: <?php echo json_encode($labels); ?>,
                    datasets: <?php echo json_encode($dataSets); ?>
                  },
                  options: {
                    scales: {
                      y: {
                        beginAtZero: true
                      }
                    }
                  }
                });
              });
              
            </script>
            <!-- /.card -->
          </section>
          <!-- /.Left col -->
        </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php
  include('../config/footer.html');
  ?>
</body>
</html>
<?php
  } 
} else {
  header("Location: ../../index.php");
  exit;
} ?>
