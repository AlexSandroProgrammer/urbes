<?php
require_once "../../../database/connection.php";
$db = new Database();
$connection = $db->conectar();
require_once("../../functions/functions.php");
// controlador inicio de sesion
if (isset($_POST["iniciarSesion"])) {
    $documento = $_POST["documento"];
    $password = $_POST['password'];
    // validamos que no vengan campos vacios
    if (isEmpty([$documento, $password])) {
        showErrorFieldsEmpty("index.php");
        exit();
    }
    // Realiza la consulta de autenticación
    $authValidation = $connection->prepare("SELECT * FROM usuarios INNER JOIN tipo_usuario ON usuarios.id_tipo_usuario = tipo_usuario.id_tipo_usuario WHERE documento = :documento AND id_estado = 1");
    $authValidation->bindParam(':documento', $documento);
    $authValidation->execute();
    $authSession = $authValidation->fetch(PDO::FETCH_ASSOC);
    if (!$authSession) {
        showErrorOrSuccessAndRedirect('error', 'Error de credenciales', 'El documento o la contraseña son incorrectas', 'index.php');
        exit();
        // desencriptacion de la contraseña
    }
    $password_bcrypt = bcrypt_password($authSession['password']);


    if ($authSession and $password == $password_bcrypt) {
        // Si la autenticación es exitosa
        $_SESSION['id_rol'] = $authSession['id_tipo_usuario'];
        $_SESSION['rol'] = $authSession['tipo_usuario'];
        $_SESSION['names'] = $authSession['nombres'];
        $_SESSION['surnames'] = $authSession['apellidos'];
        $_SESSION['documento'] = $authSession['documento'];
        
        $_SESSION['last_activity'] = time();
        
        if ($_SESSION['id_rol'] == 1) {
            header("Location:../../admin");
        } else if ($_SESSION['id_rol'] == 2) {
            header("Location:../../admin");
        } else if ($_SESSION['id_rol'] == 3 || $_SESSION['id_rol'] == 4) {
            header("Location:../../employee");
        } else {
            showErrorOrSuccessAndRedirect(
                "error",
                "Error de autenticacion",
                "No tienes permiso para acceder a ningun tipo de cuenta autorizada",
                "index.php"
            );
            exit();
        }
    } else {
        $id_tipo_usuario = 1;
        // llamamos los datos del usuario que esta intentando autenticarse
        $selectDocument = $connection->prepare("SELECT * FROM usuarios WHERE documento = :documento AND id_tipo_usuario <> :id_tipo_usuario");
        $selectDocument->bindParam(':documento', $documento);
        $selectDocument->bindParam(':id_tipo_usuario', $id_tipo_usuario);
        $selectDocument->execute();
        $selectDocumentSession = $selectDocument->fetch(PDO::FETCH_ASSOC);
        if ($selectDocumentSession) {
            // llamamos el horario local
            date_default_timezone_set("America/Bogota");
            $fecha_actual = date("Y-m-d");
            // creamos la cantidad posible de inicio de sesion
            $posibilidades = 5;
            $intentos_usuario = $connection->prepare("SELECT COUNT(*) AS conteointentos FROM intentos_fallidos WHERE documento = :documento AND fecha_intento = :fecha_actual");
            $intentos_usuario->bindParam(':documento', $documento);
            $intentos_usuario->bindParam(':fecha_actual', $fecha_actual);
            $intentos_usuario->execute();
            $intentos_realizados = $intentos_usuario->fetch(PDO::FETCH_ASSOC);
            if ($intentos_realizados['conteointentos'] == $posibilidades) {
                // si ya es igual al intento 
                $updateState = $connection->prepare("UPDATE usuarios SET id_estado = 2 WHERE documento = :documento");
                $updateState->bindParam(':documento', $documento);
                $updateState->execute();
                showErrorOrSuccessAndRedirect(
                    "error",
                    "Cuenta Bloqueada",
                    "Estimado usuario por favor comunicate con el administrador, ya que su cuenta ha sido bloqueada por superar los intentos posibles de inicio de sesion",
                    "index.php"
                );
                exit();
            }
            $registrointentos = $connection->prepare("INSERT INTO intentos_fallidos(documento, fecha_intento) VALUES (:documento,:fecha_actual)");
            $registrointentos->bindParam(':documento', $documento);
            $registrointentos->bindParam(':fecha_actual', $fecha_actual);
            $registrointentos->execute();
            if ($registrointentos) {
                // CREAMOS LA CONSULTA PARA REALIZAR EL CONTEO DE LOS INTENTOS QUE HA REALIZADO EL USUARIO
                $intentosPosibles = $connection->prepare("SELECT COUNT(*) AS contador FROM intentos_fallidos WHERE documento = :documento AND fecha_intento = :fecha_actual");
                $intentosPosibles->bindParam(':documento', $documento);
                $intentosPosibles->bindParam(':fecha_actual', $fecha_actual);
                $intentosPosibles->execute();
                $intentoUsuario = $intentosPosibles->fetch(PDO::FETCH_ASSOC);
                if ($intentoUsuario['contador'] == $posibilidades) { // Verificar el valor de "conteo"
                    // si ya es igual al intento 
                    $updateState = $connection->prepare("UPDATE usuarios SET id_estado = 2 WHERE documento = :documento");
                    $updateState->bindParam(':documento', $documento);
                    $updateState->execute();
                    showErrorOrSuccessAndRedirect(
                        "error",
                        "Cuenta Bloqueada",
                        "Estimado Usuario, su cuenta ha sido bloqueada por superar los intentos posibles de inicio de sesion",
                        "index.php"
                    );
                    exit();
                }
            }
        }
        showErrorOrSuccessAndRedirect(
            'error',
            'Error Inicio de Sesion',
            'Error al momento de iniciar sesion, revisa por favor tus credenciales',
            ''
        );
    }
}