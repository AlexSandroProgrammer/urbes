<?php
require_once("../components/sidebar.php");

if ((isset($_POST["MM_formUpdateRecoleccion"])) && ($_POST["MM_formUpdateRecoleccion"] == "formUpdateRecoleccion")) {
    // Obtener los datos del formulario
    $id_registro_veh_compactador = $_POST['id_registro_veh_compactador'];
    $id_estado = $_POST['id_estado'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $id_vehiculo = $_POST['id_vehiculo'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_finalizacion = $_POST['hora_finalizacion'];
    $km_inicio = $_POST['km_inicio'];
    $km_fin = $_POST['km_fin'];
    $horometro_inicio = $_POST['horometro_inicio'];
    $horometro_fin = $_POST['horometro_fin'];
    $observaciones = $_POST['observaciones'];
    // Manejar las imágenes del kilometraje inicial y final
    if (isset($_POST['foto_kilometraje_inicial_old'])) {
        $foto_kilometraje_inicial_old = $_POST['foto_kilometraje_inicial_old'];
    } else {
        $foto_kilometraje_inicial_old = null;
    }

    if (isset($_POST['foto_kilometraje_final_old'])) {
        $foto_kilometraje_final_old = $_POST['foto_kilometraje_final_old'];
    } else {
        $foto_kilometraje_final_old = null;
    }

    // Asignar la imagen del kilometraje inicial
    if (!empty($_FILES['foto_kilometraje_inicial']['name'])) {
        $foto_kilometraje_inicial = $_FILES['foto_kilometraje_inicial']['name'];
        $foto_km_inicio_name = uniqid() . '.' . pathinfo($foto_kilometraje_inicial, PATHINFO_EXTENSION);
    } else {
        $foto_km_inicio_name = $foto_kilometraje_inicial_old;
    }

    if (!empty($_FILES['foto_kilometraje_final']['name'])) {
        $foto_kilometraje_final = $_FILES['foto_kilometraje_final']['name'];
        $foto_km_fin_name = uniqid() . '.' . pathinfo($foto_kilometraje_final, PATHINFO_EXTENSION);
    } else {
        $foto_km_fin_name = $foto_kilometraje_final_old;
    }
    // Validar que todos los campos requeridos estén presentes
    if (isEmpty([$id_estado, $fecha_inicio, $fecha_fin, $id_vehiculo, $hora_inicio, $hora_finalizacion, $km_inicio, $km_fin, $horometro_inicio, $horometro_fin])) {
        showErrorOrSuccessAndRedirect("error", "Error en Campos", "Todos los campos requeridos deben estar presentes", "");
        exit();
    }

    // Validar que la fecha de inicio sea menor que la fecha de fin
    if ($fecha_inicio > $fecha_fin) {
        showErrorOrSuccessAndRedirect("error", "Error en Fecha", "La fecha de inicio debe ser menor que la fecha de fin", "");
        exit();
    }

    // Validar kilometraje final mayor que el inicial
    if ($km_fin <= $km_inicio) {
        showErrorOrSuccessAndRedirect("error", "Error en Kilometraje", "El kilometraje final debe ser mayor que el inicial", "");
        exit();
    }

    // Validar horómetro final mayor que el inicial
    if ($horometro_fin <= $horometro_inicio) {
        showErrorOrSuccessAndRedirect("error", "Error en Horómetro", "El horómetro final debe ser mayor que el inicial", "");
        exit();
    }


    try {

        // Consulta para actualizar los datos del vehículo
        $sql = "UPDATE vehiculo_compactador SET 
            id_estado = :id_estado,
            id_vehiculo = :id_vehiculo,
            fecha_inicio = :fecha_inicio,
            hora_inicio = :hora_inicio,
            fecha_fin = :fecha_fin,
            hora_finalizacion = :hora_finalizacion,
            km_inicio = :km_inicio,
            km_fin = :km_fin,
            horometro_inicio = :horometro_inicio,
            horometro_fin = :horometro_fin,
            observaciones = :observaciones,
            foto_kilometraje_inicial = :foto_kilometraje_inicial,
            foto_kilometraje_final = :foto_kilometraje_final
            WHERE id_registro_veh_compactador = :id_registro_veh_compactador";

        $updateDisposicion = $connection->prepare($sql);
        // Vincular parámetros
        $updateDisposicion->bindParam(':id_estado', $id_estado);
        $updateDisposicion->bindParam(':id_vehiculo', $id_vehiculo);
        $updateDisposicion->bindParam(':fecha_inicio', $fecha_inicio);
        $updateDisposicion->bindParam(':hora_inicio', $hora_inicio);
        $updateDisposicion->bindParam(':fecha_fin', $fecha_fin);
        $updateDisposicion->bindParam(':hora_finalizacion', $hora_finalizacion);
        $updateDisposicion->bindParam(':km_inicio', $km_inicio);
        $updateDisposicion->bindParam(':km_fin', $km_fin);
        $updateDisposicion->bindParam(':horometro_inicio', $horometro_inicio);
        $updateDisposicion->bindParam(':horometro_fin', $horometro_fin);
        $updateDisposicion->bindParam(':observaciones', $observaciones);
        $updateDisposicion->bindParam(':id_registro_veh_compactador', $id_registro_veh_compactador);
        $updateDisposicion->bindParam(':foto_kilometraje_inicial', $foto_km_inicio_name);
        $updateDisposicion->bindParam(':foto_kilometraje_final', $foto_km_fin_name);
        $updateDisposicion->execute();
        if ($updateDisposicion) {
            if (!empty($_FILES['foto_kilometraje_inicial']['name'])) {
                $ruta_imagenes = "../../employee/assets/images/";
                move_uploaded_file($_FILES['foto_kilometraje_inicial']['tmp_name'], $ruta_imagenes . $foto_km_inicio_name);
                $foto_kilometraje_inicial = $foto_km_inicio_name;
            }
            if (!empty($_FILES['foto_kilometraje_final']['name'])) {
                $ruta_imagenes = "../../employee/assets/images/";
                move_uploaded_file($_FILES['foto_kilometraje_final']['tmp_name'], $ruta_imagenes . $foto_km_fin_name);
                $foto_kilometraje_final = $foto_km_fin_name;
            }
            showErrorOrSuccessAndRedirect("success", "Actualización Exitosa", "Los datos del vehículo compactador se han actualizado correctamente", "index.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de Actualización", "No se pudo actualizar los datos del vehículo compactador", "index.php");
            exit();
        }
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        showErrorOrSuccessAndRedirect("error", "Error de Actualización", "No se pudo actualizar los datos: ", "index.php");
        exit();
    }
}