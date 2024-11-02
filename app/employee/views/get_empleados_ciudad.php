<?php
session_start();
require_once("../../validation/sessionValidation.php");
require_once("../../../database/connection.php");
$db = new Database();
$connection = $db->conectar();

$documento = $_SESSION['documento'];
error_log('Documento en sesión: ' . $documento);  // Verificar que el documento esté en sesión

if (isset($_GET['id_ciudad'])) {
    $id_ciudad = $_GET['id_ciudad'];
    error_log('ID ciudad: ' . $id_ciudad);  // Verificar el ID de ciudad
    $id_tipo_usuario = 3;
    try {
        $query = "SELECT documento, nombres, apellidos FROM usuarios WHERE documento <> :documento AND id_ciudad = :id_ciudad AND id_tipo_usuario = :id_tipo_usuario";
        $queryData = $connection->prepare($query);
        $queryData->bindParam(':documento', $documento, PDO::PARAM_INT);
        $queryData->bindParam(':id_ciudad', $id_ciudad, PDO::PARAM_INT);
        $queryData->bindParam(':id_tipo_usuario', $id_tipo_usuario, PDO::PARAM_STR);
        $queryData->execute();
        $empleados = $queryData->fetchAll(PDO::FETCH_ASSOC);

        // Verificar si la consulta devuelve resultados
        if (empty($empleados)) {
            error_log('No se encontraron empleados para la ciudad seleccionada.');
        } else {
            error_log('Empleados encontrados: ' . print_r($empleados, true));
        }
        echo json_encode($empleados);
        exit();
    } catch (PDOException $e) {
        error_log('Error en la base de datos: ' . $e->getMessage());  // Log de error de BD
        echo json_encode(['error' => 'Error al conectarse a la base de datos', 'detalle' => $e->getMessage()]);
        exit();
    }
} else {
    error_log('ID de ciudad no válido');
    echo json_encode(['error' => 'ID de ciudad no válido']);
}