<?php

$documento = $_SESSION['documento']; // Número de documento a buscar
$id_estado = 4; // ID de estado a validar

// CONSULTA A BASE DE DATOS
$query_areas = "SELECT 1 FROM areas_publicas WHERE documento = :documento AND id_estado = :id_estado LIMIT 1";
$query_barridos = "SELECT 1 FROM carro_barrido WHERE documento = :documento AND id_estado = :id_estado LIMIT 1";
$query_mecanica = "SELECT 1 FROM mecanica WHERE documento = :documento AND id_estado = :id_estado LIMIT 1";
$query_compactador = "SELECT 1 FROM vehiculo_compactador WHERE documento = :documento AND id_estado = :id_estado LIMIT 1";
$query_recoleccion = "SELECT 1 FROM recoleccion_relleno WHERE documento = :documento AND id_estado = :id_estado LIMIT 1";


try {
    // Asumiendo que $pdo es tu conexión PDO a la base de datos
    $stmt1 = $connection->prepare($query_areas);
    $stmt2 = $connection->prepare($query_barridos);
    $stmt3 = $connection->prepare($query_mecanica);
    $stmt4 = $connection->prepare($query_compactador);
    $stmt5 = $connection->prepare($query_recoleccion);
    // Array con los parámetros a vincular
    $params = ['documento' => $documento, 'id_estado' => $id_estado];

    // Ejecutar las consultas
    $stmt1->execute($params);
    $stmt2->execute($params);
    $stmt3->execute($params);
    $stmt4->execute($params);
    $stmt5->execute($params);
    // Verificar si alguna consulta encontró coincidencias
    if ($stmt1->fetch() || $stmt2->fetch() || $stmt3->fetch() || $stmt4->fetch() || $stmt5->fetch()) {
        showErrorOrSuccessAndRedirectInfo('warning', '¡Hey!', "Te hace falta terminar de rellenar un formulario, presiona clic en el boton para dirigirte al panel de formularios pendientes", "pendientes.php");
    }
} catch (PDOException $e) {
    // Manejar errores de base de datos
    echo "Error en la consulta: " . $e->getMessage();
}