<!-- Procesar actualización -->
<?php
if ((isset($_POST["MM_FormCarroBarrido"])) && ($_POST["MM_FormCarroBarrido"] == "FormCarroBarrido")) {
    $id_registro_barrido = $_POST['id_registro_barrido'];
    $id_estado = $_POST['id_estado'];
    $documento = $_POST['documento'];
    $ciudad = $_POST['ciudad'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fin = $_POST['hora_fin'];
    $peso = $_POST['peso'];
    $observaciones = $_POST['observaciones'];

    $updateCarroBarrido = $connection->prepare("UPDATE carro_barrido SET id_estado = :id_estado, documento = :documento, ciudad = :ciudad, fecha_inicio = :fecha_inicio, fecha_fin = :fecha_fin, hora_inicio = :hora_inicio, hora_fin = :hora_fin, peso = :peso, observaciones = :observaciones WHERE id_registro_barrido = :id_registro_barrido");

    $updateCarroBarrido->bindParam(":id_estado", $id_estado);
    $updateCarroBarrido->bindParam(":documento", $documento);
    $updateCarroBarrido->bindParam(":ciudad", $ciudad);
    $updateCarroBarrido->bindParam(":fecha_inicio", $fecha_inicio);
    $updateCarroBarrido->bindParam(":fecha_fin", $fecha_fin);
    $updateCarroBarrido->bindParam(":hora_inicio", $hora_inicio);
    $updateCarroBarrido->bindParam(":hora_fin", $hora_fin);
    $updateCarroBarrido->bindParam(":peso", $peso);
    $updateCarroBarrido->bindParam(":observaciones", $observaciones);
    $updateCarroBarrido->bindParam(":id_registro_barrido", $id_registro_barrido);
    $updateCarroBarrido->execute();
    if ($updateCarroBarrido) {
        showErrorOrSuccessAndRedirect("success", "Actualización Exitosa", "Los datos del carro de barrido se han actualizado correctamente", "index.php");
    } else {
        showErrorOrSuccessAndRedirect("error", "Error de Actualización", "No se pudo actualizar los datos del carro de barrido", "index.php");
    }
}
?>