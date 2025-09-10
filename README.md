###Este es un proyecto de base de demostración para una directiva la cual decidira si seguimos con el proyecto.

###proyecto: 
PERIODICO DIGITAL ESCOLAR (ECO BELEN)

###descripción
Este proyecto es un sistema de gestión de contenido (CMS) simple y seguro diseñado para la creación y administración de un periódico digital escolar. Permite a los administradores subir ediciones en formato PDF a través de un panel de control privado y las muestra al público en una página web. Los lectores pueden visualizar los periódicos y dejar comentarios.
🚀 Características Principales
Panel de Administración (/admin)
Acceso Seguro: Sistema de login y autenticación robusto que se conecta a una base de datos.
Gestión de Periódicos: Los administradores pueden subir nuevas ediciones en PDF, así como editar y eliminar las existentes.
Formulario Multipasos: Un formulario interactivo y guiado para la subida de periódicos, haciendo el proceso más sencillo.
Cierre de Sesión Seguro: Botón para cerrar la sesión de forma controlada.

Página Pública (/public)
Visualización de Última Edición: Muestra la edición más reciente del periódico en la página principal.
Archivo Histórico: Permite a los lectores navegar y acceder a ediciones anteriores.
Visualizador de PDF Integrado: Los periódicos se pueden ver directamente en el navegador sin necesidad de descargarlos.
Sistema de Comentarios: Los lectores pueden dejar sus opiniones y comentarios en cada edición.
⚙️ Tecnologías Utilizadas
Backend: PHP
Base de datos: MySQL
Frontend: HTML5, CSS3, JavaScript
Servidor Web: Se requiere un servidor con soporte para PHP (como XAMPP, WAMPP, MAMP o Apache).

Instalación y Configuración
Configurar la Base de Datos:
Abre tu gestor de bases de datos (por ejemplo, phpMyAdmin).
Crea una nueva base de datos llamada periodico.
Importa el archivo db/database.sql en la base de datos que acabas de crear. Esto generará todas las tablas necesarias (users, periodicos, comentarios) y un usuario administrador inicial (fabian, con contraseña fabian123).
Configurar la Conexión:
Abre el archivo config.php.
Ajusta las credenciales de la base de datos ($host, $dbname, $username, $password) si no usas la configuración por defecto de tu servidor local.
Permisos de Carpeta:
Asegúrate de que la carpeta uploads/ tiene los permisos de escritura necesarios para que el servidor pueda guardar los archivos PDF.

Uso del Sistema
Acceso al Panel Administrativo:
Navega a la carpeta admin/ en tu navegador (localhost/eco-belen/admin).
Inicia sesión con el usuario y contraseña del administrador configurado en la base de datos (fabian, fabian123).
Gestión de Contenido:
Desde el panel, haz clic en "Agregar nuevo periódico" para usar el formulario y subir un PDF.
Puedes usar los botones "Editar" y "Eliminar" en la lista para gestionar cada edición.
Visualización Pública:
Para ver la página pública, simplemente navega a la raíz del proyecto (localhost/eco-belen/public/). El último periódico subido se mostrará automáticamente

Auditor: itan midnight.







