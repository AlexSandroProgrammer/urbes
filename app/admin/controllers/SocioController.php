<?php

// OBTENEMOS LA FECHA ACTUAL 
$fecha_registro = date('Y-m-d H:i:s');

//* Registro de datos de socios
if ((isset($_POST["MM_formRegisterPartner"])) && ($_POST["MM_formRegisterPartner"] == "formRegisterPartner")) {
    // VARIABLES DE ASIGNACIÓN DE VALORES QUE SE ENVÍAN DESDE EL FORMULARIO DE REGISTRO DE ÁREA
    $tipo_documento = $_POST['tipo_documento'];
    $documento = $_POST['documento'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $celular = $_POST['celular'];
    $estado = $_POST['estado'];
    $password = $_POST['password'];

    // Validamos que no se haya recibido ningún dato vacío
    if (isEmpty([
        $tipo_documento,
        $documento,
        $nombres,
        $apellidos,
        $celular,
        $estado,
        $password
    ])) {
        showErrorFieldsEmpty("registrar_socio.php");
        exit();
    }

    // Validamos que los datos de nombres, apellidos y nombre del familiar no contengan caracteres especiales
    if (containsSpecialCharacters([
        $nombres,
        $apellidos
    ])) {
        showErrorOrSuccessAndRedirect("error", "Error de digitación", "Por favor verifica que en ningún campo existan caracteres especiales. Los campos como el nombre, apellido o nombre del familiar no deben tener letras como la ñ o caracteres especiales.", "registrar_socio.php");
        exit();
    }

    // Preparamos una consulta para validar si ya existe un usuario con el mismo documento o celular
    $partnerValidation = $connection->prepare("SELECT * FROM usuarios WHERE documento = :documento OR celular = :celular");
    $partnerValidation->bindParam(':documento', $documento);
    $partnerValidation->bindParam(':celular', $celular);
    $partnerValidation->execute();
    $resultValidation = $partnerValidation->fetchAll();

    // Si la validación falla, mostramos un mensaje de error
    if ($resultValidation) {
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Los datos ingresados ya están registrados, por favor verifica el número de documento y celular", "registrar_socio.php");
        exit();
    } else {
        try {
            $id_partner = 1;
            // encriptamos la contraseña
            $password_hash = encrypt_password($password);
            // Insertamos los datos en la base de datos, incluyendo todos los campos requeridos
            $registerPartner = $connection->prepare("INSERT INTO usuarios(documento, tipo_documento, nombres, apellidos, celular, password, id_tipo_usuario, id_estado, fecha_registro) VALUES(:documento, :tipo_documento, :nombres, :apellidos, :celular, :password, :id_tipo_usuario, :id_estado, :fecha_registro)");
            // Vinculamos los parámetros
            $registerPartner->bindParam(':documento', $documento);
            $registerPartner->bindParam(':tipo_documento', $tipo_documento);
            $registerPartner->bindParam(':nombres', $nombres);
            $registerPartner->bindParam(':apellidos', $apellidos);
            $registerPartner->bindParam(':celular', $celular);
            $registerPartner->bindParam(':password', $password_hash);
            $registerPartner->bindParam(':id_tipo_usuario', $id_partner);
            $registerPartner->bindParam(':id_estado', $estado);
            $registerPartner->bindParam(':fecha_registro', $fecha_registro);
            // Ejecutamos la consulta
            $registerPartner->execute();
            // Verificamos si la inserción fue exitosa
            if ($registerPartner) {
                showErrorOrSuccessAndRedirect("success", "Registro Exitoso", "Los datos se han registrado correctamente", "socios_activos.php");
                exit();
            }
        } catch (Exception $e) {
            // En caso de error, mostramos un mensaje y redirigimos
            showErrorOrSuccessAndRedirect("error", "Error de Registro", "Error al momento de registrar los datos.", "registrar_socio.php");
            exit();
        }
    }
}

// * metodo actuaizar datos de socios
if ((isset($_POST["MM_formUpdatePartner"])) && ($_POST["MM_formUpdatePartner"] == "formUpdatePartner")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE AREA
    $tipo_documento = $_POST['tipo_documento'];
    $documento = $_POST['documento'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $celular = $_POST['celular'];
    $estado = $_POST['estado'];
    $password = $_POST['password'];
    $ruta = $_POST['ruta'];
    // Validamos que no hayamos recibido ningún dato vacío
    if (isEmpty([
        $tipo_documento,
        $documento,
        $nombres,
        $apellidos,
        $celular,
        $estado,
        $password,
        $ruta
    ])) {
        showErrorFieldsEmpty("socios_activos.php");
        exit();
    }

    // validamos que los datos ningun tenga un caracter especial 
    if (containsSpecialCharacters([
        $nombres,
        $apellidos,
    ])) {
        showErrorOrSuccessAndRedirect(
            "error",
            "Error de digitacion",
            "Por favor verifica que en ningun campo existan caracteres especiales, los campos como el nombre, apellido, no deben tener letras como la ñ o caracteres especiales.",
            "editar_socio.php?id_partner-edit=" . $documento . "&ruta=" . $ruta
        );
        exit();
    }
    // ID DEL SOCIO
    $id_socio = 3;
    $partnerValidation = $connection->prepare("SELECT * FROM usuarios WHERE (celular = :celular) AND documento <> :documento");
    $partnerValidation->bindParam(':documento', $documento);
    $partnerValidation->bindParam(':celular', $celular);
    $partnerValidation->execute();
    $resultValidation = $partnerValidation->fetchAll();
    // Condicionales dependiendo del resultado de la consulta
    if ($resultValidation) {
        // Si ya existe una area con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Por favor revisa los datos ingresados, porque los datos registrados ya pertenecen a otro usuario.", "editar_socio.php?id_partner-edit=" . $documento . "&ruta=" . $ruta);
        exit();
    } else {
        try {
            // fecha actual
            $fecha_actualizacion = date('Y-m-d H:i:s');
            // encriptacion de contraseña
            $password_hash = encrypt_password($password);
            // Inserción de los datos en la base de datos, incluyendo la edad
            $editarDatosSocio = $connection->prepare("UPDATE usuarios  SET nombres = :nombres, apellidos = :apellidos, celular = :celular, id_estado = :estado, password = :password, tipo_documento = :tipo_documento, fecha_actualizacion = :fecha_actualizacion WHERE documento = :documento");
            // Vincular los parámetros
            $editarDatosSocio->bindParam(':nombres', $nombres);
            $editarDatosSocio->bindParam(':apellidos', $apellidos);
            $editarDatosSocio->bindParam(':celular', $celular);
            $editarDatosSocio->bindParam(':estado', $estado);
            $editarDatosSocio->bindParam(':password', $password_hash);
            $editarDatosSocio->bindParam(':tipo_documento', $tipo_documento);
            $editarDatosSocio->bindParam(':fecha_actualizacion', $fecha_actualizacion);
            $editarDatosSocio->bindParam(':documento', $documento);
            $editarDatosSocio->execute();
            if ($editarDatosSocio) {
                showErrorOrSuccessAndRedirect("success", "¡Perfecto!", "Los datos se han actualizado correctamente", $ruta);
                exit();
            }
        } catch (\Throwable $th) {
            // En caso de error, mostramos un mensaje y redirigimos
            showErrorOrSuccessAndRedirect("error", "Error de actualización", "Error al momento de actualizar los datos.", "socios_activos.php");
            exit();
        }
    }
}

// *metodo para borrar el registro
if (isset($_GET['id_partner-delete'])) {
    $id_partner = $_GET["id_partner-delete"];
    $ruta = $_GET["ruta"];
    if (isEmpty([$id_partner])) {
        showErrorOrSuccessAndRedirect("error", "Error de datos", "El parametro enviado se encuentra vacio.", $ruta);
        exit();
    } else {
        $deleteSocio = $connection->prepare("SELECT * FROM usuarios WHERE documento = :id_partner");
        $deleteSocio->bindParam(":id_partner", $id_partner);
        $deleteSocio->execute();
        $deleteSocioSelect = $deleteSocio->fetch(PDO::FETCH_ASSOC);
        if ($deleteSocioSelect) {
            // Borramos el registro del socio de la base de datos
            $delete = $connection->prepare("DELETE FROM usuarios WHERE documento = :id_partner");
            $delete->bindParam(':id_partner', $id_partner);
            $delete->execute();
            if ($delete) {
                showErrorOrSuccessAndRedirect("success", "Perfecto", "El registro seleccionado se ha eliminado correctamente.", $ruta);
                exit();
            }
        }
        showErrorOrSuccessAndRedirect("error", "Error de peticion", "Hubo algun tipo de error al momento de eliminar el registro", $ruta);
        exit();
    }
}