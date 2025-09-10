<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die(json_encode(['status' => 'error', 'message' => '❌ No autorizado.']));
}

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    $stmt_select = $conn->prepare("SELECT archivo_pdf FROM periodicos WHERE id = ?");
    $stmt_select->bind_param("i", $id);
    $stmt_select->execute();
    $result_select = $stmt_select->get_result();

    if ($result_select->num_rows > 0) {
        $row = $result_select->fetch_assoc();
        $archivo_a_eliminar = "../uploads/" . $row['archivo_pdf'];

        $stmt_delete = $conn->prepare("DELETE FROM periodicos WHERE id = ?");
        $stmt_delete->bind_param("i", $id);

        if ($stmt_delete->execute()) {
            if (file_exists($archivo_a_eliminar)) {
                unlink($archivo_a_eliminar);
            }
            echo json_encode(['status' => 'ok', 'message' => '✅ Periódico y archivo eliminados correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => '❌ Error al eliminar de la base de datos.']);
        }
        $stmt_delete->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => ' ❌ Periódico no encontrado.']);
    }
    $stmt_select->close();

} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => '❌ Solicitud no válida.']);
}
?>