<?php
session_start();
require_once "../config.php";

// Recibimos los datos del formulario
$usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

// Validamos que no estén vacíos
if (empty($usuario) || empty($password)) {
    header("Location: index.html?error=fields");
    exit();
}

// Preparamos la consulta segura
$sql = "SELECT id, nombre, password FROM users WHERE nombre = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // Verificamos la contraseña hasheada
    if (password_verify($password, $user['password'])) {
        // Login correcto
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['nombre'];
        header("Location: dashboard.php");
        exit();
    }
}

// Login fallido (contraseña incorrecta o usuario no existe)
header("Location: index.html?error=login_failed");
exit();
?>