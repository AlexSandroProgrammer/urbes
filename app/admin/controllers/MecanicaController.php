<!-- Procesar actualización -->
<?php
if ((isset($_POST["MM_FormMecanica"])) && ($_POST["MM_FormMecanica"] == "FormMecanica")) {
    $id_registro = $_POST['id_registro'];
    $id_estado = $_POST['id_estado'];
    $documento = $_POST['documento'];
    $labor_mantenimiento = $_POST['labor_mantenimiento'];
    $id_vehiculo = $_POST['id_vehiculo'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_finalizacion = $_POST['hora_finalizacion'];
    $observaciones = $_POST['observaciones'];

    $updateMecanica = $connection->prepare("UPDATE mecanica SET id_estado = :id_estado, documento = :documento, labor_mantenimiento = :labor_mantenimiento,id_vehiculo = :id_vehiculo, fecha_inicio = :fecha_inicio, fecha_fin = :fecha_fin, hora_inicio = :hora_inicio, hora_finalizacion = :hora_finalizacion, observaciones = :observaciones WHERE id_registro = :id_registro");

    $updateMecanica->bindParam(":id_estado", $id_estado);
    $updateMecanica->bindParam(":documento", $documento);
    $updateMecanica->bindParam(":labor_mantenimiento", $labor_mantenimiento);
    $updateMecanica->bindParam(":id_vehiculo", $id_vehiculo);
    $updateMecanica->bindParam(":fecha_inicio", $fecha_inicio);
    $updateMecanica->bindParam(":fecha_fin", $fecha_fin);
    $updateMecanica->bindParam(":hora_inicio", $hora_inicio);
    $updateMecanica->bindParam(":hora_finalizacion", $hora_finalizacion);
    $updateMecanica->bindParam(":observaciones", $observaciones);
    $updateMecanica->bindParam(":id_registro", $id_registro);
    $updateMecanica->execute();
    if ($updateMecanica) {
        showErrorOrSuccessAndRedirect("success", "Actualización Exitosa", "Los datos del registro mecanica vehiculo compactador se han actualizado correctamente", "mecanica_vehiculo.php");
    } else {
        showErrorOrSuccessAndRedirect("error", "Error de Actualización", "No se pudo actualizar los datos del registro mecanica vehiculo compactador", "mecanica_vehiculo.php");
    }
}
?>