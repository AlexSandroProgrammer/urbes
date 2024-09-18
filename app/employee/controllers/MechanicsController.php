<?php
//* Registro de datos de registro de actividades
if ((isset($_POST["MM_formRegisterMechanics"])) && ($_POST["MM_formRegisterMechanics"] == "formRegisterMechanics")) {
    // VARIABLES DE ASIGNACIÓN DE VALORES QUE SE ENVÍAN DESDE EL FORMULARIO DE REGISTRO DE VEHICULO COMPACTADOR
    $fecha_inicio = $_POST['fecha_inicio'];
    $documento = $_POST['documento'];
    $hora_inicio = $_POST['hora_inicio'];
    $id_vehiculo = $_POST['vehiculo'];

    // Validamos que no se haya recibido ningún dato vacío
    if (empty($fecha_inicio) ||  empty($hora_inicio) || empty($documento) || empty($id_vehiculo)) {
        showErrorFieldsEmpty( "mecanica.php");
        exit();
    }

    // OBTENEMOS LA FECHA ACTUAL 
    $fecha_registro = date('Y-m-d H:i:s');
    $pendiente = 4;
    $actvidad = 2;

    // Inserta los datos en la base de datos
    $register = $connection->prepare("INSERT INTO mecanica (fecha_inicio, hora_inicio,id_actividad, documento, id_estado, fecha_registro,id_vehiculo) VALUES(:fecha_inicio, :hora_inicio,:id_actividad, :documento, :id_estado, :fecha_registro,:id_vehiculo)");

    // Vincular los parámetros
    $register->bindParam(':fecha_inicio', $fecha_inicio);
    $register->bindParam(':hora_inicio', $hora_inicio);
    $register->bindParam(':id_actividad', $actvidad);
    $register->bindParam(':documento', $documento);
    $register->bindParam(':id_estado', $pendiente);
    $register->bindParam(':fecha_registro', $fecha_registro);
    $register->bindParam(':id_vehiculo', $id_vehiculo);
    
    if ($register->execute()) {
        showErrorOrSuccessAndRedirect("success", "Formulario Registrado", "Se ha registrado la etapa inicial del formulario, debes terminar de rellenar la información restante en el panel de formularios pendientes", "index.php");
        exit();
    } else {
        showErrorOrSuccessAndRedirect("error", "Error de Registro", "Error al momento de registrar los datos, por favor inténtalo nuevamente.", "mecanica.php");
        exit();
    }
}
?>






<?php
// * Método actualizar
if ((isset($_POST["MM_formFinishMechanics"])) && ($_POST["MM_formFinishMechanics"] == "formFinishMechanics")) {
    
    // Variables de asignación de valores que se envían desde el formulario
    $fecha_fin     = $_POST['fecha_fin'];
    $hora_fin      = $_POST['hora_fin'];
    $observacion   = $_POST['observacion'];
    $mantenimiento = $_POST['mantenimiento'];
    $id_registro   = $_POST['id_registro'];

    // Validamos que no hayamos recibido ningún dato vacío
    if (isEmpty([
        $fecha_fin,
        $hora_fin, 
        $observacion,
        $mantenimiento,
        $id_registro 
    ])) {
        showErrorFieldsEmpty("terminar_mecanica.php");
        exit();
    }

    try {
        // Fecha actual
        $fecha_actualizacion = date('Y-m-d H:i:s');
        $id_estado = 5;
       
        // Inserción de los datos en la base de datos
        $finishRegister = $connection->prepare("
            UPDATE mecanica
            SET fecha_fin = :fecha_fin, 
                hora_finalizacion = :hora_fin, 
                observaciones = :observacion, 
                labor_mantenimiento = :mantenimiento,
                fecha_actualizacion = :fecha_actualizacion,
                id_estado = :id_estado
            WHERE id_registro = :id_registro
        ");

        // Vincular los parámetros
        $finishRegister->bindParam(':fecha_fin', $fecha_fin);
        $finishRegister->bindParam(':hora_fin', $hora_fin);
        $finishRegister->bindParam(':observacion', $observacion);
        $finishRegister->bindParam(':mantenimiento', $mantenimiento);
        $finishRegister->bindParam(':fecha_actualizacion', $fecha_actualizacion);
        $finishRegister->bindParam(':id_estado', $id_estado);
        $finishRegister->bindParam(':id_registro', $id_registro);  // Añadir el parámetro que faltaba
        $finishRegister->execute();

        if ($finishRegister) {
            showErrorOrSuccessAndRedirect("success", "¡Perfecto!", "Los datos se han actualizado correctamente", "./index.php");
            exit();
        }
    } catch (\Throwable $th) {
        $errorMessage = $th->getMessage();
        echo $errorMessage;
        exit();
    }
}
?>

