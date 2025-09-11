<?php
$servername = "localhost";
$username   = "root";     // usuario por defecto en XAMPP
$password   = "";         // sin contraseña
$dbname     = "database"; // nombre de la base que creaste

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("❌ Error de conexión: " . $conn->connect_error);
}
?>
