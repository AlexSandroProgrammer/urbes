<?php
//  REGISTRO DE ACTIVIDAD
if ((isset($_POST["MM_formRegisterZone"])) && ($_POST["MM_formRegisterZone"] == "formRegisterZone")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE ACTIVIDAD
    $zona = $_POST['zona'];
    $ciudad = $_POST['ciudad'];

    // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$zona])) {
        showErrorFieldsEmpty("zonas.php");
        exit();
    }
    // validamos que no se repitan los datos del nombre del actividad
    // CONSULTA SQL PARA VERIFICAR SI EL REGISTRO YA EXISTE EN LA BASE DE DATOS
    $estadoQueryFetch = $connection->prepare("SELECT * FROM zonas  WHERE zona= :zona AND id_ciudad = :id_ciudad");
    $estadoQueryFetch->bindParam(':zona', $zona);
    $estadoQueryFetch->bindParam(':id_ciudad', $ciudad);
    $estadoQueryFetch->execute();
    $queryFetch = $estadoQueryFetch->fetchAll();
    // CONDICIONALES DEPENDIENDO EL RESULTADO DE LA CONSULTA
    if ($queryFetch) {
        // Si ya existe una actividad con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Los datos ingresados ya estan registrados", "zonas.php");
        exit();
    } else {
        // cargamos la fecha y hora actual del registro
        $fecha_registro = date('Y-m-d H:i:s');
        // Inserta los datos en la base de datos
        $registerZona = $connection->prepare("INSERT INTO zonas(zona, fecha_registro,id_ciudad) VALUES(:zona, :fecha_registro,:id_ciudad)");
        $registerZona->bindParam(':zona', $zona);
        $registerZona->bindParam(':fecha_registro', $fecha_registro);
        $registerZona->bindParam(':id_ciudad', $ciudad);
        $registerZona->execute();
        if ($registerZona) {
            showErrorOrSuccessAndRedirect("success", "Registro Exitoso", "Los datos se han registrado correctamente", "zonas.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de registro", "Error al momento de registrar los datos, por favor intentalo nuevamente", "zonas.php");
            exit();
        }
    }
}

//  EDITAR ACTIVIDAD
if ((isset($_POST["MM_formUpdateZone"])) && ($_POST["MM_formUpdateZone"] == "formUpdateZone")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE ACTIVIDAD
    $zona = $_POST['zona'];
    $id_zona = $_POST['id_zona'];
    $id_ciudad = $_POST['ciudad'];
    // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$zona, $id_zona])) {
        showErrorFieldsEmpty("zonas.php");
        exit();
    }
    // validamos que no se repitan los datos del nombre del actividad
    // CONSULTA SQL PARA VERIFICAR SI EL REGISTRO YA EXISTE EN LA BASE DE DATOS
    $zoneQueryUpdate = $connection->prepare("SELECT * FROM zonas INNER JOIN ciudades ON zonas.id_ciudad = ciudades.id_ciudad WHERE zona = :zona AND id_zona <> :id_zona ");
    $zoneQueryUpdate->bindParam(':zona', $zona);
    $zoneQueryUpdate->bindParam(':id_zona', $id_zona);
    $zoneQueryUpdate->execute();
    // Obtener todos los resultados en un array
    $queryZone = $zoneQueryUpdate->fetchAll(PDO::FETCH_ASSOC);
    if ($queryZone) {
        // Si ya existe una actividad con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Coincidencia de datos", "La Ciudad ingresada pertenece a otro registro", "zona.php");
        exit();
    } else {
        $fecha_actualizacion = date('Y-m-d H:i:s');
        // Inserta los datos en la base de datos
        $updateZone = $connection->prepare("UPDATE zonas SET zona = :zona, fecha_actualizacion = :fecha_actualizacion WHERE id_zona = :id_zona");
        $updateZone->bindParam(':zona', $zona);
        $updateZone->bindParam(':fecha_actualizacion', $fecha_actualizacion);
        $updateZone->bindParam(':id_zona', $id_ciudad);
        $updateZone->execute();
        if ($updateZone) {
            showErrorOrSuccessAndRedirect("success", "Actualizacion Exitosa", "Los datos se han actualizado correctamente", "zonas.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de Actualizacion", "Error al momento de actualizar los datos, por favor intentalo nuevamente", "zonas.php");
        }
    }
}