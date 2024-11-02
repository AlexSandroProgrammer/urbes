<?php

function enviarRecollection($datos) {
    $url = 'https://script.google.com/macros/s/AKfycbyez1TKXm1PieVwJd0-GxwrLpNTJ9ckXDIvkjaJCCRMOZqPwvEngvbjFpKQqRaLwXOFIQ/exec'; // Reemplaza con la URL de tu aplicación web de Google Apps Script

    $opciones = [
        'http' => [
            'header'  => "Content-type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($datos),
        ],
    ];
    $contexto  = stream_context_create($opciones);
    $resultado = file_get_contents($url, false, $contexto);
    
    if ($resultado === FALSE) {
        // Manejar el error
        echo "Error al enviar datos a Google Sheets.";
    }

    return $resultado;
}
?>
<?php
//* Método para registrar vehículo compactador
if ((isset($_POST["MM_formRegisterVehicleCompacter"])) && ($_POST["MM_formRegisterVehicleCompacter"] == "formRegisterVehicleCompacter")) {

    // Variables de asignación de valores recibidos desde el formulario de registro de vehículo compactador
    $fecha_inicio = $_POST['fecha_inicio'];
    $vehiculo = $_POST['vehiculo'];
    $documento = $_POST['documento'];
    $labor = 4;
    $hora_inicio = $_POST['hora_inicio'];
    $kilometraje = isset($_POST['kilometraje']) && $_POST['kilometraje'] !== '' ? $_POST['kilometraje'] : null;
    $horometro = $_POST['horometro'];
    $ciudad = $_POST['ciudad'];
    $empleados = json_decode($_POST['empleados'], true);
    $rutas = isset($_POST['rutas']) ? $_POST['rutas'] : [];

    // Variables para la imagen
    $foto_kilometraje = null;
    $imagenRuta = null;

    // Validación de campos requeridos
    if (isEmpty([
        $fecha_inicio,
        $vehiculo,
        $documento,
        $labor,
        $hora_inicio,
        $horometro,
        $ciudad,
        $rutas,
    ])) {
        showErrorFieldsEmpty("vehiculo_compactador.php");
        exit();
    }

    //* Validación de imagen (opcional)
    if (isset($_FILES['foto_kilometraje']) && is_uploaded_file($_FILES['foto_kilometraje']['tmp_name'])) {
        try {
            // Tipos de archivos permitidos y límite de tamaño
            $permitidos = array('image/jpeg', 'image/png', 'image/jpg');
            $limite_KB = 5000; // 5MB

            // Verificar tipo y tamaño del archivo
            if (!in_array($_FILES['foto_kilometraje']['type'], $permitidos)) {
                showErrorOrSuccessAndRedirect("error", "Error de archivo", "Formato de imagen no permitido. Sólo se aceptan formatos JPG, JPEG y PNG.", "vehiculo_compactador.php");
                exit();
            }

            if ($_FILES['foto_kilometraje']['size'] > ($limite_KB * 1024)) {
                showErrorOrSuccessAndRedirect("error", "Error de archivo", "El tamaño de la imagen supera los 5MB permitidos.", "vehiculo_compactador.php");
                exit();
            }

            // Directorio de destino para la imagen
            $ruta = "../assets/images/";
            $extension = pathinfo($_FILES['foto_kilometraje']['name'], PATHINFO_EXTENSION);
            $nombreArchivo = uniqid() . '.' . $extension; 
            $imagenRuta = $ruta . $nombreArchivo;

            // Crear directorio si no existe
            createDirectoryIfNotExists($ruta);

            // Verificar si el archivo ya existe y mover el archivo
            if (file_exists($imagenRuta)) {
                showErrorOrSuccessAndRedirect("error", "Error de archivo", "El nombre de la imagen ya está registrado", "vehiculo_compactador.php");
                exit();
            }

            if (!moveUploadedFile($_FILES['foto_kilometraje'], $imagenRuta)) {
                showErrorOrSuccessAndRedirect("error", "Error de archivo", "No se pudo guardar la imagen.", "vehiculo_compactador.php");
                exit();
            }

            // Asignar el nombre de la imagen a la variable
            $foto_kilometraje = $nombreArchivo;

        } catch (\Throwable $th) {
            showErrorOrSuccessAndRedirect("error", "Error de Registro", "Error al procesar la imagen. Inténtalo nuevamente.", "vehiculo_compactador.php");
            exit();
        }
    }

    // Variables adicionales y registro en la base de datos
    $fecha_registro = date('Y-m-d H:i:s');
    $pendiente = 4;

    // Insertar datos en la tabla vehiculo_compactador
    $registerTruckCompacter = $connection->prepare("
        INSERT INTO vehiculo_compactador 
        (fecha_inicio, hora_inicio, km_inicio, ciudad, foto_kilometraje_inicial, horometro_inicio, id_vehiculo, id_labor, documento, id_estado, fecha_registro)
        VALUES (:fecha_inicio, :hora_inicio, :km_inicio, :ciudad, :foto_kilometraje, :horometro_inicio, :id_vehiculo, :id_labor, :documento, :id_estado, :fecha_registro)
    ");

    // Vincular los parámetros
    $registerTruckCompacter->bindParam(':fecha_inicio', $fecha_inicio);
    $registerTruckCompacter->bindParam(':hora_inicio', $hora_inicio);
    $registerTruckCompacter->bindParam(':km_inicio', $kilometraje);
    $registerTruckCompacter->bindParam(':ciudad', $ciudad);
    $registerTruckCompacter->bindParam(':foto_kilometraje', $foto_kilometraje);
    $registerTruckCompacter->bindParam(':horometro_inicio', $horometro);
    $registerTruckCompacter->bindParam(':id_vehiculo', $vehiculo);
    $registerTruckCompacter->bindParam(':id_labor', $labor);
    $registerTruckCompacter->bindParam(':documento', $documento);
    $registerTruckCompacter->bindParam(':id_estado', $pendiente);
    $registerTruckCompacter->bindParam(':fecha_registro', $fecha_registro);
    $registerTruckCompacter->execute();

    if ($registerTruckCompacter) {
        $idRegister = $connection->lastInsertId();

        //* Registrar empleados asociados
        $insertarDetalle = $connection->prepare("INSERT INTO detalle_tripulacion(documento, id_registro) VALUES(:documento, :id_registro)");
        foreach ($empleados as $empleado) {
            $insertarDetalle->bindParam(':documento', $empleado['id']);
            $insertarDetalle->bindParam(':id_registro', $idRegister);
            $insertarDetalle->execute();
        }

        //* Registrar rutas asociadas
        $insertarDetail = $connection->prepare("INSERT INTO detalle_rutas(id_ruta, id_registro) VALUES(:id_ruta, :id_registro)");
        foreach ($rutas as $rutaId) {
            $insertarDetail->bindParam(':id_ruta', $rutaId);
            $insertarDetail->bindParam(':id_registro', $idRegister);
            $insertarDetail->execute();
        }

        //* Obtener datos del registro insertado para generar respuesta
        $query = "
            SELECT vehiculo_compactador.*, labores.labor, vehiculos.placa, usuarios.nombres, usuarios.apellidos, estados.estado, ciudades.ciudad
            FROM vehiculo_compactador
            INNER JOIN labores ON vehiculo_compactador.id_labor = labores.id_labor
            INNER JOIN vehiculos ON vehiculo_compactador.id_vehiculo = vehiculos.placa
            INNER JOIN usuarios ON vehiculo_compactador.documento = usuarios.documento
            INNER JOIN ciudades ON vehiculo_compactador.ciudad = ciudades.id_ciudad
            INNER JOIN estados ON vehiculo_compactador.id_estado = estados.id_estado
            WHERE vehiculo_compactador.id_registro_veh_compactador = :id_registro_veh_compact";
        $execute = $connection->prepare($query);
        $execute->bindParam(":id_registro_veh_compact", $idRegister);
        $execute->execute();
        $data = $execute->fetch(PDO::FETCH_ASSOC);

        //* Obtener tripulación
        $queryTripulacion = $connection->prepare("
            SELECT usuarios.documento, usuarios.nombres, usuarios.apellidos
            FROM detalle_tripulacion
            INNER JOIN usuarios ON detalle_tripulacion.documento = usuarios.documento
            WHERE detalle_tripulacion.id_registro = :id_registro
        ");
        $queryTripulacion->bindParam(":id_registro", $idRegister);
        $queryTripulacion->execute();
        $tripuResult = $queryTripulacion->fetchAll(PDO::FETCH_ASSOC);

        // Concatenar tripulación
        $tripuConcatenadas = [];
        foreach ($tripuResult as $row) {
            $tripuConcatenadas[] = $row['documento'] . " (" . $row['nombres'] .  " " . $row['apellidos'] . ")";
        }
        $registradorConcatenado = $_POST['documento'] . " (" . $data['nombres'] . " " . $data['apellidos'] . ")";

        //* Obtener rutas
        $queryZonas = $connection->prepare("
            SELECT rutasr.ruta
            FROM detalle_rutas
            INNER JOIN rutasr ON detalle_rutas.id_ruta = rutasr.id_ruta
            WHERE detalle_rutas.id_registro = :id_registro
        ");
        $queryZonas->bindParam(":id_registro", $idRegister);
        $queryZonas->execute();
        $rutasResult = $queryZonas->fetchAll(PDO::FETCH_COLUMN);
        $rutasConcatenadas = count($rutasResult) > 1 ? implode(", ", $rutasResult) : $rutasResult[0];


        // Preparar los datos para enviar a Google Sheets
        $datos = [
            'id_registro' => $idRegister,
            'fecha_inicio' => $fecha_inicio,
            'hora_inicio' => $hora_inicio,
            'documento' => $registradorConcatenado,
            'placa' => $vehiculo,
            'kilometroje_inicial' => $kilometraje ?? 'No se registró Kilometraje',
            'horometro_inicial' => $horometro,
            'labor' => $data['labor'],
            'id_estado' => $data['estado'],
            'fecha_registro' => $fecha_registro,
            'ciudad' => $data['ciudad'],
            'tripulacion' => implode(", ", $tripuConcatenadas),
            'imagen_km_inicial' => $rutasConcatenadas,
            'tipo_operacion' => 'registro_inicial'
        ];

        // Enviar datos a Google Sheets
        enviarRecollection($datos);

        // Redirigir con mensaje de éxito
        showErrorOrSuccessAndRedirect("success", "Registro Exitoso", "El formulario fue  registrado correctamente.", "index.php");
    } else {
        // Redirigir con mensaje de error
        showErrorOrSuccessAndRedirect("error", "Error de Registro", "No se pudo registrar la Informacion.", "vehiculo_compactador.php");
    }
}
?>


<?php

if ((isset($_POST["MM_formUpdateRecoleccion"])) && ($_POST["MM_formUpdateRecoleccion"] == "formUpdateRecoleccion")) {
    $id_registro_veh_compactador = $_POST['id_registro_veh_compactador'];
    $fecha_final = $_POST['fecha_final'];
    $hora_finalizacion = $_POST['hora_finalizacion'];
    $kilometraje_final = isset($_POST['kilometraje_final']) && $_POST['kilometraje_final'] !== '' ? $_POST['kilometraje_final'] : null;
    $horometro_final = $_POST['horometro_final'];
    $observaciones = $_POST['observaciones'];

    // Query para obtener el kilometraje y horómetro inicial desde la base de datos
    $queryInicial = $connection->prepare("SELECT km_inicio, horometro_inicio FROM vehiculo_compactador WHERE id_registro_veh_compactador = :id_registro");
    $queryInicial->bindParam(':id_registro', $id_registro_veh_compactador);
    $queryInicial->execute();
    $registroInicial = $queryInicial->fetch(PDO::FETCH_ASSOC);

    $km_inicio = floatval($registroInicial['km_inicio']); // Convertir a número
    $horometro_inicio = floatval($registroInicial['horometro_inicio']); 

    // Validación para asegurar que el kilometraje final y el horómetro final no sean menores o iguales al inicial
    if ($kilometraje_final < $km_inicio || $horometro_final <= $horometro_inicio) {
        showErrorOrSuccessAndRedirect("error", "Error en los valores", "El kilometraje o el horómetro final no pueden ser menores o iguales al valor inicial", "pendientes.php");
        exit();
    }

    // Validación de campos vacíos
    if (isEmpty([$fecha_final, $hora_finalizacion, $horometro_final])) {
        showErrorFieldsEmpty("pendientes.php");
        exit();
    }

    // Procesamiento de imagen si fue subida
    if (isFileUploaded($_FILES['foto_kilometraje_final'])) {
        try {
            $permitidos = array('image/jpeg', 'image/png', 'image/jpg');
            $limite_KB = 5000;

            if (isFileValid($_FILES['foto_kilometraje_final'], $permitidos, $limite_KB)) {
                $ruta = "../assets/images/";
                $extension = pathinfo($_FILES['foto_kilometraje_final']['name'], PATHINFO_EXTENSION);
                $nombreArchivo = uniqid() . '.' . $extension; 
                $imagenRuta = $ruta . $nombreArchivo;

                createDirectoryIfNotExists($ruta);

                if (file_exists($imagenRuta)) {
                    showErrorOrSuccessAndRedirect("error", "Error de archivo", "El nombre de la imagen ya está registrado", "pendientes.php");
                    exit();
                }

                $registroFotoFinal = moveUploadedFile($_FILES['foto_kilometraje_final'], $imagenRuta);

                if (!$registroFotoFinal) {
                    showErrorOrSuccessAndRedirect("error", "Error de archivo", "No se pudo mover la imagen", "pendientes.php");
                    exit();
                }

                $foto_kilometraje_final = $nombreArchivo; // Actualizamos el nombre de la imagen
            } else {
                showErrorOrSuccessAndRedirect("error", "Error de archivo", "Formato o tamaño de imagen no válido", "pendientes.php");
                exit();
            }

        } catch (\Throwable $th) {
            showErrorOrSuccessAndRedirect("error", "Error de archivo", "Error al procesar la imagen", "pendientes.php");
            exit();
        }
    } else {
        // Si no se subió una imagen, se asigna null
        $foto_kilometraje_final = null;
    }

    // Actualizar los datos
    try {
        $fecha_actualizacion = date('Y-m-d H:i:s');
        $finalizado = 5;

        $updateRegister = $connection->prepare("UPDATE vehiculo_compactador SET fecha_fin = :fecha_final, fecha_actualizacion = :fecha_actualizacion, hora_finalizacion = :hora_finalizacion, foto_kilometraje_final = :foto_kilometraje_final, km_fin = :km_fin, horometro_fin = :horometro_final, id_estado = :id_estado, observaciones = :observaciones WHERE id_registro_veh_compactador = :id_registro_veh_compactador");

        $updateRegister->bindParam(':fecha_final', $fecha_final);
        $updateRegister->bindParam(':fecha_actualizacion', $fecha_actualizacion);
        $updateRegister->bindParam(':hora_finalizacion', $hora_finalizacion);
        $updateRegister->bindParam(':foto_kilometraje_final', $foto_kilometraje_final);
        $updateRegister->bindParam(':km_fin', $kilometraje_final);
        $updateRegister->bindParam(':horometro_final', $horometro_final);
        $updateRegister->bindParam(':id_estado', $finalizado);
        $updateRegister->bindParam(':observaciones', $observaciones);
        $updateRegister->bindParam(':id_registro_veh_compactador', $id_registro_veh_compactador);
        $updateRegister->execute();

        if ($updateRegister) {
            // Consulta para actualizar Google Sheets
            $querySheets = $connection->prepare("SELECT vehiculo_compactador.*, estados.estado
                FROM vehiculo_compactador
                INNER JOIN estados ON vehiculo_compactador.id_estado = estados.id_estado
                WHERE id_registro_veh_compactador = :id_registro");
            $querySheets->bindParam(":id_registro", $id_registro_veh_compactador);
            $querySheets->execute();
            $sheets = $querySheets->fetch(PDO::FETCH_ASSOC);

            $datos = [
                'id_registro' => $id_registro_veh_compactador,
                'fecha_fin' => $fecha_final,
                'hora_fin' => $hora_finalizacion,
                'kilometraje_final'=> $kilometraje_final ?? 'no se registró kilometraje final',
                'horometro_final'=> $horometro_final,
                'observaciones' => $observaciones,
                'id_estado' => $sheets['estado'],
                'fecha_actualizacion' => $fecha_actualizacion,
                'imagen_km_final' => $foto_kilometraje_final ? $imagenRuta : 'no se registró imagen',
                'tipo_operacion' => 'actualizacion'
            ];

            // Enviar a Google Sheets
            enviarRecollection($datos);

            showErrorOrSuccessAndRedirect("success", "Actualización Exitosa", "Los datos se han actualizado correctamente", "index.php");
            exit();
        } else {
            showErrorOrSuccessAndRedirect("error", "Error de Actualización", "Error al actualizar los datos", "pendientes.php");
            exit();
        }

    } catch (\Throwable $th) {
        showErrorOrSuccessAndRedirect("error", "Error de Actualización", "Ocurrió un error, intenta nuevamente", "pendientes.php");
        exit();
    }
}

?>

<?php
function enviarRelleno($datos) {
    $url = 'https://script.google.com/macros/s/AKfycbwaygVSXfhySACMdPOeFaG0qjf671OG79H2xAI8-Om2y9LN70ttPjQS5KjSlXc3vPdiQQ/exec'; // Reemplaza con la URL de tu aplicación web de Google Apps Script

    $opciones = [
        'http' => [
            'header'  => "Content-type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($datos),
        ],
    ];
    $contexto  = stream_context_create($opciones);
    $resultado = file_get_contents($url, false, $contexto);
    
    if ($resultado === FALSE) {
        // Manejar el error
        echo "Error al enviar datos a Google Sheets.";
    }

    return $resultado;
}
?>


<?php
//* Registro de datos de registro de actividades
if ((isset($_POST["MM_formRegisterRecoleccion"])) && ($_POST["MM_formRegisterRecoleccion"] == "formRegisterRecoleccion")) {
    // VARIABLES DE ASIGNACIÓN DE VALORES QUE SE ENVÍAN DESDE EL FORMULARIO DE REGISTRO DE VEHICULO COMPACTADOR
    $fecha_inicio = $_POST['fecha_inicio'];
    $vehiculo = $_POST['vehiculo'];
    $documento = $_POST['documento'];
    $labor = 5;
    $hora_inicio = $_POST['hora_inicio'];
    $kilometraje = isset($_POST['kilometraje']) && $_POST['kilometraje'] !== '' ? $_POST['kilometraje'] : null;
    $horometro = $_POST['horometro'];
    $ciudad = $_POST['ciudad'];
    
    // Variables para la imagen
    $foto_kilometraje = null;
    $imagenRuta = null;

    // Validamos que no se haya recibido ningún dato vacío
    if (isEmpty([
        $fecha_inicio,
        $vehiculo,
        $documento,
        $labor,
        $hora_inicio,
        $horometro,
        $ciudad,
    ])) {
        showErrorFieldsEmpty("recoleccion_relleno.php");
        exit();
    }

    //* Validamos si se ha subido una imagen
    if (isset($_FILES['foto_kilometraje']) && is_uploaded_file($_FILES['foto_kilometraje']['tmp_name'])) {
        try {
            // Tipos de archivos permitidos
            $permitidos = array('image/jpeg', 'image/png', 'image/jpg');
            $limite_KB = 5000; // Límite de 5MB

            // Verificar tipo de archivo
            if (!in_array($_FILES['foto_kilometraje']['type'], $permitidos)) {
                showErrorOrSuccessAndRedirect("error", "Error de archivo", "Formato de imagen no permitido. Sólo se aceptan formatos JPG, JPEG y PNG.", "recoleccion_relleno.php");
                exit();
            }

            // Verificar tamaño de archivo
            if ($_FILES['foto_kilometraje']['size'] > ($limite_KB * 1024)) {
                showErrorOrSuccessAndRedirect("error", "Error de archivo", "El tamaño de la imagen supera los 5MB permitidos.", "recoleccion_relleno.php");
                exit();
            }

            // Directorio de destino para la imagen
            $ruta = "../assets/images/";
            $extension = pathinfo($_FILES['foto_kilometraje']['name'], PATHINFO_EXTENSION);
            $nombreArchivo = uniqid() . '.' . $extension; 
            $imagenRuta = $ruta . $nombreArchivo;

            // Crear el directorio si no existe
            createDirectoryIfNotExists($ruta);

            // Verificar si el archivo ya existe
            if (file_exists($imagenRuta)) {
                showErrorOrSuccessAndRedirect("error", "Error de archivo", "El nombre de la imagen ya está registrado", "recoleccion_relleno.php");
                exit();
            }

            // Mover el archivo subido
            $registroFoto = moveUploadedFile($_FILES['foto_kilometraje'], $imagenRuta);
            if (!$registroFoto) {
                showErrorOrSuccessAndRedirect("error", "Error de archivo", "No se pudo guardar la imagen.", "recoleccion_relleno.php");
                exit();
            }

            // Asignar el nombre de la imagen a la variable
            $foto_kilometraje = $nombreArchivo;

        } catch (\Throwable $th) {
            // Redirigir con mensaje de error
            showErrorOrSuccessAndRedirect("error", "Error de Registro", "Error al procesar la imagen. Inténtalo nuevamente.", "recoleccion_relleno.php");
            exit();
        }
    }

    // OBTENEMOS LA FECHA ACTUAL 
    $fecha_registro = date('Y-m-d H:i:s');
    $pendiente = 4;
    
    // Inserta los datos en la base de datos, incluyendo la imagen si existe
    $registerRecoleccionRelleno = $connection->prepare("INSERT INTO recoleccion_relleno (fecha_inicio, hora_inicio, km_inicio, ciudad, foto_kilometraje_inicial, horometro_inicio, id_vehiculo, id_labor, documento, id_estado, fecha_registro) VALUES(:fecha_inicio, :hora_inicio, :km_inicio, :ciudad, :foto_kilometraje, :horometro_inicio, :id_vehiculo, :id_labor, :documento, :id_estado, :fecha_registro)");

    // Vincular los parámetros
    $registerRecoleccionRelleno->bindParam(':fecha_inicio', $fecha_inicio);
    $registerRecoleccionRelleno->bindParam(':hora_inicio', $hora_inicio);
    $registerRecoleccionRelleno->bindParam(':km_inicio', $kilometraje);
    $registerRecoleccionRelleno->bindParam(':ciudad', $ciudad);
    $registerRecoleccionRelleno->bindParam(':foto_kilometraje', $foto_kilometraje);
    $registerRecoleccionRelleno->bindParam(':horometro_inicio', $horometro);
    $registerRecoleccionRelleno->bindParam(':id_vehiculo', $vehiculo);
    $registerRecoleccionRelleno->bindParam(':id_labor', $labor);
    $registerRecoleccionRelleno->bindParam(':documento', $documento);
    $registerRecoleccionRelleno->bindParam(':id_estado', $pendiente);
    $registerRecoleccionRelleno->bindParam(':fecha_registro', $fecha_registro);
    $registerRecoleccionRelleno->execute();

    if ($registerRecoleccionRelleno) {
        $idRegister = $connection->lastInsertId();

        // Consulta para obtener datos necesarios de la tabla vehiculo_compactador
        $query = "SELECT  recoleccion_relleno.*, labores.labor, vehiculos.placa, usuarios.documento,usuarios.nombres,usuarios.apellidos, estados.estado, ciudades.ciudad
                  FROM recoleccion_relleno
                  INNER JOIN labores ON recoleccion_relleno.id_labor = labores.id_labor
                  INNER JOIN vehiculos ON recoleccion_relleno.id_vehiculo = vehiculos.placa
                  INNER JOIN usuarios ON recoleccion_relleno.documento = usuarios.documento
                  INNER JOIN ciudades ON recoleccion_relleno.ciudad = ciudades.id_ciudad
                  INNER JOIN estados ON recoleccion_relleno.id_estado = estados.id_estado
                  WHERE recoleccion_relleno.id_recoleccion = :id_recoleccion";
        $execute = $connection->prepare($query);
        $execute->bindParam(":id_recoleccion", $idRegister);
        $execute->execute();
        $data = $execute->fetch(PDO::FETCH_ASSOC);


       $registradorConcatenado = $_POST['documento'] . " (" . $data['nombres'] . " " . $data['apellidos'] . ")";

        // Preparar los datos para enviar a Google Sheets
        $datos = [
            'id_registro' => $idRegister,
            'fecha_inicio' => $fecha_inicio,
            'hora_inicio' => $hora_inicio,
            'documento' => $registradorConcatenado,
            'placa' => $vehiculo,
            'kilometroje_inicial' => $kilometraje ?? 'No se registró Kilometraje',
            'horometro_inicial' => $horometro,
            'labor' => $data['labor'],
            'id_estado' => $data['estado'],
            'fecha_registro' => $fecha_registro,
            'ciudad' => $data['ciudad'],
            'imagen_km_inicial' => $imagenRuta ?? 'No se registró Imagen', 
            'tipo_operacion' => 'registro_inicial'
        ];

        // Enviar datos a Google Sheets
        enviarRelleno($datos);

        showErrorOrSuccessAndRedirect("success", "Formulario Registrado", "Se ha registrado la etapa inicial del formulario.", "index.php");
    } else {
        showErrorOrSuccessAndRedirect("error", "Error de Registro", "No se pudo registrar la Información.", "recoleccion_relleno.php");
    }
}
?>

<?php

if ((isset($_POST["MM_formUpdateDisposicion"])) && ($_POST["MM_formUpdateDisposicion"] == "formUpdateDisposicion")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE ACTIVIDAD
    $id_recoleccion = $_POST['id_recoleccion'];
    $fecha_final = $_POST['fecha_final'];
    $hora_finalizacion = $_POST['hora_finalizacion'];
    $kilometraje_final = isset($_POST['kilometraje_final']) && $_POST['kilometraje_final'] !== '' ? $_POST['kilometraje_final'] : null;
    $horometro_final = $_POST['horometro_final'];
    $observaciones = $_POST['observaciones'];
    $toneladas = $_POST['toneladas'];
    $galones = $_POST['galones'];

    // Consulta para obtener los valores iniciales
    $queryInicial = $connection->prepare("SELECT km_inicio, horometro_inicio FROM recoleccion_relleno WHERE id_recoleccion = :id_recoleccion");
    $queryInicial->bindParam(':id_recoleccion', $id_recoleccion);
    $queryInicial->execute();
    $registroInicial = $queryInicial->fetch(PDO::FETCH_ASSOC);

    $km_inicio = floatval($registroInicial['km_inicio']); // Convertir a número
    $horometro_inicio = floatval($registroInicial['horometro_inicio']); 

    // Validación para asegurar que el kilometraje final y el horómetro final no sean menores o iguales al inicial
    if ($kilometraje_final < $km_inicio || $horometro_final <= $horometro_inicio) {
        showErrorOrSuccessAndRedirect("error", "Error en los valores", "El kilometraje o el horómetro final no pueden ser menores o iguales al valor inicial", "pendientes.php");
        exit();
    }

    // Validación para asegurar que no haya datos vacíos
    if (empty($fecha_final) || empty($hora_finalizacion) || empty($horometro_final) || empty($toneladas) || empty($galones)) {
        showErrorOrSuccessAndRedirect("error", "Campos Vacíos", "Uno o más campos obligatorios están vacíos", "pendientes.php");
        exit();
    }

    // Procesar las imágenes si se han subido
    $foto_kilometraje_final = null;
    $foto_toneladas = null;
    $foto_galones = null;
    $imagenRuta = null;

    // Función para manejar la subida de imágenes
    function handleImageUpload($fileInputName, $ruta, &$fotoVariable) {
        if (isset($_FILES[$fileInputName]) && is_uploaded_file($_FILES[$fileInputName]['tmp_name'])) {
            try {
                $permitidos = array('image/jpeg', 'image/png', 'image/jpg');
                $limite_KB = 5000; // Límite de 5MB

                // Verificar tipo de archivo
                if (!in_array($_FILES[$fileInputName]['type'], $permitidos)) {
                    showErrorOrSuccessAndRedirect("error", "Error de archivo", "Formato de imagen no permitido. Sólo se aceptan formatos JPG, JPEG y PNG.", "pendientes.php");
                    exit();
                }

                // Verificar tamaño de archivo
                if ($_FILES[$fileInputName]['size'] > ($limite_KB * 1024)) {
                    showErrorOrSuccessAndRedirect("error", "Error de archivo", "El tamaño de la imagen supera los 5MB permitidos.", "pendientes.php");
                    exit();
                }

                // Nombre del archivo
                $extension = pathinfo($_FILES[$fileInputName]['name'], PATHINFO_EXTENSION);
                $nombreArchivo = uniqid() . '.' . $extension; 
                $imagenRuta = $ruta . $nombreArchivo;

                // Crear el directorio si no existe
                createDirectoryIfNotExists($ruta);

                // Verificar si el archivo ya existe
                if (file_exists($imagenRuta)) {
                    showErrorOrSuccessAndRedirect("error", "Error de archivo", "El nombre de la imagen ya está registrado", "pendientes.php");
                    exit();
                }

                // Mover el archivo subido
                $registroFotoFinal = moveUploadedFile($_FILES[$fileInputName], $imagenRuta);
                if (!$registroFotoFinal) {
                    showErrorOrSuccessAndRedirect("error", "Error de archivo", "No se pudo guardar la imagen.", "pendientes.php");
                    exit();
                }

                // Asignar el nombre de la imagen a la variable
                $fotoVariable = $nombreArchivo;

            } catch (\Throwable $th) {
                // Redirigir con mensaje de error
                showErrorOrSuccessAndRedirect("error", "Error de Registro", "Error al procesar la imagen. Inténtalo nuevamente.", "pendientes.php");
                exit();
            }
        }
    }

    // Manejar la subida de imágenes
    $ruta = "../assets/images/";
    handleImageUpload('foto_kilometraje_final', $ruta, $foto_kilometraje_final);
    handleImageUpload('foto_toneladas', $ruta, $foto_toneladas);
    handleImageUpload('foto_galones', $ruta, $foto_galones);

    // OBTENEMOS LA FECHA ACTUAL 
    $fecha_actualizacion = date('Y-m-d H:i:s');
    $finalizado = 5;

    // Actualizamos los datos en la base de datos
    $updateRegister = $connection->prepare("UPDATE recoleccion_relleno SET fecha_fin = :fecha_final, fecha_actualizacion = :fecha_actualizacion, hora_finalizacion = :hora_finalizacion, foto_kilometraje_final = :foto_kilometraje_final, foto_tonelada = :foto_toneladas, foto_galones = :foto_galones, km_fin = :km_fin, horometro_fin = :horometro_final, id_estado = :id_estado, observaciones = :observaciones, toneladas = :toneladas, galones = :galones WHERE id_recoleccion = :id_recoleccion");

    $updateRegister->bindParam(':fecha_final', $fecha_final);
    $updateRegister->bindParam(':fecha_actualizacion', $fecha_actualizacion);
    $updateRegister->bindParam(':hora_finalizacion', $hora_finalizacion);
    $updateRegister->bindParam(':foto_kilometraje_final', $foto_kilometraje_final);
    $updateRegister->bindParam(':foto_toneladas', $foto_toneladas);
    $updateRegister->bindParam(':foto_galones', $foto_galones);
    $updateRegister->bindParam(':km_fin', $kilometraje_final);
    $updateRegister->bindParam(':horometro_final', $horometro_final);
    $updateRegister->bindParam(':id_estado', $finalizado);
    $updateRegister->bindParam(':observaciones', $observaciones);
    $updateRegister->bindParam(':toneladas', $toneladas);
    $updateRegister->bindParam(':galones', $galones);
    $updateRegister->bindParam(':id_recoleccion', $id_recoleccion);
    $updateRegister->execute();

    if ($updateRegister) {
        $querySheets = $connection->prepare("SELECT recoleccion_relleno.*, estados.estado
            FROM recoleccion_relleno
            INNER JOIN estados ON recoleccion_relleno.id_estado = estados.id_estado
            WHERE id_recoleccion = :id_registro");
        $querySheets->bindParam(":id_registro", $id_recoleccion);
        $querySheets->execute();
        $sheets = $querySheets->fetch(PDO::FETCH_ASSOC);

        $datos = [
            'id_registro' => $id_recoleccion,
            'fecha_fin' => $fecha_final,
            'hora_fin' => $hora_finalizacion,
            'kilometraje_final' => $kilometraje_final ?? 'No se registró Kilometraje',
            'horometro_final' => $horometro_final,
            'observaciones' => $observaciones,
            'id_estado' => $sheets['estado'],
            'galones' => $galones,
            'fecha_actualizacion' => $fecha_actualizacion,
            'imagen_km_final' => $toneladas,
            'tipo_operacion' => 'actualizacion'
        ];

        // Envío a Google Sheets
        enviarRelleno($datos);

        showErrorOrSuccessAndRedirect("success", "Actualización Exitosa", "Los datos se han actualizado correctamente", "index.php");
        exit();
    } else {
        showErrorOrSuccessAndRedirect("error", "Error de Actualización", "Error al momento de actualizar los datos, por favor intentalo nuevamente", "pendientes.php");
        exit();
    }
}

?>