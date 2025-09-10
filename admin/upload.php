<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die(json_encode(['status' => 'error', 'message' => '❌ No autorizado.']));
}

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'] ?? 'add';
    $titulo = htmlspecialchars(trim($_POST['titulo']));
    $director = htmlspecialchars(trim($_POST['director']));
    $participantes = htmlspecialchars(trim($_POST['participantes']));
    $descripcion = htmlspecialchars(trim($_POST['descripcion']));
    $fecha = $_POST['fecha'];
    $usuario_id = $_SESSION['user_id'];
    $response = ['status' => 'error', 'message' => ''];

    // Lógica para añadir un nuevo periódico
    if ($action === 'add') {
        if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
            $response['message'] = '❌ Error: Archivo PDF no proporcionado o inválido.';
            echo json_encode($response);
            exit();
        }

        $archivoTmp = $_FILES['archivo']['tmp_name'];
        $archivoExt = strtolower(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION));

        if ($archivoExt !== "pdf") {
            $response['message'] = '❌ Error: Solo se permiten archivos PDF.';
            echo json_encode($response);
            exit();
        }

        $nuevoNombre = uniqid("periodico_") . ".pdf";
        $rutaFinal = "../uploads/" . $nuevoNombre;

        if (move_uploaded_file($archivoTmp, $rutaFinal)) {
            $stmt = $conn->prepare("INSERT INTO periodicos (titulo, director, participantes, descripcion, publicado_en, archivo_pdf, usuario_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssi", $titulo, $director, $participantes, $descripcion, $fecha, $nuevoNombre, $usuario_id);

            if ($stmt->execute()) {
                $response['status'] = 'ok';
                $response['message'] = '✅ Periódico subido correctamente.';
            } else {
                $response['message'] = '❌ Error al guardar en la base de datos.';
            }
            $stmt->close();
        } else {
            $response['message'] = '❌ Error al mover el archivo subido.';
        }
    }

    // Lógica para editar un periódico existente
    else if ($action === 'edit') {
        $id = intval($_POST['id']);

        // Manejar la subida de un nuevo archivo si se proporciona
        $nuevo_archivo_sql = "";
        $nuevo_archivo_bind = [];
        if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
            $archivoTmp = $_FILES['archivo']['tmp_name'];
            $archivoExt = strtolower(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION));

            if ($archivoExt !== "pdf") {
                $response['message'] = '❌ Error: Solo se permiten archivos PDF para el reemplazo.';
                echo json_encode($response);
                exit();
            }

            // Eliminar el archivo antiguo
            $stmt_old = $conn->prepare("SELECT archivo_pdf FROM periodicos WHERE id = ?");
            $stmt_old->bind_param("i", $id);
            $stmt_old->execute();
            $result_old = $stmt_old->get_result();
            if ($result_old->num_rows > 0) {
                $old_file = $result_old->fetch_assoc()['archivo_pdf'];
                if (file_exists("../uploads/" . $old_file)) {
                    unlink("../uploads/" . $old_file);
                }
            }
            $stmt_old->close();

            $nuevoNombre = uniqid("periodico_") . ".pdf";
            $rutaFinal = "../uploads/" . $nuevoNombre;
            if (move_uploaded_file($archivoTmp, $rutaFinal)) {
                $nuevo_archivo_sql = ", archivo_pdf = ?";
                $nuevo_archivo_bind[] = $nuevoNombre;
            } else {
                $response['message'] = '❌ Error al subir el nuevo archivo.';
                echo json_encode($response);
                exit();
            }
        }

        $sql = "UPDATE periodicos SET titulo = ?, director = ?, participantes = ?, descripcion = ?, publicado_en = ? " . $nuevo_archivo_sql . " WHERE id = ?";
        $stmt = $conn->prepare($sql);

        $params_bind = array_merge([$titulo, $director, $participantes, $descripcion, $fecha], $nuevo_archivo_bind, [$id]);

        $types = "sssssi"; // 5 strings + 1 integer
        if (!empty($nuevo_archivo_bind)) {
            $types = "sssssi"; // 6 strings + 1 integer
        }

        $stmt->bind_param($types, ...$params_bind);

        if ($stmt->execute()) {
            $response['status'] = 'ok';
            $response['message'] = '✅ Periódico actualizado correctamente.';
        } else {
            $response['message'] = '❌ Error al actualizar en la base de datos.';
        }
        $stmt->close();
    }

    echo json_encode($response);
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => '❌ Solicitud no válida.']);
}
?>