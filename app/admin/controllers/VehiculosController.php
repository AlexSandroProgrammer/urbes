<?php
//  REGISTRO DE ACTIVIDAD
if ((isset($_POST["MM_formRegisterCar"])) && ($_POST["MM_formRegisterCar"] == "formRegisterCar")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE ACTIVIDAD
    $placa = $_POST['placa'];
    $vehiculo = $_POST['vehiculo'];

    // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$placa, $vehiculo] )) {
        showErrorFieldsEmpty("Vehiculos.php");
        exit();
    }
    // validamos que no se repitan los datos del nombre del actividad
    // CONSULTA SQL PARA VERIFICAR SI EL REGISTRO YA EXISTE EN LA BASE DE DATOS
    $estadoQueryFetch = $connection->prepare("SELECT * FROM vehiculos WHERE placa = :placa");
    $estadoQueryFetch->bindParam(':placa', $placa);
    $estadoQueryFetch->execute();
    $queryFetch = $estadoQueryFetch->fetchAll();
    // CONDICIONALES DEPENDIENDO EL RESULTADO DE LA CONSULTA
    if ($queryFetch) {
        // Si ya existe una actividad con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Los datos ingresados ya estan registrados", "vehiculos.php");
        exit();
    } else {
        // cargamos la fecha y hora actual del registro
        $fecha_registro = date('Y-m-d H:i:s');
        // Inserta los datos en la base de datos
        $registerCar = $connection->prepare("INSERT INTO vehiculos(placa, vehiculo, fecha_registro) VALUES(:placa,:vehiculo, :fecha_registro)");
        $registerCar->bindParam(':placa', $placa);
        $registerCar->bindParam(':vehiculo', $vehiculo);
        $registerCar->bindParam(':fecha_registro', $fecha_registro);
        $registerCar->execute();
        if ($registerCar) {
            showErrorOrSuccessAndRedirect("success", "Registro Exitoso", "Los datos se han registrado correctamente", "vehiculos.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de registro", "Error al momento de registrar los datos, por favor intentalo nuevamente", "vehiculos.php");
            exit();
        }
    }
}

//  EDITAR ACTIVIDAD

if (isset($_POST["MM_formUpdateCar"]) && $_POST["MM_formUpdateCar"] === "formUpdateCar") {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE ACTIVIDAD
    $vehiculo = $_POST['vehiculo'];
    $placa = $_POST['placa'];
    $placaUpdate = $_POST['placaUpdate'];

    // Validamos que no haya datos vacíos
    if (empty($vehiculo) || empty($placa) || empty($placaUpdate)) {
        showErrorFieldsEmpty("vehiculos.php");
        exit();
    }

    // Verificamos si la nueva placa ya existe en la base de datos
    $carQueryCheckPlaca = $connection->prepare("SELECT * FROM vehiculos WHERE placa = :placaUpdate AND placa <> :placa");
    $carQueryCheckPlaca->bindParam(':placaUpdate', $placaUpdate);
    $carQueryCheckPlaca->bindParam(':placa', $placa);
    $carQueryCheckPlaca->execute();
    $existingPlaca = $carQueryCheckPlaca->fetch(PDO::FETCH_ASSOC);

    if ($existingPlaca) {
        // Si la nueva placa ya existe, mostramos un error
        showErrorOrSuccessAndRedirect("error", "Placa ya existente", "La placa ingresada ya pertenece a otro vehículo", "vehiculos.php");
        exit();
    }

    $fecha_actualizacion = date('Y-m-d H:i:s');
    // Actualizamos los datos en la base de datos
    $updateCar = $connection->prepare("UPDATE vehiculos SET vehiculo = :vehiculo, placa = :placaUpdate, fecha_actualizacion = :fecha_actualizacion WHERE placa = :placa");
    $updateCar->bindParam(':vehiculo', $vehiculo);
    $updateCar->bindParam(':placaUpdate', $placaUpdate);
    $updateCar->bindParam(':fecha_actualizacion', $fecha_actualizacion);
    $updateCar->bindParam(':placa', $placa);
    $updateCar->execute();

    if ($updateCar->rowCount() > 0) {
        showErrorOrSuccessAndRedirect("success", "Actualización Exitosa", "Los datos del vehículo se han actualizado correctamente", "vehiculos.php");
    } else {
        showErrorOrSuccessAndRedirect("error", "Error de Actualización", "Error al momento de actualizar los datos, por favor inténtalo nuevamente", "vehiculos.php");
    }
    exit();
}
?>
