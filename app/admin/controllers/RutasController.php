<?php
//  REGISTRO DE ACTIVIDAD
if ((isset($_POST["MM_formRegisterRute"])) && ($_POST["MM_formRegisterRute"] == "formRegisterRute")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE ACTIVIDAD
    $ruta = $_POST['ruta'];
    $ciudad = $_POST['ciudad'];

    // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$ruta])) {
        showErrorFieldsEmpty("rutas.php");
        exit();
    }
    // validamos que no se repitan los datos del nombre del actividad
    // CONSULTA SQL PARA VERIFICAR SI EL REGISTRO YA EXISTE EN LA BASE DE DATOS
    $estadoQueryFetch = $connection->prepare("SELECT * FROM rutasr  WHERE ruta= :ruta AND id_ciudad = :id_ciudad");
    $estadoQueryFetch->bindParam(':ruta', $ruta);
    $estadoQueryFetch->bindParam(':id_ciudad', $ciudad);
    $estadoQueryFetch->execute();
    $queryFetch = $estadoQueryFetch->fetchAll();
    // CONDICIONALES DEPENDIENDO EL RESULTADO DE LA CONSULTA
    if ($queryFetch) {
        // Si ya existe una actividad con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Los datos ingresados ya estan registrados", "rutas.php");
        exit();
    } else {
        // Inserta los datos en la base de datos
        $registerZona = $connection->prepare("INSERT INTO rutasr(ruta, id_ciudad) VALUES(:ruta, :id_ciudad)");
        $registerZona->bindParam(':ruta', $ruta);
        $registerZona->bindParam(':id_ciudad', $ciudad);
        $registerZona->execute();
        if ($registerZona) {
            showErrorOrSuccessAndRedirect("success", "Registro Exitoso", "Los datos se han registrado correctamente", "rutas.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de registro", "Error al momento de registrar los datos, por favor intentalo nuevamente", "rutas.php");
            exit();
        }
    }
}
// EDITAR ACTIVIDAD
if ((isset($_POST["MM_formUpdateRute"])) && ($_POST["MM_formUpdateRute"] == "formUpdateRute")) {
    $ruta = $_POST['ruta'];
    $id_ruta = $_POST['id_ruta'];
    $id_ciudad = $_POST['ciudad'];
    if (empty($ruta) || empty($id_ruta)) {
        showErrorFieldsEmpty("rutas.php");
        exit();
    }
    $zoneQueryUpdate = $connection->prepare("SELECT * FROM rutasr WHERE ruta = :ruta AND id_ruta <> :id_ruta");
    $zoneQueryUpdate->bindParam(':ruta', $ruta);
    $zoneQueryUpdate->bindParam(':id_ruta', $id_ruta);
    $zoneQueryUpdate->execute();
    $queryZone = $zoneQueryUpdate->fetchAll(PDO::FETCH_ASSOC);
    if ($queryZone) {
        showErrorOrSuccessAndRedirect("error", "Coincidencia de datos", "La ruta ya existe para otra ciudad", "rutas.php");
        exit();
    } else {
        $updateZone = $connection->prepare("UPDATE rutasr SET ruta = :ruta, id_ciudad = :id_ciudad WHERE id_ruta = :id_ruta");
        $updateZone->bindParam(':ruta', $ruta);
        $updateZone->bindParam(':id_ruta', $id_ruta);
        $updateZone->bindParam(':id_ciudad', $id_ciudad);
        $updateZone->execute();
        if ($updateZone) {
            showErrorOrSuccessAndRedirect("success", "Actualización Exitosa", "Los datos se han actualizado correctamente", "rutas.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de Actualización", "Error al momento de actualizar los datos, por favor intentalo nuevamente", "rutas.php");
        }
    }
}