<?php
//  REGISTRO DE ESTADO
if ((isset($_POST["MM_formRegisterState"])) && ($_POST["MM_formRegisterState"] == "formRegisterState")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE ESTADO
    $estado = $_POST['estado'];

    // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$estado])) {
        showErrorFieldsEmpty("estados.php");
        exit();
    }
    // validamos que no se repitan los datos del nombre del eSTADO
    // CONSULTA SQL PARA VERIFICAR SI EL REGISTRO YA EXISTE EN LA BASE DE DATOS
    $estadoQueryFetch = $connection->prepare("SELECT * FROM estados WHERE estado = :estado");
    $estadoQueryFetch->bindParam(':estado', $estado);
    $estadoQueryFetch->execute();
    $queryFetch = $estadoQueryFetch->fetchAll();
    // CONDICIONALES DEPENDIENDO EL RESULTADO DE LA CONSULTA
    if ($queryFetch) {
        // Si ya existe una eSTADO con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Los datos ingresados ya estan registrados", "estados.php");
        exit();
    } else {
        // Inserta los datos en la base de datos
        $registerState = $connection->prepare("INSERT INTO estados(estado) VALUES(:estado)");
        $registerState->bindParam(':estado', $estado);
        $registerState->execute();
        if ($registerState) {
            showErrorOrSuccessAndRedirect("success", "Registro Exitoso", "Los datos se han registrado correctamente", "estados.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de registro", "Error al momento de registrar los datos, por favor intentalo nuevamente", "estados.php");
            exit();
        }
    }
}