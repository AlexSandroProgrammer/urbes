<?php

// Establecer el tiempo máximo de inactividad en segundos (10 minutos = 600 segundos)
$inactivityLimit = 100; 

// Verificar si existe el timestamp de la última actividad
if (isset($_SESSION['last_activity'])) {
    // Calcular el tiempo que ha pasado desde la última actividad
    $timeSinceLastActivity = time() - $_SESSION['last_activity'];

    // Si ha pasado más del tiempo de inactividad permitido, cerrar sesión
    if ($timeSinceLastActivity > $inactivityLimit) {
        // Destruir la sesión
        session_unset(); // Destruye todas las variables de la sesión
        session_destroy(); // Destruye la sesión en sí

        // Mostrar el mensaje de error
        echo '<div style="color: red; font-weight: bold; text-align: center; margin-top: 20px;">
                Tu sesión ha expirado debido a la inactividad. Serás redirigido al inicio de sesión en 5 segundos.
              </div>';

        // Esperar 5 segundos y redirigir al inicio de sesión
        header("refresh:5;url=../index.php");
        exit();
    }
}

// Actualizar el timestamp de la última actividad
$_SESSION['last_activity'] = time();
?>
