<?php
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
    $empleados = json_decode($_POST['empleados'], true);
    echo json_decode($empleados);
    $pendiente = 4;
    // Validamos que no se haya recibido ningún dato vacío
    if (isEmpty([
        $fecha_inicio,
        $vehiculo,
        $documento,
        $labor,
        $hora_inicio,
        $foto_kilometraje,
        $kilometraje,
        $horometro,
        $ciudad,
    ])) {
        showErrorFieldsEmpty("vehiculo_compactador.php");
        exit();
    }
    //* Validamos que el kilometraje sea un número
    if (isFileUploaded($_FILES['foto_kilometraje'])) {
        $permitidos = array(
            'image/jpeg',
            'image/png',
            'image/jpg',
        );
        $limite_KB = 5000;
        if (isFileValid($_FILES['foto_kilometraje'], $permitidos, $limite_KB)) {
            $ruta = "../assets/images/";
            // Obtener la extensión del archivo
            $nombreArchivo = $_FILES['foto_kilometraje']['name'];
            $imagenRuta = $ruta . $nombreArchivo;
            createDirectoryIfNotExists($ruta);
            if (file_exists($imagenRuta)) {
                showErrorOrSuccessAndRedirect("error", "Error de archivo", "El nombre de la imagen ya esta registrado", "vehiculo_compactador.php");
                exit();
            }
            $registroFoto = moveUploadedFile($_FILES['foto_kilometraje'], $imagenRuta);
            if ($registroFoto) {
                // OBTENEMOS LA FECHA ACTUAL 
                $fecha_registro = date('Y-m-d H:i:s');
                // Inserta los datos en la base de datos, incluyendo la edad
                $registerTruckCompacter = $connection->prepare("INSERT INTO registro_actividades (fecha_inicio, hora_inicio, km_inicio, foto_kilometraje, horometro_inicio, id_vehiculos, id_labor, documento, id_estado, fecha_registro) VALUES(:fecha_inicio, :hora_inicio, :km_inicio, :foto_kilometraje, :horometro_inicio, :id_vehiculos, :id_labor, :documento, :id_estado, :fecha_registro)");
                // Vincular los parámetros
                $registerTruckCompacter->bindParam(':fecha_inicio', $fecha_inicio);
                $registerTruckCompacter->bindParam(':hora_inicio', $hora_inicio);
                $registerTruckCompacter->bindParam(':km_inicio', $kilometraje);
                $registerTruckCompacter->bindParam(':foto_kilometraje', $nombreArchivo);
                $registerTruckCompacter->bindParam(':horometro_inicio', $horometro);
                $registerTruckCompacter->bindParam(':id_vehiculos', $vehiculo);
                $registerTruckCompacter->bindParam(':id_labor', $labor);
                $registerTruckCompacter->bindParam(':documento', $documento);
                $registerTruckCompacter->bindParam(':id_estado', $pendiente);
                $registerTruckCompacter->bindParam(':fecha_registro', $fecha_registro);
                $registerTruckCompacter->execute();
                if ($registerTruckCompacter) {
                    // Capturamos el ID del último registro insertado
                    $idRegister = $connection->lastInsertId();  // Cambiado a lastInsertId()
                    // Insertar el arreglo de IDs en otra tabla relacionada
                    $insertarDetalle = $connection->prepare("INSERT INTO detalle_tripulacion(documento, id_registro) VALUES(:documento, :id_registro)");
                    foreach ($empleados as $empleado) {
                        $insertarDetalle->bindParam(':documento', $empleado['id']);
                        $insertarDetalle->bindParam(':id_registro', $idRegister);
                        $insertarDetalle->execute();
                    }
                    showErrorOrSuccessAndRedirect("success", "Formulario Registrado", "Se ha registrado la etapa inicial del formulario, debes terminar de rellenar la información restante en el panel de formularios pendientes", "index.php");
                    exit();
                } else {
                    showErrorOrSuccessAndRedirect("error", "Error de Registro", "Error al momento de registrar los datos, por favor inténtalo nuevamente.", "vehiculo_compactador.php");
                    exit();
                }
            } else {
                showErrorOrSuccessAndRedirect("error", "Error de Registro", "Error al momento de registrar los datos, error al momento de registrar la imagen.", "vehiculo_compactador.php");
                exit();
            }
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de archivo", "El archivo no es válido, debe ser de tipo PNG o JPEG, y no debe superar el tamaño permitido que son 10 MB.", "vehiculo_compactador.php");
            exit();
        }
    } else {
        showErrorOrSuccessAndRedirect("error", "Error de archivo", "Error al subir el archivo", "vehiculo_compactador.php");
        exit();
    }
}