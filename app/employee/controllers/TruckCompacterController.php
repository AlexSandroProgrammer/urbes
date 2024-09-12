<?php
// OBTENEMOS LA FECHA ACTUAL 
$fecha_registro = date('Y-m-d H:i:s');

//* Registro de datos de registro de actividades
if ((isset($_POST["MM_formRegisterVehicleCompacter"])) && ($_POST["MM_formRegisterVehicleCompacter"] == "formRegisterVehicleCompacter")) {
    // VARIABLES DE ASIGNACIÓN DE VALORES QUE SE ENVÍAN DESDE EL FORMULARIO DE REGISTRO DE VEHICULO COMPACTADOR
    $fecha_inicio = $_POST['fecha_inicio'];
    $vehiculo = $_POST['vehiculo'];
    $documento = $_POST['documento'];
    $labor = $_POST['labor'];
    $hora_inicio = $_POST['hora_inicio'];
    $foto_kilometraje = $_FILES['foto_kilometraje']['name'];
    $kilometraje = $_POST['kilometraje'];
    $horometro = $_POST['horometro'];
    $ciudad = $_POST['ciudad'];
    $pendiente = 4;
    // Validamos que no se haya recibido ningún dato vacío
    if (isEmpty([
        $fecha_inicio,
        $vehiculo,
        $documento,
        $labor,
        $foto_kilometraje,
        $kilometraje,
        $horometro,
        $ciudad,
    ])) {
        showErrorFieldsEmpty("vehiculo_compactador.php");
        exit();
    }
    //* Validamos que el kilometraje sea un número
    try {
        // encriptamos la contraseña
        $password_hash = encrypt_password($password);
        // Insertamos los datos en la base de datos, incluyendo todos los campos requeridos
        $registerEmployee = $connection->prepare("INSERT INTO registro_actividades(documento) VALUES(:documento)");
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