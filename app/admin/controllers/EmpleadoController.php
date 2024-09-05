<?php

// OBTENEMOS LA FECHA ACTUAL 
$fecha_registro = date('Y-m-d H:i:s');

// Registro de datos de aprendices
if ((isset($_POST["MM_formRegisterEmployee"])) && ($_POST["MM_formRegisterEmployee"] == "formRegisterEmployee")) {

    // VARIABLES DE ASIGNACIÓN DE VALORES QUE SE ENVÍAN DESDE EL FORMULARIO DE REGISTRO DE ÁREA
    $tipo_documento = $_POST['tipo_documento'];
    $documento = $_POST['documento'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $celular = $_POST['celular'];
    $celular_familiar = $_POST['celular_familiar'];
    $nombre_familiar = $_POST['nombre_familiar'];
    $parentezco_familiar = $_POST['parentezco_familiar'];
    $estado = $_POST['estado'];
    $eps = $_POST['eps'];
    $arl = $_POST['arl'];
    $password = $_POST['password'];

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
        $password
    ])) {
        showErrorFieldsEmpty("registrar_empleado.php");
        exit();
    }

    // Validamos que los datos de nombres, apellidos y nombre del familiar no contengan caracteres especiales
    if (containsSpecialCharacters([
        $nombres,
        $apellidos,
        $nombre_familiar,
    ])) {
        showErrorOrSuccessAndRedirect("error", "Error de digitación", "Por favor verifica que en ningún campo existan caracteres especiales. Los campos como el nombre, apellido o nombre del familiar no deben tener letras como la ñ o caracteres especiales.", "registrar_empleado.php");
        exit();
    }

    // ID DEL EMPLEADO
    $id_employee = 3;

    // Preparamos una consulta para validar si ya existe un usuario con el mismo documento o celular
    $userValidation = $connection->prepare("SELECT * FROM usuarios WHERE (documento = :documento OR celular = :celular) AND id_tipo_usuario = :id_tipo_usuario");
    $userValidation->bindParam(':documento', $documento);
    $userValidation->bindParam(':celular', $celular);
    $userValidation->bindParam(':id_tipo_usuario', $id_employee);
    $userValidation->execute();
    $resultValidation = $userValidation->fetchAll();

    // Si la validación falla, mostramos un mensaje de error
    if ($resultValidation) {
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Los datos ingresados ya están registrados, por favor verifica el número de documento y celular", "registrar_empleado.php");
        exit();
    } else {
        try {
            // Encriptamos la contraseña usando password_hash
            $pass_encriptaciones = [
                'cost' => 15
            ];
            $password_hash = password_hash($password, PASSWORD_DEFAULT, $pass_encriptaciones);

            // Insertamos los datos en la base de datos, incluyendo todos los campos requeridos
            $registerEmployee = $connection->prepare("INSERT INTO usuarios(documento, tipo_documento, nombres, apellidos, celular, celular_familiar, parentezco_familiar, nombre_familiar, password, id_tipo_usuario, id_estado, fecha_registro, eps, arl) 
            VALUES(:documento, :tipo_documento, :nombres, :apellidos, :celular, :celular_familiar, :parentezco_familiar, :nombre_familiar, :password, :id_tipo_usuario, :id_estado, :fecha_registro, :eps, :arl)");

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
            $registerEmployee->bindParam(':id_tipo_usuario', $id_employee);
            $registerEmployee->bindParam(':id_estado', $estado);
            $registerEmployee->bindParam(':fecha_registro', $fecha_registro);
            $registerEmployee->bindParam(':eps', $eps);
            $registerEmployee->bindParam(':arl', $arl);

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
