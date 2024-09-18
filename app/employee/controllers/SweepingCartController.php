<?php
//* Registro de datos de registro de actividades
if ((isset($_POST["MM_formRegisterSweepingCart"])) && ($_POST["MM_formRegisterSweepingCart"] == "formRegisterSweepingCart")) {
    // VARIABLES DE ASIGNACIÓN DE VALORES QUE SE ENVÍAN DESDE EL FORMULARIO DE REGISTRO DE VEHICULO COMPACTADOR
    $fecha_inicio = $_POST['fecha_inicio'];
    $documento = $_POST['documento'];
    $hora_inicio = $_POST['hora_inicio'];
    $ciudad = $_POST['ciudad'];
    $zonas = isset($_POST['zonas']) ? $_POST['zonas'] : [];

    // Validamos que no se haya recibido ningún dato vacío
    if (empty($fecha_inicio) || empty($documento) || empty($hora_inicio) || empty($ciudad) || empty($zonas)) {
        showErrorFieldsEmpty( "carro_barrido.php");
        exit();
    }

    // OBTENEMOS LA FECHA ACTUAL 
    $fecha_registro = date('Y-m-d H:i:s');
    $pendiente = 4;
    $actividad = 5;

    // Inserta los datos en la base de datos
    $register = $connection->prepare("INSERT INTO carro_barrido (fecha_inicio, hora_inicio,id_actividad, ciudad, documento, id_estado, fecha_registro) VALUES(:fecha_inicio, :hora_inicio,:id_actividad, :ciudad, :documento, :id_estado, :fecha_registro)");

    // Vincular los parámetros
    $register->bindParam(':fecha_inicio', $fecha_inicio);
    $register->bindParam(':hora_inicio', $hora_inicio);
    $register->bindParam(':id_actividad', $actividad);
    $register->bindParam(':ciudad', $ciudad);
    $register->bindParam(':documento', $documento);
    $register->bindParam(':id_estado', $pendiente);
    $register->bindParam(':fecha_registro', $fecha_registro);
    
    if ($register->execute()) {
        // Capturamos el ID del último registro insertado
        $idRegister = $connection->lastInsertId();
        
        // Insertar el arreglo de IDs en otra tabla relacionada
        $insertarDetail = $connection->prepare("INSERT INTO detalle_zonas(id_zona, id_registro) VALUES(:id_zona, :id_registro)");
        
        foreach ($zonas as $zonaId) {
            $insertarDetail->bindParam(':id_zona', $zonaId);
            $insertarDetail->bindParam(':id_registro', $idRegister);
            $insertarDetail->execute();
        }

        showErrorOrSuccessAndRedirect("success", "Formulario Registrado", "Se ha registrado la etapa inicial del formulario, debes terminar de rellenar la información restante en el panel de formularios pendientes", "index.php");
        exit();
    } else {
        showErrorOrSuccessAndRedirect("error", "Error de Registro", "Error al momento de registrar los datos, por favor inténtalo nuevamente.", "carro_barrido.php");
        exit();
    }
}
?>


<?php
// * Método actualizar
if ((isset($_POST["MM_formFinishSweepingCart"])) && ($_POST["MM_formFinishSweepingCart"] == "formFinishSweepingCart")) {
    
    // Variables de asignación de valores que se envían desde el formulario
    $fecha_fin     = $_POST['fecha_fin'];
    $hora_fin      = $_POST['hora_fin'];
    $peso          = $_POST['peso'];
    $observacion   = $_POST['observacion'];
    $id_registro   = $_POST['id_registro'];

    // Validamos que no hayamos recibido ningún dato vacío
    if (isEmpty([
        $fecha_fin,
        $hora_fin, 
        $peso, 
        $observacion,
        $id_registro 
    ])) {
        showErrorFieldsEmpty("terminar_carro_barrido.php");
        exit();
    }

    try {
        // Fecha actual
        $fecha_actualizacion = date('Y-m-d H:i:s');
        $id_estado = 5;
       
        // Inserción de los datos en la base de datos
        $finishRegister = $connection->prepare("
            UPDATE carro_barrido
            SET fecha_fin = :fecha_fin, 
                hora_fin = :hora_fin, 
                peso = :peso, 
                observaciones = :observacion, 
                fecha_actualizacion = :fecha_actualizacion,
                id_estado = :id_estado
            WHERE id_registro_barrido = :id_registro
        ");

        // Vincular los parámetros
        $finishRegister->bindParam(':fecha_fin', $fecha_fin);
        $finishRegister->bindParam(':hora_fin', $hora_fin);
        $finishRegister->bindParam(':peso', $peso);
        $finishRegister->bindParam(':observacion', $observacion);
        $finishRegister->bindParam(':fecha_actualizacion', $fecha_actualizacion);
        $finishRegister->bindParam(':id_estado', $id_estado);
        $finishRegister->bindParam(':id_registro', $id_registro);  // Añadir el parámetro que faltaba
        $finishRegister->execute();

        if ($finishRegister) {
            showErrorOrSuccessAndRedirect("success", "¡Perfecto!", "Los datos se han actualizado correctamente", "./index.php");
            exit();
        }
    } catch (\Throwable $th) {
      
        showErrorOrSuccessAndRedirect("error", "Error de actualización", "Error al momento de actualizar los datos.", "./index.php");
        exit();
    }
}
?>