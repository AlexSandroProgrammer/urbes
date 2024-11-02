<?php

// OBTENEMOS LA FECHA ACTUAL 
$fecha_registro = date('Y-m-d H:i:s');

//* Registro de datos de empleados
if ((isset($_POST["MM_formRegisterEmployee"])) && ($_POST["MM_formRegisterEmployee"] == "formRegisterEmployee")) {

    // VARIABLES DE ASIGNACIÓN DE VALORES QUE SE ENVÍAN DESDE EL FORMULARIO DE REGISTRO DE ÁREA
    $tipo_documento = $_POST['tipo_documento'];
    $documento = $_POST['documento'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $celular = $_POST['celular'];
    $celular_familiar = $_POST['celular_familiar'];
    $nombre_familiar = $_POST['nombre_familiar'];
    $ciudad = $_POST['ciudad'];
    $parentezco_familiar = $_POST['parentezco_familiar'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = isset($_POST['fecha_fin']) && $_POST['fecha_fin'] !== '' ? $_POST['fecha_fin'] : null;
    $estado = $_POST['estado'];
    $eps = $_POST['eps'];
    $arl = $_POST['arl'];
    $password = $_POST['password'];
    $rh = $_POST['rh'];
    $tipo_rol = $_POST['tipo_rol'];
    // Validamos que no se haya recibido ningún dato vacío
    if (isEmpty([
        $tipo_documento,
        $documento,
        $nombres,
        $apellidos,
        $celular,
        $estado,
        $celular_familiar,
        $nombre_familiar,
        $parentezco_familiar,
        $eps,
        $arl,
        $password,
        $fecha_inicio,
        $ciudad,
        $rh,
        $tipo_rol
    ])) {
        showErrorFieldsEmpty("registrar_empleado.php");
        exit();
    }

    // Validamos que los datos de nombres, apellidos y nombre del familiar no contengan caracteres especiales
    if (containsSpecialCharacters([
        $nombres,
        $apellidos,
        $nombre_familiar
    ])) {
        showErrorOrSuccessAndRedirect("error", "Error de digitación", "Por favor verifica que en ningún campo existan caracteres especiales. Los campos como el nombre, apellido o nombre del familiar no deben tener letras como la ñ o caracteres especiales.", "registrar_empleado.php");
        exit();
    }
    // Preparamos una consulta para validar si ya existe un usuario con el mismo documento o celular
    $userValidation = $connection->prepare("SELECT * FROM usuarios WHERE documento = :documento OR celular = :celular");
    $userValidation->bindParam(':documento', $documento);
    $userValidation->bindParam(':celular', $celular);
    $userValidation->execute();
    $resultValidation = $userValidation->fetchAll();

    // Si la validación falla, mostramos un mensaje de error
    if ($resultValidation) {
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Los datos ingresados ya están registrados, por favor verifica el número de documento y celular ingresados", "registrar_empleado.php");
        exit();
    } else {
        try {
            // encriptamos la contraseña
            $password_hash = encrypt_password($password);
            // Insertamos los datos en la base de datos, incluyendo todos los campos requeridos
            $registerEmployee = $connection->prepare("INSERT INTO usuarios(documento, tipo_documento, nombres, apellidos, celular, celular_familiar, parentezco_familiar, nombre_familiar, password, id_tipo_usuario, id_estado, fecha_registro, eps, arl, id_ciudad, fecha_inicio, fecha_fin, rh) VALUES(:documento, :tipo_documento, :nombres, :apellidos, :celular, :celular_familiar, :parentezco_familiar, :nombre_familiar, :password, :id_tipo_usuario, :id_estado, :fecha_registro, :eps, :arl, :ciudad, :fecha_inicio, :fecha_fin, :rh)");
            // Vinculamos los parámetros
            $registerEmployee->bindParam(':documento', $documento);
            $registerEmployee->bindParam(':tipo_documento', $tipo_documento);
            $registerEmployee->bindParam(':nombres', $nombres);
            $registerEmployee->bindParam(':apellidos', $apellidos);
            $registerEmployee->bindParam(':celular', $celular);
            $registerEmployee->bindParam(':celular_familiar', $celular_familiar);
            $registerEmployee->bindParam(':parentezco_familiar', $parentezco_familiar);
            $registerEmployee->bindParam(':nombre_familiar', $nombre_familiar);
            $registerEmployee->bindParam(':password', $password_hash);
            $registerEmployee->bindParam(':id_tipo_usuario', $tipo_rol);
            $registerEmployee->bindParam(':id_estado', $estado);
            $registerEmployee->bindParam(':fecha_registro', $fecha_registro);
            $registerEmployee->bindParam(':eps', $eps);
            $registerEmployee->bindParam(':arl', $arl);
            $registerEmployee->bindParam(':ciudad', $ciudad);
            $registerEmployee->bindParam(':fecha_inicio', $fecha_inicio);
            $registerEmployee->bindParam(':fecha_fin', $fecha_fin);
            $registerEmployee->bindParam(':rh', $rh);
            // Ejecutamos la consulta
            $registerEmployee->execute();
            // Verificamos si la inserción fue exitosa
            if ($registerEmployee) {
                showErrorOrSuccessAndRedirect("success", "Registro Exitoso", "Los datos se han registrado correctamente", "empleados_activos.php");
                exit();
            }
        } catch (Exception $e) {
            // En caso de error, mostramos un mensaje y redirigimos
            showErrorOrSuccessAndRedirect("error", "Error de Registro", "Error al momento de registrar los datos.", "registrar_empleado.php");
            exit();
        }
    }
}

// * metodo actuaizar datos de empleados
if ((isset($_POST["MM_formUpdateEmployee"])) && ($_POST["MM_formUpdateEmployee"] == "formUpdateEmployee")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE AREA
    $tipo_documento = $_POST['tipo_documento'];
    $documento = $_POST['documento'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $celular = $_POST['celular'];
    $estado = $_POST['estado'];
    $password = $_POST['password'];
    $ruta = $_POST['ruta'];
    $nombre_familiar = $_POST['nombre_familiar'];
    $celular_familiar = $_POST['celular_familiar'];
    $parentezco_familiar = $_POST['parentezco_familiar'];
    $eps = $_POST['eps'];
    $arl = $_POST['arl'];
    $ciudad = $_POST['ciudad'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $rh = $_POST['rh'];
    $tipo_rol = $_POST['tipo_rol'];
    // Validamos que no hayamos recibido ningún dato vacío
    if (isEmpty([
        $tipo_documento,
        $documento,
        $nombres,
        $apellidos,
        $celular,
        $estado,
        $password,
        $ruta,
        $nombre_familiar,
        $celular_familiar,
        $parentezco_familiar,
        $eps,
        $arl,
        $ciudad,
        $fecha_inicio,
        $fecha_fin,
        $rh,
        $tipo_rol
    ])) {
        showErrorFieldsEmpty("empleados_activos.php");
        exit();
    }

    // validamos que los datos ningun tenga un caracter especial 
    if (containsSpecialCharacters([
        $nombres,
        $apellidos,
        $nombre_familiar,
        $parentezco_familiar
    ])) {
        showErrorOrSuccessAndRedirect(
            "error",
            "Error de digitacion",
            "Por favor verifica que en ningun campo existan caracteres especiales, los campos como el nombre, apellido, no deben tener letras como la ñ o caracteres especiales.",
            "editar_empleado.php?id_employee-edit=" . $documento . "&ruta=" . $ruta
        );
        exit();
    }

    $userValidation = $connection->prepare("SELECT * FROM usuarios WHERE (celular = :celular) AND documento <> :documento");
    $userValidation->bindParam(':documento', $documento);
    $userValidation->bindParam(':celular', $celular);
    $userValidation->execute();
    $resultValidation = $userValidation->fetchAll();
    // Condicionales dependiendo del resultado de la consulta
    if ($resultValidation) {
        // Si ya existe una area con ese nombre entonces cancelamos el registro y le indicamos al usuario
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Por favor revisa los datos ingresados, porque los datos registrados ya pertenecen a otro usuario.", "editar_empleado.php?id_employee-edit=" . $documento . "&ruta=" . $ruta);
        exit();
    } else {
        try {
            // fecha actual
            $fecha_actualizacion = date('Y-m-d H:i:s');
            // encriptacion de contraseña
            $password_hash = encrypt_password($password);
            // Inserción de los datos en la base de datos, incluyendo la edad
            $editEmployeeData = $connection->prepare("UPDATE usuarios  SET nombres = :nombres, apellidos = :apellidos, celular = :celular, id_estado = :estado, password = :password, nombre_familiar = :nombre_familiar, celular_familiar = :celular_familiar, parentezco_familiar = :parentezco_familiar, eps = :eps, arl = :arl, tipo_documento = :tipo_documento, fecha_actualizacion = :fecha_actualizacion, id_ciudad = :id_ciudad, fecha_inicio = :fecha_inicio, fecha_fin = :fecha_fin, rh = :rh, id_tipo_usuario = :tipo_rol  WHERE documento = :documento");
            // Vincular los parámetros
            $editEmployeeData->bindParam(':nombres', $nombres);
            $editEmployeeData->bindParam(':apellidos', $apellidos);
            $editEmployeeData->bindParam(':celular', $celular);
            $editEmployeeData->bindParam(':estado', $estado);
            $editEmployeeData->bindParam(':password', $password_hash);
            $editEmployeeData->bindParam(':nombre_familiar', $nombre_familiar);
            $editEmployeeData->bindParam(':celular_familiar', $celular_familiar);
            $editEmployeeData->bindParam(':parentezco_familiar', $parentezco_familiar);
            $editEmployeeData->bindParam(':eps', $eps);
            $editEmployeeData->bindParam(':arl', $arl);
            $editEmployeeData->bindParam(':tipo_documento', $tipo_documento);
            $editEmployeeData->bindParam(':fecha_actualizacion', $fecha_actualizacion);
            $editEmployeeData->bindParam(':id_ciudad', $ciudad);
            $editEmployeeData->bindParam(':fecha_inicio', $fecha_inicio);
            $editEmployeeData->bindParam(':fecha_fin', $fecha_fin);
            $editEmployeeData->bindParam(':rh', $rh);
            $editEmployeeData->bindParam(':tipo_rol', $tipo_rol);
            $editEmployeeData->bindParam(':documento', $documento);
            $editEmployeeData->execute();
            if ($editEmployeeData) {
                showErrorOrSuccessAndRedirect("success", "¡Perfecto!", "Los datos se han actualizado correctamente", $ruta);
                exit();
            }
        } catch (\Throwable $th) {
            // En caso de error, mostramos un mensaje y redirigimos
            showErrorOrSuccessAndRedirect("error", "Error de actualización", "Error al momento de actualizar los datos.", "empleados_activos.php");
            exit();
        }
    }
}
// *metodo para borrar el registro
if (isset($_GET['id_employee-delete'])) {
    $id_employee = $_GET["id_employee-delete"];
    $ruta = $_GET["ruta"];
    if (isEmpty([$id_employee])) {
        showErrorOrSuccessAndRedirect("error", "Error de datos", "El parametro enviado se encuentra vacio.", $ruta);
        exit();
    } else {
        $deleteEmpleado = $connection->prepare("SELECT * FROM usuarios WHERE documento = :id_employee");
        $deleteEmpleado->bindParam(":id_employee", $id_employee);
        $deleteEmpleado->execute();
        $deleteEmpleadoSelect = $deleteEmpleado->fetch(PDO::FETCH_ASSOC);
        if ($deleteEmpleadoSelect) {
            // Borramos el registro del empleado de la base de datos
            $delete = $connection->prepare("DELETE FROM usuarios WHERE documento = :id_employee");
            $delete->bindParam(':id_employee', $id_employee);
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