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