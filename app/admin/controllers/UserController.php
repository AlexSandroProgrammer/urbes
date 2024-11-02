<?php
//* Actualización de datos del usuario autenticado

// Verifica si se ha enviado el formulario de actualización y si corresponde al formulario correcto
if ((isset($_POST["MM_formUpdateMyDates"])) && ($_POST["MM_formUpdateMyDates"] == "formUpdateMyDates")) {

    // VARIABLES DE ASIGNACIÓN DE VALORES QUE SE ENVÍAN DESDE EL FORMULARIO DE REGISTRO DE ÁREA
    $documento = $_POST['documento'];       // Captura el valor del documento enviado por el formulario
    $names = $_POST['names'];               // Captura el valor del nombre enviado por el formulario
    $surnames = $_POST['surnames'];         // Captura el valor de los apellidos enviados por el formulario
    $celular = $_POST['celular'];           // Captura el valor del celular enviado por el formulario
    $tipo_documento = $_POST['tipo_documento']; // Captura el tipo de documento enviado por el formulario
    $password = $_POST['password'];         // Captura el valor de la contraseña enviada por el formulario

    // Validamos que no se haya recibido ningún dato vacío en los campos críticos
    if (isEmpty([$documento, $names, $surnames, $celular, $tipo_documento])) {
        showErrorFieldsEmpty("perfil.php"); // Si algún campo está vacío, muestra un error y redirige
        exit();                             // Termina la ejecución del script
    }

    // Prepara una consulta para verificar si el número de celular ya pertenece a otro usuario
    $useValidation = $connection->prepare("SELECT * FROM usuarios WHERE (celular = :celular) AND documento <> :documento");
    // Vincula el valor del celular a la consulta
    $useValidation->bindParam(':celular', $celular);
    // Vincula el valor del documento a la consulta
    $useValidation->bindParam(':documento', $documento);
    // Ejecuta la consulta
    $useValidation->execute();
    // Obtiene el resultado de la consulta
    $fetch = $useValidation->fetch(PDO::FETCH_ASSOC);

    // Condicionales dependiendo del resultado de la consulta
    if ($fetch) {
        // Si ya existe un usuario con el mismo celular y diferente documento, muestra un error
        showErrorOrSuccessAndRedirect("error", "Error de Actualización", "El número de celular ya le pertenece a un usuario", "perfil.php");
        exit(); // Termina la ejecución del script
    } else {
        try {
            $userData = $connection->prepare("SELECT * FROM usuarios WHERE documento = :documento");
            $userData->bindParam(':documento', $documento);
            // Ejecuta la consulta
            $userData->execute();
            // Obtiene el resultado de la consulta
            $user = $userData->fetch(PDO::FETCH_ASSOC);
            // Si el campo de la contraseña está vacío, conserva la contraseña actual del usuario
            if (isEmpty([$password])) {
                // Si no hay una nueva contraseña, se mantiene la contraseña actual del usuario
                $user_password = $user['password'];
            } else {
                // Si hay una nueva contraseña, se encripta usando password_hash con una opción de coste
                $user_password = encrypt_password($password);
            }
            // Inserta o actualiza los datos en la base de datos
            $updateDataUser = $connection->prepare("UPDATE usuarios SET nombres = :names, apellidos = :surnames, celular = :celular, tipo_documento = :tipo_documento, password = :password  WHERE documento = :documento");
            // Vincula los parámetros a la consulta de actualización
            $updateDataUser->bindParam(':names', $names);
            $updateDataUser->bindParam(':surnames', $surnames);
            $updateDataUser->bindParam(':celular', $celular);
            $updateDataUser->bindParam(':tipo_documento', $tipo_documento);
            $updateDataUser->bindParam(':password', $user_password);
            $updateDataUser->bindParam(':documento', $documento);
            // Ejecuta la consulta de actualización
            $updateDataUser->execute();

            // Verifica si la actualización fue exitosa
            if ($updateDataUser) {
                // Muestra un mensaje de éxito y redirige al perfil
                showErrorOrSuccessAndRedirect("success", "¡Genial!", "Los datos se han actualizado correctamente", "perfil.php");
                exit(); // Termina la ejecución del script
            }
        } catch (Exception $e) {
            // En caso de error, muestra un mensaje de error y redirige al perfil
            showErrorOrSuccessAndRedirect("error", "Error de Actualización", "Error al momento de actualizar los datos", "perfil.php");
            exit(); // Termina la ejecución del script
        }
    }
}