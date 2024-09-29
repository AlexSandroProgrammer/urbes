<?php
function enviarAGoogle($datos) {
    $url = 'https://script.google.com/macros/s/AKfycbwe1aBiobGopCdHb8nKtYMfngfnMgGlS41V0Bmu6bJtMo-2W2djL6lwqB_e1-NLogre5A/exec'; // Reemplaza con la URL de tu aplicación web de Google Apps Script

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
if ((isset($_POST["MM_formRegisterSweepingCart"])) && ($_POST["MM_formRegisterSweepingCart"] == "formRegisterSweepingCart")) {
    // VARIABLES DE ASIGNACIÓN DE VALORES QUE SE ENVÍAN DESDE EL FORMULARIO DE REGISTRO DE VEHICULO COMPACTADOR
    $fecha_inicio = $_POST['fecha_inicio'];
    $documento = $_POST['documento'];
    $hora_inicio = $_POST['hora_inicio'];
    $ciudad = $_POST['ciudad'];
    $zonas = isset($_POST['zonas']) ? $_POST['zonas'] : [];

    // Validamos que no se haya recibido ningún dato vacío
    if (empty($fecha_inicio) || empty($documento) || empty($hora_inicio) || empty($ciudad) || empty($zonas)) {
        showErrorFieldsEmpty("carro_barrido.php");
        exit();
    }

    // OBTENEMOS LA FECHA ACTUAL 
    $fecha_registro = date('Y-m-d H:i:s');
    $pendiente = 4; // Estado pendiente
    $actividad = 5; // Actividad asignada

    // Inserta los datos en la base de datos
    $register = $connection->prepare("INSERT INTO carro_barrido (fecha_inicio, hora_inicio, id_actividad, ciudad, documento, id_estado, fecha_registro) VALUES (:fecha_inicio, :hora_inicio, :id_actividad, :ciudad, :documento, :id_estado, :fecha_registro)");

    // Vincular los parámetros
    $register->bindParam(':fecha_inicio', $fecha_inicio);
    $register->bindParam(':hora_inicio', $hora_inicio);
    $register->bindParam(':id_actividad', $actividad);
    $register->bindParam(':ciudad', $ciudad);
    $register->bindParam(':documento', $documento);
    $register->bindParam(':id_estado', $pendiente);
    $register->bindParam(':fecha_registro', $fecha_registro);

    if ($register->execute()) {
        // Capturamos el ID del último registro insertado
        $idRegister = $connection->lastInsertId();
        
        // Insertar el arreglo de zonas en otra tabla relacionada (detalle_zonas)
        $insertarDetail = $connection->prepare("INSERT INTO detalle_zonas(id_zona, id_registro) VALUES(:id_zona, :id_registro)");

        foreach ($zonas as $zonaId) {
            $insertarDetail->bindParam(':id_zona', $zonaId);
            $insertarDetail->bindParam(':id_registro', $idRegister);
            $insertarDetail->execute();
        }
       

        // Consulta para obtener los datos necesarios de la tabla carro_barrido
        $querySheets = $connection->prepare("SELECT carro_barrido.*, actividades.actividad, ciudades.ciudad, estados.estado,usuarios.nombres,usuarios.apellidos
                                             FROM carro_barrido
                                             INNER JOIN ciudades ON carro_barrido.ciudad = ciudades.id_ciudad
                                             INNER JOIN usuarios ON carro_barrido.documento = usuarios.documento
                                             INNER JOIN estados ON carro_barrido.id_estado = estados.id_estado
                                             INNER JOIN actividades ON carro_barrido.id_actividad = actividades.id_actividad
                                             WHERE id_registro_barrido = :id_registro");
        $querySheets->bindParam(":id_registro", $idRegister);
        $querySheets->execute();
        $sheets = $querySheets->fetch(PDO::FETCH_ASSOC);

        // Consulta para obtener todas las zonas asociadas al id_registro
        $queryZonas = $connection->prepare("SELECT zonas.zona
                                            FROM detalle_zonas
                                            INNER JOIN zonas ON detalle_zonas.id_zona = zonas.id_zona
                                            WHERE detalle_zonas.id_registro = :id_registro");
        $queryZonas->bindParam(":id_registro", $idRegister);
        $queryZonas->execute();

        // Obtener todas las zonas asociadas en un array
        $zonasResult = $queryZonas->fetchAll(PDO::FETCH_COLUMN);

        // Verifica si se están obteniendo múltiples zonas
        if (count($zonasResult) > 1) {
        // Concatenar los nombres de las zonas en una cadena
        $zonasConcatenadas = implode(", ", $zonasResult);
        } else {
         // Si solo hay una zona, asignarla directamente
        $zonasConcatenadas = $zonasResult[0];
        }

      
        $registradorConcatenado = $_POST['documento'] . " (" . $sheets['nombres'] . " " . $sheets['apellidos'] . ")";
        // Preparar los datos para enviar a Google Sheets
        $datos = [
            'id_registro' => $idRegister,
            'fecha_inicio' => $fecha_inicio,
            'hora_inicio' => $hora_inicio,
            'documento' => $registradorConcatenado,
            'actividad' => $sheets['actividad'],
            'id_estado' => $sheets['estado'],
            'fecha_registro' => $fecha_registro,
            'ciudad' => $sheets['ciudad'],
            'zonas' => $zonasConcatenadas, // Agregar las zonas concatenadas
            'tipo_operacion' => 'registro_inicial'
        ];

        // Enviar datos a Google Sheets
        enviarAGoogle($datos);

        // Redirigir con mensaje de éxito
        showErrorOrSuccessAndRedirect("success", "Formulario Registrado", "Se ha registrado la etapa inicial del formulario, debes terminar de rellenar la información restante en el panel de formularios pendientes", "index.php");
        exit();
    } else {
        // Manejar error de registro en la base de datos
        showErrorOrSuccessAndRedirect("error", "Error de Registro", "Error al momento de registrar los datos, por favor inténtalo nuevamente.", "carro_barrido.php");
        exit();
    }
}
?>




<?php
// * Método actualizar
if ((isset($_POST["MM_formFinishSweepingCart"])) && ($_POST["MM_formFinishSweepingCart"] == "formFinishSweepingCart")) {
    
    // Variables de asignación de valores que se envían desde el formulario
    $fecha_fin     = $_POST['fecha_fin'];
    $hora_fin      = $_POST['hora_fin'];
    $peso          = isset($_POST['peso']) && $_POST['peso'] !== '' ? $_POST['peso'] : null; // Si está vacío, asignamos null
    $observacion   = $_POST['observacion'];
    $id_registro   = $_POST['id_registro'];

    // Validamos que no hayamos recibido ningún dato vacío
    if (isEmpty([
        $fecha_fin,
        $hora_fin, 
        $observacion,
        $id_registro 
    ])) {
        showErrorFieldsEmpty("pendientes.php");
        exit();
    }

    try {
        // Fecha actual
        $fecha_actualizacion = date('Y-m-d H:i:s');
        $id_estado = 5;
       
        // Inserción de los datos en la base de datos
        $finishRegister = $connection->prepare("
            UPDATE carro_barrido
            SET fecha_fin = :fecha_fin, 
                hora_fin = :hora_fin, 
                peso = :peso, 
                observaciones = :observacion, 
                fecha_actualizacion = :fecha_actualizacion,
                id_estado = :id_estado
            WHERE id_registro_barrido = :id_registro
        ");

        // Vincular los parámetros
        $finishRegister->bindParam(':fecha_fin', $fecha_fin);
        $finishRegister->bindParam(':hora_fin', $hora_fin);
         // Si $peso es NULL, usamos bindValue con PDO::PARAM_NULL, si no, bindParam con el valor real
        if ($peso === null) {
            $finishRegister->bindValue(':peso', null, PDO::PARAM_NULL);
        } else {
            $finishRegister->bindParam(':peso', $peso);
        }
        $finishRegister->bindParam(':observacion', $observacion);
        $finishRegister->bindParam(':fecha_actualizacion', $fecha_actualizacion);
        $finishRegister->bindParam(':id_estado', $id_estado);
        $finishRegister->bindParam(':id_registro', $id_registro);
        $finishRegister->execute();

        if ($finishRegister) {
            $querySheets = $connection->prepare("SELECT carro_barrido.*, estados.estado
            FROM carro_barrido
            INNER JOIN estados ON carro_barrido.id_estado = estados.id_estado
            WHERE id_registro_barrido = :id_registro
        ");
        $querySheets->bindParam(":id_registro", $id_registro);
        $querySheets->execute();
        $sheets = $querySheets->fetch(PDO::FETCH_ASSOC);

        // Preparar los datos para Google Sheets
        $datos = [
            'id_registro' => $id_registro,
            'fecha_fin' => $fecha_fin,
            'hora_fin' => $hora_fin,
            'peso' => $peso ?? 0,
            'observacion' => $observacion,
            'id_estado' => $sheets['estado'],
            'fecha_actualizacion' => $fecha_actualizacion,
            'tipo_operacion' => 'actualizacion'
        ];

        // Enviar datos a Google Sheets
        enviarAGoogle($datos);

        showErrorOrSuccessAndRedirect("success", "¡Perfecto!", "Los datos se han actualizado correctamente", "./index.php");
        exit();
        }
     }  catch (\Throwable $th) {
        showErrorOrSuccessAndRedirect("error", "Error de actualización", "Error al momento de actualizar los datos.", "./index.php");
        exit();
    }
}
?>