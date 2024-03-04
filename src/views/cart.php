<?php

session_start();
if(isset($_SESSION['email'])){
  $email = $_SESSION['email'];
  include('../config/connection.php');
  $query = 'SELECT detail, price FROM product ORDER BY detail asc';
  $result = $connect -> query($query);
  if($result->num_rows > 0){
    
?>

<!DOCTYPE html>
<html lang="en">
<?php include('../config/header.html'); ?>
<script>
    function addProduct(){
      var producto = document.getElementById('producto').value;
      var cantidad = parseInt(document.getElementById('cantidad').value);
      
      if(!isNaN(cantidad) && cantidad > 0){
        $.ajax({
          url:'../config/getPrice.php',
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

<?php include('../config/navbar.php'); ?>

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
                  <select class="custom-select" name="producto" id="producto">
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
  <?php include('../config/footer.html'); ?>
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

</body>
</html>
<?php 
}
} else {
  header("Location: ../../index.php");
  exit;
} ?>
