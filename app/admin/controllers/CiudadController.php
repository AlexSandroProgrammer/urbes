<?php
//  REGISTRO DE ACTIVIDAD
if ((isset($_POST["MM_formRegisterCity"])) && ($_POST["MM_formRegisterCity"] == "formRegisterCity")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE ACTIVIDAD
    $ciudad = $_POST['ciudad'];

    // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$ciudad])) {
        showErrorFieldsEmpty("ciudades.php");
        exit();
    }
    // validamos que no se repitan los datos del nombre del actividad
    // CONSULTA SQL PARA VERIFICAR SI EL REGISTRO YA EXISTE EN LA BASE DE DATOS
    $estadoQueryFetch = $connection->prepare("SELECT * FROM ciudades WHERE ciudad = :ciudad");
    $estadoQueryFetch->bindParam(':ciudad', $ciudad);
    $estadoQueryFetch->execute();
    $queryFetch = $estadoQueryFetch->fetchAll();
    // CONDICIONALES DEPENDIENDO EL RESULTADO DE LA CONSULTA
    if ($queryFetch) {
        // Si ya existe una actividad con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Los datos ingresados ya estan registrados", "ciudades.php");
        exit();
    } else {
        // cargamos la fecha y hora actual del registro
        $fecha_registro = date('Y-m-d H:i:s');
        // Inserta los datos en la base de datos
        $registerCiudad = $connection->prepare("INSERT INTO ciudades(ciudad, fecha_registro) VALUES(:ciudad, :fecha_registro)");
        $registerCiudad->bindParam(':ciudad', $ciudad);
        $registerCiudad->bindParam(':fecha_registro', $fecha_registro);
        $registerCiudad->execute();
        if ($registerCiudad) {
            showErrorOrSuccessAndRedirect("success", "Registro Exitoso", "Los datos se han registrado correctamente", "ciudades.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de registro", "Error al momento de registrar los datos, por favor intentalo nuevamente", "ciudades.php");
            exit();
        }
    }
}

//  EDITAR ACTIVIDAD
if ((isset($_POST["MM_formUpdateCity"])) && ($_POST["MM_formUpdateCity"] == "formUpdateCity")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE ACTIVIDAD
    $ciudad = $_POST['ciudad'];
    $id_ciudad = $_POST['id_ciudad'];
    // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$ciudad, $id_ciudad])) {
        showErrorFieldsEmpty("ciudades.php");
        exit();
    }
    // validamos que no se repitan los datos del nombre del actividad
    // CONSULTA SQL PARA VERIFICAR SI EL REGISTRO YA EXISTE EN LA BASE DE DATOS
    $cityQueryUpdate = $connection->prepare("SELECT * FROM ciudades WHERE ciudad = :ciudad AND id_ciudad <> :id_ciudad");
    $cityQueryUpdate->bindParam(':ciudad', $ciudad);
    $cityQueryUpdate->bindParam(':id_ciudad', $id_ciudad);
    $cityQueryUpdate->execute();
    // Obtener todos los resultados en un array
    $queryCity = $cityQueryUpdate->fetchAll(PDO::FETCH_ASSOC);
    if ($queryCity) {
        // Si ya existe una actividad con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Coincidencia de datos", "La Ciudad ingresada pertenece a otro registro", "ciudades.php");
        exit();
    } else {
        $fecha_actualizacion = date('Y-m-d H:i:s');
        // Inserta los datos en la base de datos
        $updateCity = $connection->prepare("UPDATE ciudades SET ciudad = :ciudad, fecha_actualizacion = :fecha_actualizacion WHERE id_ciudad = :id_ciudad");
        $updateCity->bindParam(':ciudad', $ciudad);
        $updateCity->bindParam(':fecha_actualizacion', $fecha_actualizacion);
        $updateCity->bindParam(':id_ciudad', $id_ciudad);
        $updateCity->execute();
        if ($updateCity) {
            showErrorOrSuccessAndRedirect("success", "Actualizacion Exitosa", "Los datos se han actualizado correctamente", "ciudades.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de Actualizacion", "Error al momento de actualizar los datos, por favor intentalo nuevamente", "ciudades.php");
        }
    }
}