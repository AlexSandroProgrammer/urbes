<?php
//  REGISTRO DE ACTIVIDAD
if ((isset($_POST["MM_formRegisterLabors"])) && ($_POST["MM_formRegisterLabors"] == "formRegisterLabors")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE ACTIVIDAD
    $labor = $_POST['labor'];
    $actividad = $_POST["actividad"];

    // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$labor, $actividad])) {
        showErrorFieldsEmpty("labores.php");
        exit();
    }
    // validamos que no se repitan los datos del nombre del actividad
    // CONSULTA SQL PARA VERIFICAR SI EL REGISTRO YA EXISTE EN LA BASE DE DATOS
    $estadoQueryFetch = $connection->prepare("SELECT * FROM labores WHERE labor = :labor");
    $estadoQueryFetch->bindParam(':labor', $labor);
    $estadoQueryFetch->execute();
    $queryFetch = $estadoQueryFetch->fetchAll();
    // CONDICIONALES DEPENDIENDO EL RESULTADO DE LA CONSULTA
    if ($queryFetch) {
        // Si ya existe una actividad con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Los datos ingresados ya estan registrados", "labores.php");
        exit();
    } else {
        // cargamos la fecha y hora actual del registro
        $fecha_registro = date('Y-m-d H:i:s');
        // Inserta los datos en la base de datos
        $registerLabors = $connection->prepare("INSERT INTO labores(labor, fecha_registro,id_actividad) VALUES(:labor, :fecha_registro,:id_actividad)");
        $registerLabors->bindParam(':labor', $labor);
        $registerLabors->bindParam(':fecha_registro', $fecha_registro);
        $registerLabors->bindParam(':id_actividad', $actividad);
        $registerLabors->execute();
        if ($registerLabors) {
            showErrorOrSuccessAndRedirect("success", "Registro Exitoso", "Los datos se han registrado correctamente", "labores.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de registro", "Error al momento de registrar los datos, por favor intentalo nuevamente", "labores.php");
            exit();
        }
    }
}

//  EDITAR LABOR
if ((isset($_POST["MM_formUpdateLabors"])) && ($_POST["MM_formUpdateLabors"] == "formUpdateLabors")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE ACTIVIDAD
    $labor = $_POST['labor'];
    $id_labor = $_POST['id_labor'];
    $actividad = $_POST['actividad'];
    // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$labor, $id_labor])) {
        showErrorFieldsEmpty("labores.php");
        exit();
    }
    // validamos que no se repitan los datos del nombre del actividad
    // CONSULTA SQL PARA VERIFICAR SI EL REGISTRO YA EXISTE EN LA BASE DE DATOS
    $LaborsQueryUpdate = $connection->prepare("SELECT * FROM labores WHERE labor = :labor AND id_labor <> :id_labor");
    $LaborsQueryUpdate->bindParam(':labor', $labor);
    $LaborsQueryUpdate->bindParam(':id_labor', $id_labor);
    $LaborsQueryUpdate->execute();
    // Obtener todos los resultados en un array
    $queryLabors = $LaborsQueryUpdate->fetchAll(PDO::FETCH_ASSOC);
    if ($queryLabors) {
        // Si ya existe una actividad con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Coincidencia de datos", "La Ciudad ingresada pertenece a otro registro", "labores.php");
        exit();
    } else {
        $fecha_actualizacion = date('Y-m-d H:i:s');
        // Inserta los datos en la base de datos
        $updateLabors = $connection->prepare("UPDATE labores SET labor = :labor, fecha_actualizacion = :fecha_actualizacion, id_actividad = :id_actividad WHERE id_labor = :id_labor");
        $updateLabors->bindParam(':labor', $labor);
        $updateLabors->bindParam(':fecha_actualizacion', $fecha_actualizacion);
        $updateLabors->bindParam(':id_actividad', $actividad);
        $updateLabors->bindParam(':id_labor', $id_labor);
        $updateLabors->execute();
        if ($updateLabors) {
            showErrorOrSuccessAndRedirect("success", "Actualizacion Exitosa", "Los datos se han actualizado correctamente", "labores.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de Actualizacion", "Error al momento de actualizar los datos, por favor intentalo nuevamente", "labores.php");
        }
    }
}