<?php
session_start();
require_once("../../validation/sessionValidation.php");
require_once("../../../database/connection.php");
$db = new Database();
$connection = $db->conectar();

$documento = $_SESSION['documento'];

if (isset($_GET['id_ciudad'])) {
    $id_ciudad = $_GET['id_ciudad'];
    $confi_conductor = "NO";
    try {
        // Ajustamos la consulta para filtrar empleados por ciudad
        $query = "SELECT documento, nombres, apellidos FROM usuarios WHERE documento <> :documento AND id_ciudad = :id_ciudad AND confi_conductor = :confi_conductor";
        $queryData = $connection->prepare($query);
        $queryData->bindParam(':documento', $documento, PDO::PARAM_INT);
        $queryData->bindParam(':id_ciudad', $id_ciudad, PDO::PARAM_INT);
        $queryData->bindParam(':confi_conductor', $confi_conductor, PDO::PARAM_STR);
        $queryData->execute();
        $empleados = $queryData->fetchAll(PDO::FETCH_ASSOC);

        $_SESSION['empleados'] = $empleados;
        echo json_encode($_SESSION['empleados']);
        exit();
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al conectarse a la base de datos', 'detalle' => $e->getMessage()]);
        exit();
    }
} else {
    echo json_encode(['error' => 'ID de ciudad no válido']);
}