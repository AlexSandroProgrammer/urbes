<?php
//* Registro de datos de registro de actividades
if ((isset($_POST["MM_formRegisterTreePruning"])) && ($_POST["MM_formRegisterTreePruning"] == "formRegisterTreePruning")) {
    // VARIABLES DE ASIGNACIÓN DE VALORES QUE SE ENVÍAN DESDE EL FORMULARIO DE REGISTRO DE VEHICULO COMPACTADOR
    $fecha_inicio = $_POST['fecha_inicio'];
    $documento = $_POST['documento'];
    $hora_inicio = $_POST['hora_inicio'];
    $ciudad = $_POST['id_ciudad'];
    
    
    

    // Validamos que no se haya recibido ningún dato vacío
    if (empty($fecha_inicio) ||  empty($hora_inicio) || empty($documento) || empty($ciudad) ) {
        showErrorFieldsEmpty( "poda.php");
        exit();
    }

    // OBTENEMOS LA FECHA ACTUAL 
    $fecha_registro = date('Y-m-d H:i:s');
    $pendiente = 4;
    $labor = 7;

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
        showErrorOrSuccessAndRedirect("error", "Error de Registro", "Error al momento de registrar los datos, por favor inténtalo nuevamente.", "poda.php");
        exit();
    }
}
?>


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



<?php
//* Registro de datos de registro de actividades
if ((isset($_POST["MM_formRegisterWashing"])) && ($_POST["MM_formRegisterWashing"] == "formRegisterWashing")) {
    // VARIABLES DE ASIGNACIÓN DE VALORES QUE SE ENVÍAN DESDE EL FORMULARIO DE REGISTRO DE VEHICULO COMPACTADOR
    $fecha_inicio = $_POST['fecha_inicio'];
    $documento = $_POST['documento'];
    $hora_inicio = $_POST['hora_inicio'];
    $ciudad = $_POST['id_ciudad'];
    
    

    // Validamos que no se haya recibido ningún dato vacío
    if (empty($fecha_inicio) ||  empty($hora_inicio) || empty($documento) || empty($ciudad) ) {
        showErrorFieldsEmpty( "lavado.php");
        exit();
    }

    // OBTENEMOS LA FECHA ACTUAL 
    $fecha_registro = date('Y-m-d H:i:s');
    $pendiente = 4;
    $labor = 8;

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
        showErrorOrSuccessAndRedirect("error", "Error de Registro", "Error al momento de registrar los datos, por favor inténtalo nuevamente.", "lavado.php");
        exit();
    }
}
?>



<?php
// * Método actualizar
if ((isset($_POST["MM_formFinishPublicAreas"])) && ($_POST["MM_formFinishPublicAreas"] == "formFinishPublicAreas")) {
    
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
        showErrorFieldsEmpty("terminar_form_areas_public.php");
        exit();
    }

    try {
        // Fecha actual
        $fecha_actualizacion = date('Y-m-d H:i:s');
        $id_estado = 5;
       
        // Inserción de los datos en la base de datos
        $finishRegister = $connection->prepare("
            UPDATE areas_publicas 
            SET fecha_finalizacion = :fecha_fin, 
                hora_finalizacion = :hora_fin, 
                peso = :peso, 
                observaciones = :observacion, 
                fecha_actualizacion = :fecha_actualizacion,
                id_estado = :id_estado
            WHERE id_registro = :id_registro
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
