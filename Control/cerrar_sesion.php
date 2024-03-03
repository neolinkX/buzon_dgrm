<?php
session_start(); // Iniciar la sesión si no está iniciada

// Destruir todas las variables de sesión
session_unset();

// Destruir la sesión
session_destroy();

// Redirigir al usuario a la página de inicio de sesión o a otra página
header("Location: ../index.php"); // Cambia "index.php" a la página a la que deseas redirigir
exit();
?>
