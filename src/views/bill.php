<?php
session_start();
if(isset($_SESSION['email'])){
  $email = $_SESSION['email'];
  include('../config/connection.php');
  $sql = "SELECT id FROM users WHERE email = '".$email."'";
  $result = $connect -> query($sql);
  $user_id = $result->fetch_assoc()['id'];
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      
      $fecha = $_POST["date"];
      $categoria = $_POST["category"];
      $descripcion = $_POST["description"];
      $monto = $_POST["amount"];
      $metodo_pago = $_POST["pay"];
  
      $sql = "INSERT INTO bill (date, category, description, amount, pay, user_id) 
              VALUES ('$fecha', '$categoria', '$descripcion', $monto, '$metodo_pago','$user_id')";
  
      if ($connect->query($sql) === TRUE) {
          echo "<script>alert('Registro exitoso');</script>";
      } else {
          echo "Error al registrar el gasto: " . $connect->error;
      }
  
      $connect->close();
  }
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
            <h1 class="m-0">Gastos</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Inicio</a></li>
              <li class="breadcrumb-item active">Gastos v1</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <!-- jquery validation -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Registro de Gastos</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form id="quickForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="card-body">
                  <div class="form-group">
                    <label for="date">Fecha:</label>
                    <input type="date" name="date" class="form-control" id="date" required>
                  </div>
                  <div class="form-group">
                    <label for="category">Categoría:</label>
                    <select class="custom-select form-control-border" name="category" id="category" required>
                      <option value="Alimentos">Alimentos</option>
                      <option value="Transporte">Transporte</option>
                      <option value="Vestimenta">Vestimenta</option>
                      <option value="Salud">Salud</option>
                      <option value="otros gastos">Otros Gastos</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="description">Descripción</label>
                    <input type="text" name="description" class="form-control" id="description" required>
                  </div>
                  <div class="input-group">
                    <label for="amount">Monto:</label>
                    <div class="input-group-prepend">
                      <span class="input-group-text">$</span>
                    </div>
                    <input type="text" class="form-control" name="amount" id="amount">
                  </div>
                  <div class="form-group">
                    <label for="pay">Método de Pago:</label>
                    <select class="custom-select form-control-border" name="pay" id="pay" required>
                      <option value="Efectivo">Efectivo</option>
                      <option value="Transferencia">Transferencia</option>
                      <option value="Tarjeta de Débito">Tarjeta de Débito</option>
                      <option value="Tarjeta de Crédito">Tarjeta de Crédito</option>
                    </select>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </section>
    <!-- /Main content -->
  </div>
  <!-- /.content-wrapper -->
  
<?php
  include('../config/footer.html');
  ?>
</body>
</html>
<?php } else {
  header("Location: ../index.php");
  exit;
} ?>
