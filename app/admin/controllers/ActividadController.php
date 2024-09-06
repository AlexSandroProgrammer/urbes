<?php
//  REGISTRO DE AREA
if ((isset($_POST["MM_formRegisterActivity"])) && ($_POST["MM_formRegisterActivity"] == "formRegisterActivity")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE AREA
    $actividad = $_POST['actividad'];

    // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$actividad])) {
        showErrorFieldsEmpty("actividades.php");
        exit();
    }
    // validamos que no se repitan los datos del nombre del area
    // CONSULTA SQL PARA VERIFICAR SI EL REGISTRO YA EXISTE EN LA BASE DE DATOS
    $estadoQueryFetch = $connection->prepare("SELECT * FROM actividades WHERE actividad = :actividad");
    $estadoQueryFetch->bindParam(':actividad', $actividad);
    $estadoQueryFetch->execute();
    $queryFetch = $estadoQueryFetch->fetchAll();
    // CONDICIONALES DEPENDIENDO EL RESULTADO DE LA CONSULTA
    if ($queryFetch) {
        // Si ya existe una area con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Los datos ingresados ya estan registrados", "actividad.php");
        exit();
    } else {
        // Inserta los datos en la base de datos
        $registerActivity = $connection->prepare("INSERT INTO actividades(actividad) VALUES(:actividad)");
        $registerActivity->bindParam(':actividad', $actividad);
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
