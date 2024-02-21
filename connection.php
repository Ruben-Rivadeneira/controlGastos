<?php
$servername = "localhost";
$username = "gastos";
$password = "gastos2024";
$database = "control_gastos";

$connect = new mysqli($servername, $username, $password, $database);

if ($connect->connect_error) {
    die("Error de conexión: " . $connect->connect_error);
}
?>
