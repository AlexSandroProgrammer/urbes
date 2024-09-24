<?php
$titlePage = "Actualizar Disposición Veh. Compactador";
require_once("../components/sidebar.php");

if ((isset($_POST["MM_FormUpDispocision"])) && ($_POST["MM_FormUpDispocision"] == "FormUpDispocision")) {
    // Obtener los datos del formulario
    $id_registro_disposicion = $_POST['id_registro_disposicion'];
    $estado = $_POST['estado'];
    $ciudad = $_POST['ciudad'];
    $vehiculo = $_POST['vehiculo'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $hora_inicio = $_POST['hora_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $hora_fin = $_POST['hora_fin'];
    $km_inicio = $_POST['km_inicio'];
    $km_fin = $_POST['km_fin'];
    $toneladas = $_POST['toneladas'];
    $galones = $_POST['galones'];
    $horometro_inicio = $_POST['horometro_inicio'];
    $horometro_fin = $_POST['horometro_fin'];
    $observaciones = $_POST['observaciones'];

    // Manejo de imágenes
    $foto_km_inicio = isset($_FILES['foto_km_inicio']) ? $_FILES['foto_km_inicio'] : null;
    $foto_km_fin = isset($_FILES['foto_km_fin']) ? $_FILES['foto_km_fin'] : null;

    $foto_km_inicio_name = !empty($foto_km_inicio['name']) ? uniqid() . '.' . pathinfo($foto_km_inicio['name'], PATHINFO_EXTENSION) : null;
    $foto_km_fin_name = !empty($foto_km_fin['name']) ? uniqid() . '.' . pathinfo($foto_km_fin['name'], PATHINFO_EXTENSION) : null;

    try {
        // Iniciar transacción
        $connection->beginTransaction();

        // Consulta para actualizar los datos de la disposición
        $updateDisposicion = $connection->prepare("UPDATE recoleccion_relleno SET 
            id_estado = :estado,
            ciudad = :ciudad,
            id_vehiculo = :vehiculo,
            fecha_inicio = :fecha_inicio,
            hora_inicio = :hora_inicio,
            fecha_fin = :fecha_fin,
            hora_finalizacion = :hora_fin,
            km_inicio = :km_inicio,
            km_fin = :km_fin,
            toneladas = :toneladas,
            galones = :galones,
            horometro_inicio = :horometro_inicio,
            horometro_fin = :horometro_fin,
            observaciones = :observaciones" .
            ($foto_km_inicio_name ? ", foto_kilometraje_inicial = :foto_km_inicio" : "") .
            ($foto_km_fin_name ? ", foto_kilometraje_final = :foto_km_fin" : "") . "
            WHERE id_recoleccion = :id_registro_disposicion
        ");

        // Vincular parámetros
        $updateDisposicion->bindParam(':estado', $estado);
        $updateDisposicion->bindParam(':ciudad', $ciudad);
        $updateDisposicion->bindParam(':vehiculo', $vehiculo);
        $updateDisposicion->bindParam(':fecha_inicio', $fecha_inicio);
        $updateDisposicion->bindParam(':hora_inicio', $hora_inicio);
        $updateDisposicion->bindParam(':fecha_fin', $fecha_fin);
        $updateDisposicion->bindParam(':hora_fin', $hora_fin);
        $updateDisposicion->bindParam(':km_inicio', $km_inicio);
        $updateDisposicion->bindParam(':km_fin', $km_fin);
        $updateDisposicion->bindParam(':toneladas', $toneladas);
        $updateDisposicion->bindParam(':galones', $galones);
        $updateDisposicion->bindParam(':horometro_inicio', $horometro_inicio);
        $updateDisposicion->bindParam(':horometro_fin', $horometro_fin);
        $updateDisposicion->bindParam(':observaciones', $observaciones);
        $updateDisposicion->bindParam(':id_registro_disposicion', $id_registro_disposicion);

        if ($foto_km_inicio_name) {
            $updateDisposicion->bindParam(':foto_km_inicio', $foto_km_inicio_name);
        }

        if ($foto_km_fin_name) {
            $updateDisposicion->bindParam(':foto_km_fin', $foto_km_fin_name);
        }

        $updateDisposicion->execute();

        // Manejar las subidas de archivos
        $ruta_imagenes = "../../employee/assets/images/";

        if ($foto_km_inicio_name) {
            move_uploaded_file($foto_km_inicio['tmp_name'], $ruta_imagenes . $foto_km_inicio_name);
        }

        if ($foto_km_fin_name) {
            move_uploaded_file($foto_km_fin['tmp_name'], $ruta_imagenes . $foto_km_fin_name);
        }

        // Confirmar la transacción
        $connection->commit();

        showErrorOrSuccessAndRedirect("success", "Actualización Exitosa", "Los datos del carro de barrido se han actualizado correctamente", "index.php");


    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $connection->rollBack();

        // Mostrar mensaje de error
        $connection->rollBack();
        showErrorOrSuccessAndRedirect("error", "Error de Actualización", "No se pudo actualizar los datos del carro de barrido: " . $e->getMessage(), "index.php");
    }
}
?>