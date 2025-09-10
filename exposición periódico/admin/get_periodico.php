?php
session_start();
require_once "../config.php";

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die(json_encode(['status' => 'error', 'message' => '❌ No autorizado.']));
}

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    http_response_code(400);
    die(json_encode(['status' => 'error', 'message' => '❌ ID de periódico no proporcionado.']));
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT titulo, director, participantes, descripcion, publicado_en FROM periodicos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $periodico = $result->fetch_assoc();
    echo json_encode(['status' => 'ok', 'data' => $periodico]);
} else {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => '❌ Periódico no encontrado.']);
}

$stmt->close();
?>
