<?php
//* Registro de datos de registro de actividades
if ((isset($_POST["MM_formRegisterGrassPruning"])) && ($_POST["MM_formRegisterGrassPruning"] == "formRegisterGrassPruning")) {
    // VARIABLES DE ASIGNACIÓN DE VALORES QUE SE ENVÍAN DESDE EL FORMULARIO DE REGISTRO DE VEHICULO COMPACTADOR
    $fecha_inicio = $_POST['fecha_inicio'];
    $documento = $_POST['documento'];
    $hora_inicio = $_POST['hora_inicio'];
    $ciudad = $_POST['id_ciudad'];
    
    

    // Validamos que no se haya recibido ningún dato vacío
    if (empty($fecha_inicio) ||  empty($hora_inicio) || empty($documento) || empty($ciudad) ) {
        showErrorFieldsEmpty( "cesped.php");
        exit();
    }

    // OBTENEMOS LA FECHA ACTUAL 
    $fecha_registro = date('Y-m-d H:i:s');
    $pendiente = 4;
    $labor = 6;

    // Inserta los datos en la base de datos
    $register = $connection->prepare("INSERT INTO areas_publicas(fecha_inicio, hora_inicio,id_labor, documento, id_estado, fecha_registro,id_ciudad) VALUES(:fecha_inicio, :hora_inicio,:id_labor, :documento, :id_estado, :fecha_registro,:id_ciudad)");

    // Vincular los parámetros
    $register->bindParam(':fecha_inicio', $fecha_inicio);
    $register->bindParam(':hora_inicio', $hora_inicio);
    $register->bindParam(':id_labor', $labor);
    $register->bindParam(':documento', $documento);
    $register->bindParam(':id_estado', $pendiente);
    $register->bindParam(':fecha_registro', $fecha_registro);
    $register->bindParam(':id_ciudad', $ciudad);
    
    if ($register->execute()) {
        showErrorOrSuccessAndRedirect("success", "Formulario Registrado", "Se ha registrado la etapa inicial del formulario, debes terminar de rellenar la información restante en el panel de formularios pendientes", "index.php");
        exit();
    } else {
        showErrorOrSuccessAndRedirect("error", "Error de Registro", "Error al momento de registrar los datos, por favor inténtalo nuevamente.", "cesped.php");
        exit();
    }
}
?>


