<?php
//  REGISTRO DE ACTIVIDAD
if ((isset($_POST["MM_formRegisterActivity"])) && ($_POST["MM_formRegisterActivity"] == "formRegisterActivity")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE ACTIVIDAD
    $actividad = $_POST['actividad'];

    // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$actividad])) {
        showErrorFieldsEmpty("actividades.php");
        exit();
    }
    // validamos que no se repitan los datos del nombre del actividad
    // CONSULTA SQL PARA VERIFICAR SI EL REGISTRO YA EXISTE EN LA BASE DE DATOS
    $estadoQueryFetch = $connection->prepare("SELECT * FROM actividades WHERE actividad = :actividad");
    $estadoQueryFetch->bindParam(':actividad', $actividad);
    $estadoQueryFetch->execute();
    $queryFetch = $estadoQueryFetch->fetchAll();
    // CONDICIONALES DEPENDIENDO EL RESULTADO DE LA CONSULTA
    if ($queryFetch) {
        // Si ya existe una actividad con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Los datos ingresados ya estan registrados", "actividades.php");
        exit();
    } else {
        // cargamos la fecha y hora actual del registro
        $fecha_registro = date('Y-m-d H:i:s');
        // Inserta los datos en la base de datos
        $registerActivity = $connection->prepare("INSERT INTO actividades(actividad, fecha_registro) VALUES(:actividad, :fecha_registro)");
        $registerActivity->bindParam(':actividad', $actividad);
        $registerActivity->bindParam(':fecha_registro', $fecha_registro);
        $registerActivity->execute();
        if ($registerActivity) {
            showErrorOrSuccessAndRedirect("success", "Registro Exitoso", "Los datos se han registrado correctamente", "actividades.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de registro", "Error al momento de registrar los datos, por favor intentalo nuevamente", "actividades.php");
            exit();
        }
    }
}

//  EDITAR ACTIVIDAD
if ((isset($_POST["MM_formUpdateActivity"])) && ($_POST["MM_formUpdateActivity"] == "formUpdateActivity")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE ACTIVIDAD
    $actividad = $_POST['actividad'];
    $id_actividad = $_POST['id_actividad'];
    // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$actividad, $id_actividad])) {
        showErrorFieldsEmpty("actividades.php");
        exit();
    }
    // validamos que no se repitan los datos del nombre del actividad
    // CONSULTA SQL PARA VERIFICAR SI EL REGISTRO YA EXISTE EN LA BASE DE DATOS
    $actividityQueryUpdate = $connection->prepare("SELECT * FROM actividades WHERE actividad = :actividad AND id_actividad <> :id_actividad");
    $actividityQueryUpdate->bindParam(':actividad', $actividad);
    $actividityQueryUpdate->bindParam(':id_actividad', $id_actividad);
    $actividityQueryUpdate->execute();
    // Obtener todos los resultados en un array
    $queryActivity = $actividityQueryUpdate->fetchAll(PDO::FETCH_ASSOC);
    if ($queryActivity) {
        // Si ya existe una actividad con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Coincidencia de datos", "La actividad ingresada pertenece a otro registro", "actividades.php");
        exit();
    } else {
        $fecha_actualizacion = date('Y-m-d H:i:s');
        // Inserta los datos en la base de datos
        $updateActivity = $connection->prepare("UPDATE actividades SET actividad = :actividad, fecha_actualizacion = :fecha_actualizacion WHERE id_actividad = :id_actividad");
        $updateActivity->bindParam(':actividad', $actividad);
        $updateActivity->bindParam(':fecha_actualizacion', $fecha_actualizacion);
        $updateActivity->bindParam(':id_actividad', $id_actividad);
        $updateActivity->execute();
        if ($updateActivity) {
            showErrorOrSuccessAndRedirect("success", "Actualizacion Exitosa", "Los datos se han actualizado correctamente", "actividades.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de Actualizacion", "Error al momento de actualizar los datos, por favor intentalo nuevamente", "actividades.php");
        }
    }
}