<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit;
}
require_once "../config.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel Administrativo - ECO BELÉN</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="style.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
  <header class="admin-header">
    <h1>📰 ECO BELÉN - Panel Administrativo</h1>
    <a href="logout.php" class="btn-logout">Cerrar sesión</a>
  </header>

  <main class="dashboard">
    <button class="btn-primary" id="openModalBtn">➕ Agregar nuevo periódico</button>

    <section class="list-section">
      <h2>Periódicos subidos</h2>
      <ul class="periodico-list" id="periodico-list">
        <?php
        $sql = "SELECT id, titulo, director, publicado_en FROM periodicos ORDER BY publicado_en DESC";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<li data-id='{$row['id']}'>
                        <div class='info'>
                            <strong>{$row['titulo']}</strong>
                            <span>📅 {$row['publicado_en']} | Dir: {$row['director']}</span>
                        </div>
                        <div class='actions'>
                            <button class='btn-action edit-btn'>✏️ Editar</button>
                            <button class='btn-action delete-btn'>🗑️ Eliminar</button>
                        </div>
                      </li>";
            }
        } else {
            echo "<p>No hay periódicos subidos aún.</p>";
        }
        ?>
      </ul>
    </section>
  </main>

  <div id="uploadModal" class="modal">
    <div class="modal-content">
      <span class="close-btn">&times;</span>
      <h2 id="modal-title">Subir nuevo periódico</h2>
      <form id="uploadForm" enctype="multipart/form-data">
        <input type="hidden" name="action" id="form-action" value="add">
        <input type="hidden" name="id" id="periodico-id">
        <div class="form-step" data-step="1">
          <label for="titulo">Título del periódico</label>
          <input type="text" id="titulo" name="titulo" required>
        </div>
        <div class="form-step" data-step="2">
          <label for="director">Nombre del director</label>
          <input type="text" id="director" name="director" required>
        </div>
        <div class="form-step" data-step="3">
          <label for="participantes">Nombres de los participantes (separados por coma)</label>
          <textarea id="participantes" name="participantes"></textarea>
        </div>
        <div class="form-step" data-step="4">
          <label for="descripcion">Descripción (opcional)</label>
          <textarea id="descripcion" name="descripcion"></textarea>
        </div>
        <div class="form-step" data-step="5">
            <label for="fecha">Fecha de publicación</label>
            <input type="date" id="fecha" name="fecha" required>
        </div>
        <div class="form-step" data-step="6">
          <label for="archivo">Archivo PDF</label>
          <input type="file" id="archivo" name="archivo" accept="application/pdf">
        </div>

        <div class="modal-footer">
          <button type="button" class="btn-secondary" id="prevBtn" style="display:none;">← Anterior</button>
          <button type="button" class="btn-primary" id="nextBtn">Siguiente →</button>
          <button type="submit" class="btn-primary" id="submitBtn" style="display:none;">Finalizar</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>