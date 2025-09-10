document.addEventListener("DOMContentLoaded", () => {
    // Lógica para enviar comentarios (existente y mejorada)
    const commentForm = document.getElementById("commentForm");
    const commentsList = document.getElementById("commentsList");

    if (commentForm && commentsList) {
        commentForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            const formData = new FormData(commentForm);

            try {
                const response = await fetch("comments.php", {
                    method: "POST",
                    body: formData,
                });

                const result = await response.json();
                if (result.status === "ok") {
                    // Crear el nuevo elemento del comentario y agregarlo a la lista
                    const newCommentDiv = document.createElement("div");
                    newCommentDiv.className = "comment";
                    newCommentDiv.innerHTML = `
                        <strong>${result.usuario}</strong>
                        <p>${result.comentario}</p>
                        <span>${result.fecha}</span>
                    `;
                    // Si no había comentarios, eliminar el mensaje "No hay comentarios"
                    const noCommentsMsg = commentsList.querySelector('p');
                    if(noCommentsMsg && noCommentsMsg.textContent.includes('No hay comentarios')) {
                        noCommentsMsg.remove();
                    }
                    commentsList.prepend(newCommentDiv); // Agregar al inicio de la lista
                    commentForm.reset(); // Limpiar el formulario
                } else {
                    alert("❌ Error al enviar el comentario: " + result.message);
                }
            } catch (error) {
                console.error("Error al enviar el comentario:", error);
                alert("❌ Ocurrió un error de conexión al enviar el comentario.");
            }
        });
    }

    // Lógica para los paneles laterales (ocultar/mostrar)
    const periodicosPanel = document.getElementById('periodicosPanel');
    const togglePeriodicosBtn = document.getElementById('togglePeriodicosBtn');
    const commentsPanel = document.getElementById('commentsPanel');
    const toggleCommentsBtn = document.getElementById('toggleCommentsBtn');

    if (periodicosPanel && togglePeriodicosBtn) {
        togglePeriodicosBtn.addEventListener('click', () => {
            periodicosPanel.classList.toggle('hidden');
            if (periodicosPanel.classList.contains('hidden')) {
                togglePeriodicosBtn.textContent = '▶';
            } else {
                togglePeriodicosBtn.textContent = '◀';
            }
        });
    }

    if (commentsPanel && toggleCommentsBtn) {
        // Asegurarse de que el panel de comentarios esté visible por defecto en view.php
        // y oculto en index.php, o según la lógica de tu HTML
        // En view.php, el panel NO tiene la clase 'hidden' inicialmente.
        // En index.php, el panel SÍ tiene la clase 'hidden' inicialmente.
        toggleCommentsBtn.addEventListener('click', () => {
            commentsPanel.classList.toggle('hidden');
            if (commentsPanel.classList.contains('hidden')) {
                toggleCommentsBtn.textContent = '◀';
            } else {
                toggleCommentsBtn.textContent = '▶';
            }
        });
    }

    // Animación para el header (opcional, para darle un toque extra)
    const header = document.querySelector('.public-header');
    if (header) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.style.padding = '10px 0';
                header.style.fontSize = '0.9em';
            } else {
                header.style.padding = '20px 0';
                header.style.fontSize = '1em';
            }
        });
    }
});
