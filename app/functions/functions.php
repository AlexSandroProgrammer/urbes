<?php
function isEmpty($fields)
{
    foreach ($fields as $field) {
        if (empty($field)) {
            return true;
        }
    }
    return false;
}

function containsSpecialCharacters($fields)
{
    foreach ($fields as $field) {
        if (!preg_match('/^[a-zA-Z\s]+$/', $field)) {
            return true;
        }
    }
    return false;
}

function isNotEmpty($fields)
{
    foreach ($fields as $field) {
        if (!empty($field)) {
            return true;
        }
    }
    return false;
}


function showErrorOrSuccessAndRedirect($icon, $title, $description, $location)
{

    echo "<script>      // Mostrar advertencia con SweetAlert
                Swal.fire({
                    icon: '$icon',
                    title: '$title',
                    text: '$description',
                    confirmButtonText: 'Aceptar',
                    timer: 300000, // Tiempo en milisegundos para mostrar la advertencia
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    willClose: () => {
                        localStorage.removeItem('empleados');
                         window.location = '$location'
                    },
                });</script>";
}

function showErrorOrSuccessAndRedirectInfo($icon, $title, $description, $location)
{

    echo "<script>      // Mostrar advertencia con SweetAlert
                Swal.fire({
                    icon: '$icon',
                    title: '$title',
                    text: '$description',
                    confirmButtonText: 'Aceptar',
                    willClose: () => {
                        localStorage.removeItem('empleados');
                         window.location = '$location'
                    },
                });</script>";
}

function isValidTime($time)
{
    $format = 'H:i';
    $parsedTime = DateTime::createFromFormat($format, $time);
    return $parsedTime && $parsedTime->format($format) === $time;
}



function showErrorFieldsEmpty($location)
{
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Algunos datos estan vacios, debes ingresar todos los datos del formulario',
            timer: 300000, // Tiempo en milisegundos para mostrar la advertencia
            timerProgressBar: true,
        }).then(() => {
            window.location='$location'    
        });</script>";
}

function isFileUploaded($file)
{
    return isset($file) && $file['error'] === 0;
}

function isFileValid($file, $allowedTypes, $maxSizeKB)
{
    return in_array($file["type"], $allowedTypes) && $file["size"] <= $maxSizeKB * 1024;
}

function createDirectoryIfNotExists($directory)
{
    if (!file_exists($directory)) {
        mkdir($directory);
    }
}

function moveUploadedFile($file, $destination)
{
    return move_uploaded_file($file["tmp_name"], $destination);
}


// FUNCION QUE PERMITA PASAR PARAMETROS PARA CREAR UN CARD
function cardStadicts($item, $table, $route, $nameTitle)
{
    require_once("../../../database/connection.php");
    $db = new Database();
    $connection = $db->conectar();
    $countAreas = "SELECT COUNT(*) AS $item FROM $table";
    try {
        $resultado = $connection->query($countAreas);
        $conteo = $resultado->fetch(PDO::FETCH_ASSOC)[$item];
        if ($conteo >= 1) {
            echo "
                <div class='col-md-6 col-12 mb-4'>
                    <div class='card'>
                        <div class='card-body'>
                            <div class='card-title d-flex align-items-start justify-content-between'>
                                <div class='avatar flex-shrink-0 w-50'>
                                    <i class='rounded bx bx-layout'></i>
                                </div>
                                <div class='dropdown'>
                                    <a href='$route' class='btn btn-primary'>Ver $nameTitle</a>
                                </div>
                            </div>
                            <span class='fw-medium d-block mb-1'>$nameTitle</span>
                            <h3 class='card-title mb-2'>" . htmlspecialchars($conteo, ENT_QUOTES, 'UTF-8') . "</h3>
                        </div>
                    </div>
                </div>
            ";
        } else {
            echo "
                <div class='col-lg-6 col-md-12 col-6 mb-4'>
                    <div class='card'>
                        <div class='card-body'>
                            <span class='fw-medium d-block mb-1'>No hay registros de $nameTitle.</span>
                        </div>
                    </div>
                </div>
            ";
        }
    } catch (PDOException $e) {
        echo "Error en la consulta: " . $e->getMessage();
    }
}

// FUNCION PARA ENCRIPTAR CONTRASEÑAS
function encrypt_password($password_usuario)
{
    // creamos una lleva unica
    $key = 'M*1240??Cla!!SecretaEs//esta??Nad!e2301';
    // Generamos un IV aleatorio
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    // Ciframos la contraseña usando AES-256-CBC
    $password_cifrada = openssl_encrypt($password_usuario, 'aes-256-cbc', $key, 0, $iv);
    // Codificamos la contraseña cifrada y el IV juntos en base64 para almacenarlos
    return base64_encode($password_cifrada . '::' . $iv);
}

// FUNCION PARA DESENCRIPTAR CONTRASEÑAS
function bcrypt_password($password_cifrada)
{
    $key = 'M*1240??Cla!!SecretaEs//esta??Nad!e2301';
    // Decodificamos la contraseña cifrada que incluye el IV
    list($encrypted_data, $iv) = explode('::', base64_decode($password_cifrada), 2);

    // Desciframos la contraseña utilizando AES-256-CBC
    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, 0, $iv);
}


// FUNCION PARA REALIZAR CONTEO DE USUARIOS DE ACUERDO AL ESTADO
function countStatesUsers($item, $table, $title, $description, $id_estado, $state, $id_tipo_usuario)
{
    require_once("../../../database/connection.php");
    $db = new Database();
    $connection = $db->conectar();
    $countTable = "SELECT COUNT(*) AS $item FROM $table WHERE id_tipo_usuario = $id_tipo_usuario AND id_estado = $id_estado";
    try {
        $resultado = $connection->query($countTable);
        $count = $resultado->fetch(PDO::FETCH_ASSOC)[$item];
        if ($count >= 1) {
            echo "
                <li class='d-flex mb-4 pb-1'>
                    <div class='avatar flex-shrink-0 me-3'>
                        <span class='avatar-initial rounded bg-label-$state'><i class='bx bx-user'></i></span>
                    </div>
                    <div class='d-flex w-100 flex-wrap align-items-center justify-content-between gap-2'>
                        <div class='me-2'>
                            <h6 class='mb-0'>$title</h6>
                            <small class='text-muted'>$description</small>
                        </div>
                        <div class='user-progress'>
                            <small class='fw-semibold'>$count</small>
                        </div>
                    </div>
                </li>
            ";
        } else {
            echo "
                                <li class='d-flex mb-4 pb-1'>
                                <div class='avatar flex-shrink-0 me-3'>
                                    <span class='avatar-initial rounded bg-label-$state'><i class='bx bx-user'></i></span>
                                </div>
                                <div class='d-flex w-100 flex-wrap align-items-center justify-content-between gap-2'>
                                    <div class='me-2'>
                                        <h6 class='mb-0'>$title</h6>
                                        <small class='text-muted'>Sin registros</small>
                                    </div>
                                    <div class='user-progress'>
                                        <small class='fw-semibold'>0</small>
                                    </div>
                                </div>
                            </li>
            ";
        }
    } catch (PDOException $e) {
        echo "Error en la consulta: " . $e->getMessage();
    }
}


// ------------------------ FUNCTIONS OR METHODS ---------------------------------------
// FUNCTION CREATE USER
function registerUser($connection, $rol, $nombre_usuario, $email, $user_password, $estado)
{
    // Prepara la consulta SQL usando sentencias preparadas
    $registerUser = "INSERT INTO usuarios(tipo_usuario,nombre_usuario,email, password,estado_usuario) VALUES (?,?,?,?,?)";
    $requestUser = $connection->prepare($registerUser);

    // Bind de los parámetros
    $requestUser->bindParam(1, $rol);
    $requestUser->bindParam(2, $nombre_usuario);
    $requestUser->bindParam(3, $email);
    $requestUser->bindParam(4, $user_password);
    $requestUser->bindParam(5, $estado);



    // Ejecuta la consulta
    if ($requestUser->execute()) {
        return true; // Registro exitoso
    } else {
        return false; // Error al registrar el usuario
    }
}


// FUNCTION UPDATE USER
function updateUser($connection, $id_usuario, $rol, $names, $username)
{
    // Prepara la consulta SQL usando sentencias preparadas
    $updateUser = "UPDATE usuarios SET rol = ?, nombre_Usuario = ?, usuario = ? WHERE id_usuario = ?";
    $queryUser = $connection->prepare($updateUser);


    // Bind de los parámetros
    $queryUser->bindParam(1, $rol);
    $queryUser->bindParam(2, $names);
    $queryUser->bindParam(3, $username);
    $queryUser->bindParam(4, $id_usuario);


    // Ejecuta la consulta
    if ($queryUser->execute()) {
        return true; // Actualizacion exitosa
    } else {
        return false; // Error al actualizar el usuario
    }
}


function encriptar($texto, $token)
{
    $clave = md5($token); // Generar clave a partir del token
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $textoEncriptado = openssl_encrypt($texto, 'aes-256-cbc', $clave, 0, $iv);
    return base64_encode($iv . $textoEncriptado);
}

$token = "11SXDLSLDDDDKFE332KDKS";