<?php

// Establecer el tiempo máximo de inactividad en segundos (10 minutos = 600 segundos)
$inactivityLimit = 100;
// Verificar si existe el timestamp de la última actividad
if (isset($_SESSION['last_activity'])) {
    // Calcular el tiempo que ha pasado desde la última actividad
    $timeSinceLastActivity = time() - $_SESSION['last_activity'];
    // Si ha pasado más del tiempo de inactividad permitido, cerrar sesión
    if ($timeSinceLastActivity > $inactivityLimit) {
        showErrorOrSuccessAndRedirect('info', '¡Opsss!', "Llevas mas de 5 minutos de inactividad", "index.php?logout");
        // Destruir la sesión
        exit();
    }
}

// Actualizar el timestamp de la última actividad
$_SESSION['last_activity'] = time();
