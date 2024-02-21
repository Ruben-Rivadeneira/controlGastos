<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "control_gastos";

$connect = new mysqli($servername, $username, $password, $database);

if ($connect->connect_error) {
    die("Error de conexión: " . $connect->connect_error);
}
?>
