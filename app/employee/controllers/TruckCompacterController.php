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
    $foto_kilometraje = $_FILES['foto_kilometraje']['name'];
    $kilometraje = $_POST['kilometraje'];
    $horometro = $_POST['horometro'];
    $ciudad = $_POST['ciudad'];
    $empleados = json_decode($_POST['empleados'], true);

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

    //* Validamos que el archivo de foto sea válido
    try {
        if (isFileUploaded($_FILES['foto_kilometraje'])) {
            $permitidos = array(
                'image/jpeg',
                'image/png',
                'image/jpg',
            );
            $limite_KB = 5000;
            if (isFileValid($_FILES['foto_kilometraje'], $permitidos, $limite_KB)) {
                $ruta = "../assets/images/";
                $extension = pathinfo($_FILES['foto_kilometraje']['name'], PATHINFO_EXTENSION);
                $nombreArchivo = uniqid() . '.' . $extension; 
                $imagenRuta = $ruta . $nombreArchivo;
                createDirectoryIfNotExists($ruta);

                // Verificar si el archivo ya existe
                if (file_exists($imagenRuta)) {
                    showErrorOrSuccessAndRedirect("error", "Error de archivo", "El nombre de la imagen ya está registrado", "vehiculo_compactador.php");
                    exit();
                }

                // Mover el archivo subido
                $registroFoto = moveUploadedFile($_FILES['foto_kilometraje'], $imagenRuta);
                if ($registroFoto) {
                    // Obtener la fecha actual
                    $fecha_registro = date('Y-m-d H:i:s');
                    $pendiente = 4;

                    // Insertar los datos en la base de datos
                    $registerTruckCompacter = $connection->prepare("INSERT INTO vehiculo_compactador (fecha_inicio, hora_inicio, km_inicio, ciudad, foto_kilometraje_inicial, horometro_inicio, id_vehiculo, id_labor, documento, id_estado, fecha_registro) VALUES(:fecha_inicio, :hora_inicio, :km_inicio, :ciudad, :foto_kilometraje, :horometro_inicio, :id_vehiculo, :id_labor, :documento, :id_estado, :fecha_registro)");

                    // Vincular los parámetros
                    $registerTruckCompacter->bindParam(':fecha_inicio', $fecha_inicio);
                    $registerTruckCompacter->bindParam(':hora_inicio', $hora_inicio);
                    $registerTruckCompacter->bindParam(':km_inicio', $kilometraje);
                    $registerTruckCompacter->bindParam(':ciudad', $ciudad);
                    $registerTruckCompacter->bindParam(':foto_kilometraje', $nombreArchivo);
                    $registerTruckCompacter->bindParam(':horometro_inicio', $horometro);
                    $registerTruckCompacter->bindParam(':id_vehiculo', $vehiculo);
                    $registerTruckCompacter->bindParam(':id_labor', $labor);
                    $registerTruckCompacter->bindParam(':documento', $documento);
                    $registerTruckCompacter->bindParam(':id_estado', $pendiente);
                    $registerTruckCompacter->bindParam(':fecha_registro', $fecha_registro);
                    $registerTruckCompacter->execute();

                    if ($registerTruckCompacter) {
                        // Capturamos el ID del último registro insertado
                        $idRegister = $connection->lastInsertId();

                        // Insertar los empleados en la tabla relacionada
                        $insertarDetalle = $connection->prepare("INSERT INTO detalle_tripulacion(documento, id_registro) VALUES(:documento, :id_registro)");
                        foreach ($empleados as $empleado) {
                            $insertarDetalle->bindParam(':documento', $empleado['id']);
                            $insertarDetalle->bindParam(':id_registro', $idRegister);
                            $insertarDetalle->execute();
                        }

                        // Consulta para obtener datos necesarios de la tabla vehiculo_compactador
                        $query = "SELECT vehiculo_compactador.*, labores.labor, vehiculos.placa, usuarios.documento, estados.estado, ciudades.ciudad
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

                        // Consulta para obtener la tripulación
                        $queryTripulacion = $connection->prepare("SELECT usuarios.documento
                                                                  FROM detalle_tripulacion
                                                                  INNER JOIN usuarios ON detalle_tripulacion.documento = usuarios.documento
                                                                  WHERE detalle_tripulacion.id_registro = :id_registro");
                        $queryTripulacion->bindParam(":id_registro", $idRegister);
                        $queryTripulacion->execute();

                        // Obtener la tripulación en un array
                        $tripuResult = $queryTripulacion->fetchAll(PDO::FETCH_COLUMN);

                        // Concatenar los documentos si hay más de uno
                        $tripuConcatenadas = count($tripuResult) > 1 ? implode(", ", $tripuResult) : $tripuResult[0];

                        // Preparar los datos para enviar a Google Sheets
                        $datos = [
                            'id_registro' => $idRegister,
                            'fecha_inicio' => $fecha_inicio,
                            'hora_inicio' => $hora_inicio,
                            'documento' => $documento,
                            'placa' => $vehiculo,
                            'kilometroje_inicial' => $kilometraje,
                            'horometro_inicial' => $horometro,
                            'labor' => $data['labor'],
                            'id_estado' => $data['estado'],
                            'fecha_registro' => $fecha_registro,
                            'ciudad' => $data['ciudad'],
                            'tripulacion' => $tripuConcatenadas,
                            'imagen_km_inicial' => $imagenRuta, // Tripulación concatenada
                            'tipo_operacion' => 'registro_inicial'
                        ];

                        // Enviar datos a Google Sheets
                        enviarRecollection($datos);

                        // Redirigir con mensaje de éxito
                        showErrorOrSuccessAndRedirect("success", "Formulario Registrado", "Se ha registrado la etapa inicial del formulario. Debes terminar de rellenar la información restante en el panel de formularios pendientes.", "index.php");
                        exit();
                    }
                }
            }
        }
    } catch (\Throwable $th) {
        // Redirigir con mensaje de error
        showErrorOrSuccessAndRedirect("error", "Error de Registro", "Error al momento de registrar los datos, por favor inténtalo nuevamente.", "vehiculo_compactador.php");
        exit();
    }
}
?>

<?php

if ((isset($_POST["MM_formUpdateRecoleccion"])) && ($_POST["MM_formUpdateRecoleccion"] == "formUpdateRecoleccion")) {
    $id_registro_veh_compactador = $_POST['id_registro_veh_compactador'];
    $fecha_final = $_POST['fecha_final'];
    $hora_finalizacion = $_POST['hora_finalizacion'];
    $foto_kilometraje_final = $_FILES['foto_kilometraje_final']['name'];
    $kilometraje_final = $_POST['kilometraje_final'];
    $horometro_final = $_POST['horometro_final'];
    $observaciones = $_POST['observaciones'];

    if (isEmpty([$fecha_final, $hora_finalizacion, $horometro_final])) {
        showErrorFieldsEmpty("pendientes.php");
        exit();
    } else {
        try {
            if (isFileUploaded($_FILES['foto_kilometraje_final'])) {
                $permitidos = array('image/jpeg', 'image/png', 'image/jpg');
                $limite_KB = 5000;

                if (isFileValid($_FILES['foto_kilometraje_final'], $permitidos, $limite_KB)) {
                    $ruta = "../assets/images/";
                    $extension = pathinfo($_FILES['foto_kilometraje_final']['name'], PATHINFO_EXTENSION);
                    $nombreArchivo = uniqid() . '.' . $extension; 
                    $imagenRuta = $ruta . $nombreArchivo;

                    createDirectoryIfNotExists($ruta);

                    if (file_exists($imagenRuta)) {
                        showErrorOrSuccessAndRedirect("error", "Error de archivo", "El nombre de la imagen ya esta registrado", "pendientes.php");
                        exit();
                    }

                    $registroFotoFinal = moveUploadedFile($_FILES['foto_kilometraje_final'], $imagenRuta);

                    if ($registroFotoFinal) {
                        $foto_kilometraje_final = $nombreArchivo; // Actualizamos el nombre de la imagen
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
                                'kilometraje_final'=> $kilometraje_final,
                                'horometro_final'=> $horometro_final,
                                'observaciones' => $observaciones,
                                'id_estado' => $sheets['estado'],
                                'fecha_actualizacion' => $fecha_actualizacion,
                                'imagen_km_final' => $imagenRuta,
                                'tipo_operacion' => 'actualizacion'
                            ];

                            enviarRecollection($datos);
                            showErrorOrSuccessAndRedirect("success", "Actualizacion Exitosa", "Los datos se han actualizado correctamente", "index.php");
                            exit();
                        } else {
                            showErrorOrSuccessAndRedirect("error", "Error de Actualizacion", "Error al momento de actualizar los datos, por favor intentalo nuevamente", "pendientes.php");
                            exit();
                        }
                    }
                }
            }
        } catch (\Throwable $th) {
            showErrorOrSuccessAndRedirect("error", "Error de Actualizacion", "Error al momento de actualizar los datos, por favor intentalo nuevamente", "pendientes.php");
            exit();
        }
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
    $foto_kilometraje = $_FILES['foto_kilometraje']['name'];
    $kilometraje = $_POST['kilometraje'];
    $horometro = $_POST['horometro'];
    $ciudad = $_POST['ciudad'];
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
        showErrorFieldsEmpty("recoleccion_relleno.php");
        exit();
    }
    //* Validamos que el kilometraje sea un número
    try {
        if (isFileUploaded($_FILES['foto_kilometraje'])) {
            $permitidos = array(
                'image/jpeg',
                'image/png',
                'image/jpg',
            );
            $limite_KB = 5000;
            if (isFileValid($_FILES['foto_kilometraje'], $permitidos, $limite_KB)) {
                $ruta = "../assets/images/";
                $extension = pathinfo($_FILES['foto_kilometraje']['name'], PATHINFO_EXTENSION);
                $nombreArchivo = uniqid() . '.' . $extension; 
                // Obtener la extensión del archivo
                $imagenRuta = $ruta . $nombreArchivo;
                createDirectoryIfNotExists($ruta);
                if (file_exists($imagenRuta)) {
                    showErrorOrSuccessAndRedirect("error", "Error de archivo", "El nombre de la imagen ya esta registrado", "recoleccion_relleno.php");
                    exit();
                }
                $registroFoto = moveUploadedFile($_FILES['foto_kilometraje'], $imagenRuta);
                if ($registroFoto) {
                    // OBTENEMOS LA FECHA ACTUAL 
                    $fecha_registro = date('Y-m-d H:i:s');
                    $pendiente = 4;
                    // Inserta los datos en la base de datos, incluyendo la edad
                    $registerRecoleccionRelleno = $connection->prepare("INSERT INTO recoleccion_relleno (fecha_inicio, hora_inicio, km_inicio, ciudad, foto_kilometraje_inicial, horometro_inicio, id_vehiculo, id_labor, documento, id_estado, fecha_registro) VALUES(:fecha_inicio, :hora_inicio, :km_inicio, :ciudad, :foto_kilometraje, :horometro_inicio, :id_vehiculo, :id_labor, :documento, :id_estado, :fecha_registro)");
                    // Vincular los parámetros
                    $registerRecoleccionRelleno->bindParam(':fecha_inicio', $fecha_inicio);
                    $registerRecoleccionRelleno->bindParam(':hora_inicio', $hora_inicio);
                    $registerRecoleccionRelleno->bindParam(':km_inicio', $kilometraje);
                    $registerRecoleccionRelleno->bindParam(':ciudad', $ciudad);
                    $registerRecoleccionRelleno->bindParam(':foto_kilometraje', $nombreArchivo);
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
                        $query = "SELECT  recoleccion_relleno.*, labores.labor, vehiculos.placa, usuarios.documento, estados.estado, ciudades.ciudad
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

                      

                   

                        // Preparar los datos para enviar a Google Sheets
                        $datos = [
                            'id_registro' => $idRegister,
                            'fecha_inicio' => $fecha_inicio,
                            'hora_inicio' => $hora_inicio,
                            'documento' => $documento,
                            'placa' => $vehiculo,
                            'kilometroje_inicial' => $kilometraje,
                            'horometro_inicial' => $horometro,
                            'labor' => $data['labor'],
                            'id_estado' => $data['estado'],
                            'fecha_registro' => $fecha_registro,
                            'ciudad' => $data['ciudad'],
                            'imagen_km_inicial' => $imagenRuta, // Tripulación concatenada
                            'tipo_operacion' => 'registro_inicial'
                        ];

                        // Enviar datos a Google Sheets
                        enviarRelleno($datos);



                        showErrorOrSuccessAndRedirect("success", "Formulario Registrado", "Se ha registrado la etapa inicial del formulario, debes terminar de rellenar la información restante en el panel de formularios pendientes", "index.php");
                        exit();
                    }
                }
            }
        }
    } catch (\Throwable $th) {
        showErrorOrSuccessAndRedirect("error", "Error de Registro", "Error al momento de registrar los datos, por favor inténtalo nuevamente.", "recoleccion_relleno.php");
        exit();
    }
}

?>
<?php
//* ... METODO PARA ACTUALIZAR LOS DATOS DE TIPO LABOR DE RELLENO ...
if ((isset($_POST["MM_formUpdateDisposicion"])) && ($_POST["MM_formUpdateDisposicion"] == "formUpdateDisposicion")) {
    // VARIABLES DE ASIGNACION DE VALORES QUE SE ENVIA DEL FORMULARIO REGISTRO DE ACTIVIDAD
    $id_recoleccion = $_POST['id_recoleccion'];
    $fecha_final = $_POST['fecha_final'];
    $hora_finalizacion = $_POST['hora_finalizacion'];
    $foto_kilometraje_final = $_FILES['foto_kilometraje_final']['name'];
    $kilometraje_final = $_POST['kilometraje_final'];
    $horometro_final = $_POST['horometro_final'];
    $observaciones = $_POST['observaciones'];
    $toneladas = $_POST['toneladas'];
    $galones = $_POST['galones'];

    // validamos que no hayamos recibido ningun dato vacio
    if (isEmpty([$fecha_final, $hora_finalizacion, $horometro_final, $toneladas, $galones])) {
        showErrorFieldsEmpty("pendientes.php");
        exit();
    } else {
        try {
            if (isFileUploaded($_FILES['foto_kilometraje_final'])) {
                $permitidos = array(
                    'image/jpeg',
                    'image/png',
                    'image/jpg',
                );
                $limite_KB = 5000;
                if (isFileValid($_FILES['foto_kilometraje_final'], $permitidos, $limite_KB)) {
                    $ruta = "../assets/images/";
                    // Obtener la extensión del archivo
                    $extension = pathinfo($_FILES['foto_kilometraje_final']['name'], PATHINFO_EXTENSION);
                    $nombreArchivo = uniqid() . '.' . $extension; 
                    
                    $imagenRuta = $ruta . $nombreArchivo;
                    createDirectoryIfNotExists($ruta);
                    if (file_exists($imagenRuta)) {
                        showErrorOrSuccessAndRedirect("error", "Error de archivo", "El nombre de la imagen ya esta registrado", "pendientes.php");
                        exit();
                    }
                    $registroFotoFinal = moveUploadedFile($_FILES['foto_kilometraje_final'], $imagenRuta);
                    if ($registroFotoFinal) {
                        // OBTENEMOS LA FECHA ACTUAL 
                        $fecha_actualizacion = date('Y-m-d H:i:s');
                        $finalizado = 5;
                        // Inserta los datos en la base de datos, incluyendo la edad
                        // Inserta los datos en la base de datos
                        $updateRegister = $connection->prepare("UPDATE recoleccion_relleno SET fecha_fin = :fecha_final, fecha_actualizacion = :fecha_actualizacion, hora_finalizacion = :hora_finalizacion, foto_kilometraje_final = :foto_kilometraje_final, km_fin = :km_fin, horometro_fin = :horometro_final, id_estado = :id_estado, observaciones = :observaciones, toneladas = :toneladas, galones = :galones WHERE id_recoleccion = :id_recoleccion");
                        $updateRegister->bindParam(':fecha_final', $fecha_final);
                        $updateRegister->bindParam(':fecha_actualizacion', $fecha_actualizacion);
                        $updateRegister->bindParam(':hora_finalizacion', $hora_finalizacion);
                        $updateRegister->bindParam(':foto_kilometraje_final', $foto_kilometraje_final);
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
                            'kilometraje_final'=> $kilometraje_final,
                            'horometro_final'=> $horometro_final,
                            'observaciones' => $observaciones,
                            'id_estado' => $sheets['estado'],
                            'galones' => $galones,
                            'fecha_actualizacion' => $fecha_actualizacion,
                            'imagen_km_final' => $imagenRuta,
                            'tipo_operacion' => 'actualizacion'
                        ];

                        enviarRelleno($datos);
                            showErrorOrSuccessAndRedirect("success", "Actualizacion Exitosa", "Los datos se han actualizado correctamente", "index.php");
                            exit();
                        } else {
                            showErrorOrSuccessAndRedirect("error", "Error de Actualizacion", "Error al momento de actualizar los datos, por favor intentalo nuevamente", "pendientes.php");
                            exit();
                        }
                    }
                }
            }
        } catch (\Throwable $th) {
            showErrorOrSuccessAndRedirect("error", "Error de Actualizacion", "Error al momento de actualizar los datos, por favor intentalo nuevamente", "pendientes.php");
            exit();
        }
    }
}


?>