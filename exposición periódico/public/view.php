<?php
require_once "../config.php";

if (!isset($_GET['id'])) {
    header("Location: index.php"); // Redirige si no hay ID
    exit;
}

$id = intval($_GET['id']);

// Obtener detalles del periódico principal
$stmt = $conn->prepare("SELECT id, titulo, director, publicado_en, archivo_pdf FROM periodicos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows === 0) {
    header("Location: index.php?error=notfound"); // Redirige si no existe
    exit;
}
$periodico_principal = $result->fetch_assoc();

// Obtener todos los periódicos para el panel lateral (ordenados por más reciente)
$sql_todos = "SELECT id, titulo, director, publicado_en FROM periodicos ORDER BY publicado_en DESC";
$result_todos = $conn->query($sql_todos);
$periodicos_array = [];
if ($result_todos && $result_todos->num_rows > 0) {
    while ($row = $result_todos->fetch_assoc()) {
        $periodicos_array[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($periodico_principal['titulo']); ?> - ECO BELÉN</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="style.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body class="pdf-viewer-page">
  <header class="public-header">
    <h1>📰 ECO BELÉN</h1>
    <p class="slogan">"La voz de nuestra comunidad escolar"</p>
    <nav>
      <a href="index.php">← Volver al inicio</a>
    </nav>
  </header>

  <main class="public-content" id="main-content">

    <aside class="side-panel periodicos-panel" id="periodicosPanel">
      <button class="toggle-button" id="togglePeriodicosBtn">◀</button>
      <h3>📚 Ediciones Anteriores</h3>
      <div class="listado">
        <?php
        if (count($periodicos_array) > 0) {
            foreach ($periodicos_array as $row) {
                // Resalta el periódico actual
                $isActive = ($row['id'] == $periodico_principal['id']) ? 'active' : '';
                echo "<div class='list-item {$isActive}'>
                        <a href='view.php?id={$row['id']}' title='Ver {$row['titulo']}'>
                            <strong>{$row['titulo']}</strong>
                            <span>📅 {$row['publicado_en']} | Dir: {$row['director']}</span>
                        </a>
                      </div>";
            }
        } else {
            echo "<p>No hay periódicos disponibles aún.</p>";
        }
        ?>
      </div>
    </aside>

    <section class="main-periodico-display">
      <h2><?php echo htmlspecialchars($periodico_principal['titulo']); ?></h2>
      <p>📅 <?php echo htmlspecialchars($periodico_principal['publicado_en']); ?> | Dir: <?php echo htmlspecialchars($periodico_principal['director']); ?></p>

      <div class="pdf-container">
        <iframe src="../uploads/<?php echo htmlspecialchars($periodico_principal['archivo_pdf']); ?>" frameborder="0"></iframe>
      </div>
    </section>

    <aside class="side-panel comments-panel" id="commentsPanel">
        <button class="toggle-button" id="toggleCommentsBtn">▶</button>
        <h3>💬 Comentarios</h3>
        <form id="commentForm">
            <input type="hidden" name="id_periodico" value="<?php echo $periodico_principal['id']; ?>">
            <input type="text" name="usuario" placeholder="Tu nombre" required>
            <textarea name="comentario" placeholder="Escribe tu comentario..." required></textarea>
            <button type="submit">Enviar</button>
        </form>
        <div id="commentsList">
            <?php
            // Obtener comentarios para este periódico
            $cstmt = $conn->prepare("SELECT usuario_nombre, comentario, creado_en FROM comentarios WHERE periodico_id = ? ORDER BY creado_en DESC");
            $cstmt->bind_param("i", $id);
            $cstmt->execute();
            $cresult = $cstmt->get_result();

            if ($cresult && $cresult->num_rows > 0) {
                while ($c = $cresult->fetch_assoc()) {
                    echo "<div class='comment'>
                            <strong>" . htmlspecialchars($c['usuario_nombre']) . "</strong>
                            <p>" . htmlspecialchars($c['comentario']) . "</p>
                            <span>{$c['creado_en']}</span>
                          </div>";
                }
            } else {
                echo "<p>No hay comentarios aún. ¡Sé el primero en opinar!</p>";
            }
            $cstmt->close();
            ?>
        </div>
    </aside>

  </main>
  <script src="script.js"></script>
</body>
</html>
