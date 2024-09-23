<!-- Procesar actualización -->
<?php
if ((isset($_POST["MM_FormUpdatePublic"])) && ($_POST["MM_FormUpdatePublic"] == "FormUpdatePublic")) {
    $id_registro = $_POST['id_registro'];
    $estado = $_POST['estado'];
    $labor = $_POST['labor'];
    $documento = $_POST['documento'];
    $ciudad = $_POST['ciudad'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_finalizacion = $_POST['fecha_finalizacion'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fin = $_POST['hora_fin'];
    $peso = $_POST['peso'];
    $observaciones = $_POST['observaciones'];

    $updateAreaPublica = $connection->prepare("UPDATE areas_publicas SET id_estado = :estado, id_labor = :labor, documento = :documento, id_ciudad = :ciudad, fecha_inicio = :fecha_inicio, fecha_finalizacion = :fecha_finalizacion, hora_inicio = :hora_inicio, hora_finalizacion = :hora_fin, peso = :peso, observaciones = :observaciones WHERE id_registro = :id_registro");
    
    $updateAreaPublica->bindParam(":estado", $estado);
    $updateAreaPublica->bindParam(":labor", $labor);
    $updateAreaPublica->bindParam(":documento", $documento);
    $updateAreaPublica->bindParam(":ciudad", $ciudad);
    $updateAreaPublica->bindParam(":fecha_inicio", $fecha_inicio);
    $updateAreaPublica->bindParam(":fecha_finalizacion", $fecha_finalizacion);
    $updateAreaPublica->bindParam(":hora_inicio", $hora_inicio);
    $updateAreaPublica->bindParam(":hora_fin", $hora_fin);
    $updateAreaPublica->bindParam(":peso", $peso);
    $updateAreaPublica->bindParam(":observaciones", $observaciones);
    $updateAreaPublica->bindParam(":id_registro", $id_registro);
    
    if ($updateAreaPublica->execute()) {
        showErrorOrSuccessAndRedirect("success", "Actualización Exitosa", "Los datos del área pública se han actualizado correctamente", "index.php");
    } else {
        showErrorOrSuccessAndRedirect("error", "Error de Actualización", "No se pudo actualizar los datos del área pública", "index.php");
    }
}
?>