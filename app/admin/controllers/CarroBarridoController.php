<?php
$titlePage = "Editar Carro de Barrido ";
require_once("../components/sidebar.php");

if ((isset($_POST["MM_FormCarroBarrido"])) && ($_POST["MM_FormCarroBarrido"] == "FormCarroBarrido")) {
    // Obtener los datos del formulario
    $id_registro_barrido = $_POST['id_registro_barrido'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $hora_inicio = $_POST['hora_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $hora_fin = $_POST['hora_fin'];
    $zonas = isset($_POST['zonas']) ? $_POST['zonas'] : [];
    $peso = $_POST['peso'];
    $observaciones = $_POST['observaciones'];

    try {
        // Iniciar transacción
        $connection->beginTransaction();

        // Actualizar los datos del carro de barrido
        $updateCarroBarrido = $connection->prepare("
            UPDATE carro_barrido SET 
                fecha_inicio = :fecha_inicio,
                hora_inicio = :hora_inicio,
                fecha_fin = :fecha_fin,
                hora_fin = :hora_fin,
                peso = :peso,
                observaciones = :observaciones
            WHERE id_registro_barrido = :id_registro_barrido
        ");

        $updateCarroBarrido->bindParam(':fecha_inicio', $fecha_inicio);
        $updateCarroBarrido->bindParam(':hora_inicio', $hora_inicio);
        $updateCarroBarrido->bindParam(':fecha_fin', $fecha_fin);
        $updateCarroBarrido->bindParam(':hora_fin', $hora_fin);
        $updateCarroBarrido->bindParam(':peso', $peso);
        $updateCarroBarrido->bindParam(':observaciones', $observaciones);
        $updateCarroBarrido->bindParam(':id_registro_barrido', $id_registro_barrido);

        $updateCarroBarrido->execute();

        // Borrar las zonas existentes asociadas al registro
        $queryEliminarZonas = $connection->prepare("DELETE FROM detalle_zonas WHERE id_registro = :id_registro");
        $queryEliminarZonas->bindParam(':id_registro', $id_registro_barrido);
        $queryEliminarZonas->execute();

        // Insertar las nuevas zonas seleccionadas
        foreach ($zonas as $zona) {
            $queryInsertarZona = $connection->prepare("INSERT INTO detalle_zonas (id_registro, id_zona) VALUES (:id_registro, :id_zona)");
            $queryInsertarZona->bindParam(':id_registro', $id_registro_barrido);
            $queryInsertarZona->bindParam(':id_zona', $zona);
            $queryInsertarZona->execute();
        }

        // Confirmar la transacción
        $connection->commit();

        // Redirigir con éxito
        showErrorOrSuccessAndRedirect("success", "Actualización Exitosa", "Los datos del carro de barrido se han actualizado correctamente", "index.php");

    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $connection->rollBack();
        showErrorOrSuccessAndRedirect("error", "Error de Actualización", "No se pudo actualizar los datos del carro de barrido: " . $e->getMessage(), "index.php");
    }
}


?>