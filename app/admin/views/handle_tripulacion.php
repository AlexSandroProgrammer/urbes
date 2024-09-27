<?php
session_start();
require_once("../../validation/sessionValidation.php");
require_once("../../../database/connection.php");
$db = new Database();
$connection = $db->conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $documento = $_POST['documento'];
    $id_registro = $_POST['id_registro'];
    $action = $_POST['action'];
    try {
        // Usar una transacción para garantizar que la operación se realice correctamente
        $connection->beginTransaction();

        if ($action === 'add') {
            // Insertar en detalle_tripulacion
            $query = "INSERT INTO detalle_tripulacion (id_registro, documento) VALUES (:id_registro, :documento)";
            $stmt = $connection->prepare($query);
            $stmt->bindParam(':id_registro', $id_registro);
            $stmt->bindParam(':documento', $documento);
            $stmt->execute();
            $response = ['status' => 'success', 'message' => 'Empleado agregado exitosamente.'];
        } elseif ($action === 'remove') {
            // Eliminar de detalle_tripulacion
            $query = "DELETE FROM detalle_tripulacion WHERE id_registro = :id_registro AND documento = :documento";
            $stmt = $connection->prepare($query);
            $stmt->bindParam(':id_registro', $id_registro);
            $stmt->bindParam(':documento', $documento);
            $stmt->execute();
            $response = ['status' => 'error', 'message' => 'Empleado eliminado exitosamente.'];
        }

        // Confirmar la transacción
        $connection->commit();
    } catch (Exception $e) {
        // Si ocurre un error, revertir la transacción
        $connection->rollBack();
        $response = ['status' => 'error', 'message' => 'Error: ' . $e->getMessage()];
    }

    // Devolver una respuesta en formato JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}