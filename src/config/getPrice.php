<?php
include('connection.php');
$producto = $_POST['producto'];
$query = "SELECT price FROM product WHERE detail = '$producto'";
$resultado = $connect->query($query);

if($resultado->num_rows > 0 ){
    $fila = $resultado->fetch_assoc();
    echo $fila['price'];
} else {
    echo "0";
}

$connect -> close();
?>