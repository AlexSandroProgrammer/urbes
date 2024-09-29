<?php
function enviarMecanichs($datos) {
    $url = 'https://script.google.com/macros/s/AKfycby3LqWfR0Si3a-qAbBpXZaPvZyfk5WAo4B_5W-Augv70Gml3EOsN2d6MUeFfsXeYK_5Rg/exec'; // Reemplaza con la URL de tu aplicación web de Google Apps Script

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
if ((isset($_POST["MM_formRegisterMechanics"])) && ($_POST["MM_formRegisterMechanics"] == "formRegisterMechanics")) {
    // VARIABLES DE ASIGNACIÓN DE VALORES QUE SE ENVÍAN DESDE EL FORMULARIO DE REGISTRO DE VEHICULO COMPACTADOR
    $fecha_inicio = $_POST['fecha_inicio'];
    $documento = $_POST['documento'];
    $hora_inicio = $_POST['hora_inicio'];
    $id_vehiculo = $_POST['vehiculo'];

    // Validamos que no se haya recibido ningún dato vacío
    if (empty($fecha_inicio) ||  empty($hora_inicio) || empty($documento) || empty($id_vehiculo)) {
        showErrorFieldsEmpty( "mecanica.php");
        exit();
    }

    // OBTENEMOS LA FECHA ACTUAL 
    $fecha_registro = date('Y-m-d H:i:s');
    $pendiente = 4;
    $actvidad = 2;

    // Inserta los datos en la base de datos
    $register = $connection->prepare("INSERT INTO mecanica (fecha_inicio, hora_inicio,id_actividad, documento, id_estado, fecha_registro,id_vehiculo) VALUES(:fecha_inicio, :hora_inicio,:id_actividad, :documento, :id_estado, :fecha_registro,:id_vehiculo)");

    // Vincular los parámetros
    $register->bindParam(':fecha_inicio', $fecha_inicio);
    $register->bindParam(':hora_inicio', $hora_inicio);
    $register->bindParam(':id_actividad', $actvidad);
    $register->bindParam(':documento', $documento);
    $register->bindParam(':id_estado', $pendiente);
    $register->bindParam(':fecha_registro', $fecha_registro);
    $register->bindParam(':id_vehiculo', $id_vehiculo);
    
    if ($register->execute()) {
         // Obtener el ID autoincremental
         $id_registro = $connection->lastInsertId();

         // Consultar los nombres de la ciudad, documento y usuario
         $querySheets = $connection->prepare("SELECT   mecanica.*,  
                                actividades.actividad,
                                estados.estado,
                                usuarios.nombres,
                                usuarios.apellidos
                                FROM  mecanica 
                                INNER JOIN actividades ON mecanica.id_actividad = actividades.id_actividad 
                                INNER JOIN estados ON mecanica.id_estado = estados.id_estado 
                                INNER JOIN usuarios ON mecanica.documento = usuarios.documento
                                WHERE id_registro = :id_registro");
            $querySheets->bindParam(":id_registro", $id_registro);
            $querySheets->execute();
            $sheets = $querySheets->fetch(PDO::FETCH_ASSOC);
 


                
    $registradorConcatenado = $_POST['documento'] . " (" . $sheets['nombres'] . " " . $sheets['apellidos'] . ")";
         $datos = [
             'id_registro' => $id_registro,
             'fecha_inicio' => $fecha_inicio,
             'hora_inicio' => $hora_inicio,
             'documento' => $registradorConcatenado, 
             'actividad' => $sheets['actividad'],
             'id_estado' => $sheets['estado'],
             'vehiculo' => $id_vehiculo, 
             'fecha_registro' => $fecha_registro,
             'tipo_operacion' => 'registro_inicial' // Nombre de la ciudad
         ];
 
         // Enviar datos a Google Sheets
         enviarMecanichs($datos);
        showErrorOrSuccessAndRedirect("success", "Formulario Registrado", "Se ha registrado la etapa inicial del formulario, debes terminar de rellenar la información restante en el panel de formularios pendientes", "index.php");
        exit();
    } else {
        showErrorOrSuccessAndRedirect("error", "Error de Registro", "Error al momento de registrar los datos, por favor inténtalo nuevamente.", "mecanica.php");
        exit();
    }
}
?>






<?php
// * Método actualizar
if ((isset($_POST["MM_formFinishMechanics"])) && ($_POST["MM_formFinishMechanics"] == "formFinishMechanics")) {
    
    // Variables de asignación de valores que se envían desde el formulario
    $fecha_fin     = $_POST['fecha_fin'];
    $hora_fin      = $_POST['hora_fin'];
    $observacion   = $_POST['observacion'];
    $mantenimiento = $_POST['mantenimiento'];
    $id_registro   = $_POST['id_registro'];

    // Validamos que no hayamos recibido ningún dato vacío
    if (isEmpty([
        $fecha_fin,
        $hora_fin, 
        $observacion,
        $mantenimiento,
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
            UPDATE mecanica
            SET fecha_fin = :fecha_fin, 
                hora_finalizacion = :hora_fin, 
                observaciones = :observacion, 
                labor_mantenimiento = :mantenimiento,
                fecha_actualizacion = :fecha_actualizacion,
                id_estado = :id_estado
            WHERE id_registro = :id_registro
        ");

        // Vincular los parámetros
        $finishRegister->bindParam(':fecha_fin', $fecha_fin);
        $finishRegister->bindParam(':hora_fin', $hora_fin);
        $finishRegister->bindParam(':observacion', $observacion);
        $finishRegister->bindParam(':mantenimiento', $mantenimiento);
        $finishRegister->bindParam(':fecha_actualizacion', $fecha_actualizacion);
        $finishRegister->bindParam(':id_estado', $id_estado);
        $finishRegister->bindParam(':id_registro', $id_registro);  // Añadir el parámetro que faltaba
        $finishRegister->execute();

        if ($finishRegister) {
            
            $querySheets = $connection->prepare("
            SELECT mecanica.*, estados.estado
            FROM mecanica
            INNER JOIN estados ON mecanica.id_estado = estados.id_estado
            WHERE id_registro = :id_registro
        ");
        $querySheets->bindParam(":id_registro", $id_registro); // Usar el ID correcto
        $querySheets->execute();
        $sheets = $querySheets->fetch(PDO::FETCH_ASSOC);

        // Preparar los datos para Google Sheets
        $datos = [
            'id_registro' => $id_registro,
            'fecha_fin' => $fecha_fin,
            'hora_fin' => $hora_fin,
            'mantenimiento' => $mantenimiento,
            'observacion' => $observacion,
            'id_estado' => $sheets['estado'], // Estado actualizado
            'fecha_actualizacion' => $fecha_actualizacion,
            'tipo_operacion' => 'actualizacion'
        ];

        // Enviar datos a Google Sheets
        enviarMecanichs($datos);
            showErrorOrSuccessAndRedirect("success", "¡Perfecto!", "Los datos se han actualizado correctamente", "./index.php");
            exit();
        }
    } catch (\Throwable $th) {
        $errorMessage = $th->getMessage();
        echo $errorMessage;
        exit();
    }
}
?>