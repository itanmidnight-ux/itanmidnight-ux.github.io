<?php
require_once "../config.php";

// Obtener periódicos ordenados por fecha de publicación (más reciente primero)
$sql = "SELECT id, titulo, director, publicado_en, archivo_pdf FROM periodicos ORDER BY publicado_en DESC";
$result = $conn->query($sql);
$periodicos_array = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $periodicos_array[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>ECO BELÉN - Periódicos Escolares</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="style.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
  <header class="public-header">
    <h1>📰 ECO BELÉN</h1>
    <p class="slogan">"La voz de nuestra comunidad escolar"</p>
    <nav>
      <a href="index.php">Inicio</a>
      <a href="#main-content">Explorar</a>
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
                echo "<div class='list-item'>
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
      <?php
      if (!empty($periodicos_array)) {
          $ultimo = $periodicos_array[0];
          echo "<h2>✨ Última edición</h2>
                <div class='card'>
                  <h3>{$ultimo['titulo']}</h3>
                  <p>📅 {$ultimo['publicado_en']} | Dir: {$ultimo['director']}</p>
                  <a href='view.php?id={$ultimo['id']}' class='btn-view'>Ver periódico</a>
                </div>";
      } else {
          echo "<p>No hay periódicos disponibles aún.</p>";
      }
      ?>
    </section>

    <aside class="side-panel comments-panel hidden" id="commentsPanel">
        <button class="toggle-button" id="toggleCommentsBtn">▶</button>
        <h3>💬 Comentarios</h3>
        <p>No hay comentarios en la página principal. Vea un periódico para comentar.</p>
    </aside>

  </main>

  <script src="script.js"></script>
</body>
</html>
