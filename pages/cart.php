<?php

session_start();
if(isset($_SESSION['email'])){
  $email = $_SESSION['email'];
  include('../connection.php');
  $query = 'SELECT detail, price FROM product';
  $result = $connect -> query($query);
  if($result->num_rows > 0){
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Control Gastos</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="../plugins/summernote/summernote-bs4.min.css">
  <script>
    function addProduct(){
      var producto = document.getElementById('producto').value;
      var cantidad = parseInt(document.getElementById('cantidad').value);
      
      if(!isNaN(cantidad) && cantidad > 0){
        $.ajax({
          url:'getPrice.php',
          method:'POST',
          data: { producto: producto},
          success: function(response) {
            var precioUnitario = parseFloat(response);
            var precioTotal = precioUnitario * cantidad;

            var fila = '<tr>';
            fila += '<td>' + producto + '</td>';
            fila += '<td>' + cantidad + '</td>';
            fila += '<td>' + precioUnitario.toFixed(2) + '</td>';
            fila += '<td>' + precioTotal.toFixed(2) + '</td>';
            fila += '</tr>';

            $('#tablaCompra tbody').append(fila);
            actualizarTotalCompra();
          }
        });
      } else {
        alert('Ingrese una cantidad válida');
      }
    }
     function actualizarTotalCompra(){
      var totalCompra = 0;
      $('#tablaCompra tbody tr').each(function() {
      var precioTotal = parseFloat($(this).find("td:eq(3)").text());
      totalCompra  += isNaN(precioTotal)  ? 0 : precioTotal;
      });
      $('#totalCompra').text(totalCompra.toFixed(2));
    }
  </script>
  
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../dist/img/controlGastosLogo.png" alt="AdminLTELogo" height="350" width="350">
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

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="../dist\img\controlGastosLogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Control Gastos</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="#" class="d-block"><?php echo $email; ?></a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item menu-open">
            <a href="main.php" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Compras
              </p>
            </a>
          </li>
          <li class="nav-item menu-open">
            <a href="bill.php" class="nav-link active">
              <i class="nav-icon fas fa-money-bill"></i>
              <p>
                Ingreso de Gastos
              </p>
            </a>
          </li>
          <li class="nav-item menu-open">
            <a href="product.php" class="nav-link active">
              <i class="nav-icon fas fa-shopping-cart"></i>
              <p>
                Registro de Productos
              </p>
            </a>
          </li>
          <li class="nav-item menu-open">
            <a href="cart.php" class="nav-link active">
              <i class="nav-icon fas fa-shopping-cart"></i>
              <p>
                Proyección de Compras
              </p>
            </a>
          </li>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Compras</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Inicio</a></li>
              <li class="breadcrumb-item active">Compras v1</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main row -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <!-- jquery validation -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Lista de Compras</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form id="formularioCompra" method="post">
                <div class="card-body">
                  <div class="form-group">
                    <label for="producto">Producto:</label>
                    <select name="producto" id="producto">
                      <?php
                        while ($row = $result->fetch_assoc()){
                        echo "<option value='". $row['detail'] . "'>". $row['detail'].'<br>'."</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="cantidad">Cantidad:</label>
                    <input type="number" name="cantidad" class="form-control" id="cantidad" required>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="button" class="btn btn-primary" onclick="addProduct()">Agregar</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
            <!-- Table -->
            <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Lista de Compras</h3>

                <div class="card-tools">
                  <div class="input-group input-group-sm" style="width: 150px;">
                    <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                    <div class="input-group-append">
                      <button type="submit" class="btn btn-default">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0" style="height: 300px;">
                <table class="table table-head-fixed text-nowrap" id="tablaCompra">
                  <thead>
                    <tr>
                      <th>Artículo</th>
                      <th>Cant.</th>
                      <th>V. Unitario</th>
                      <th>V. Total</th>
                    </tr>
                  </thead>
                  <tbody id="detailSales">
                  </tbody>
                </table>
                <p>Total de la Compra: <span id="totalCompra">0.00</span></p>
              </div>
              <!-- /.card-body -->
              <button id="btnGenerarPDF" class="btn btn-primary" onclick="generarPDF(event)" >Generar PDF</button>
            </div>
            <!-- /.card -->
          </div>
        </div>
            <!-- /.Table -->
          </div>
        </div>
      </div>
    </section>
    <!-- /.row (main row) -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2022-2024 <a href="">RBN Solutions</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0.0
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.debug.js"></script>
<script>
  function generarPDF(event){
    event.preventDefault()

    const tbody = document.querySelector('tbody')
    if(tbody.childElementCount === 0){
      alert('No existe registros en la tabla');
      return;
    }

    const str = recorrerTabla();

    const doc = new jsPDF()
    doc.text(10,10, str)
    doc.save('listaCompra.pdf')
  }

  function recorrerTabla(){
    let str = ''
    const table = document.getElementById('tablaCompra');
    for(let i = 0; i < table.rows.length; i++){
      str += '\n'
      for(let j = 0; j < table.rows[i].cells.length; j++){
        
          let col = table.rows[i].cells[j].innerText
          str += `-> ${col}`
        
      }
    }

    const total = document.querySelector('#totalCompra').innerText
    str += `\n\nTotal: $${total}\n`
    return str
}
  
</script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="../plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="../plugins/sparklines/sparkline.js"></script>
<!-- jQuery Knob Chart -->
<script src="../plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="../plugins/moment/moment.min.js"></script>
<script src="../plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="../plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
<?php 
}
} else {
  header("Location: ../index.php");
  exit;
} ?>
