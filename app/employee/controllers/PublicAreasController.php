<?php
function enviarAGoogleSheets($datos) {
    $url = 'https://script.google.com/macros/s/AKfycbw9jsfxtTxQOd61oENuzszYBcmDyJciwTE-SQYk_nocd5IMjcJy2a5RPzlt4o7sKgjvNA/exec'; // Reemplaza con la URL de tu aplicación web de Google Apps Script

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
if ((isset($_POST["MM_formRegisterTreePruning"])) && ($_POST["MM_formRegisterTreePruning"] == "formRegisterTreePruning")) {
    // VARIABLES DE ASIGNACIÓN DE VALORES QUE SE ENVÍAN DESDE EL FORMULARIO DE REGISTRO DE VEHICULO COMPACTADOR
    $fecha_inicio = $_POST['fecha_inicio'];
    $documento = $_POST['documento'];
    $hora_inicio = $_POST['hora_inicio'];
    $ciudad = $_POST['id_ciudad'];
    
    
    

    // Validamos que no se haya recibido ningún dato vacío
    if (empty($fecha_inicio) ||  empty($hora_inicio) || empty($documento) || empty($ciudad) ) {
        showErrorFieldsEmpty( "poda.php");
        exit();
    }

    // OBTENEMOS LA FECHA ACTUAL 
    $fecha_registro = date('Y-m-d H:i:s');
    $pendiente = 4;
    $labor = 7;

    // Inserta los datos en la base de datos
    $register = $connection->prepare("INSERT INTO areas_publicas(fecha_inicio, hora_inicio,id_labor, documento, id_estado, fecha_registro,id_ciudad) VALUES(:fecha_inicio, :hora_inicio,:id_labor, :documento, :id_estado, :fecha_registro,:id_ciudad)");

    // Vincular los parámetros
    $register->bindParam(':fecha_inicio', $fecha_inicio);
    $register->bindParam(':hora_inicio', $hora_inicio);
    $register->bindParam(':id_labor', $labor);
    $register->bindParam(':documento', $documento);
    $register->bindParam(':id_estado', $pendiente);
    $register->bindParam(':fecha_registro', $fecha_registro);
    $register->bindParam(':id_ciudad', $ciudad);
    
    if ($register->execute()) {
         // Obtener el ID autoincremental
         $id_registro = $connection->lastInsertId();

         // Consultar los nombres de la ciudad, documento y usuario
         $querySheets = $connection->prepare("SELECT   areas_publicas.*,  
                                labores.labor, 
                                ciudades.id_ciudad, 
                                ciudades.ciudad, 
                                estados.estado,
                                usuarios.nombres,
                                usuarios.apellidos

                                FROM  areas_publicas 
                                INNER JOIN ciudades ON areas_publicas.id_ciudad = ciudades.id_ciudad 
                                INNER JOIN labores ON areas_publicas.id_labor = labores.id_labor 
                                INNER JOIN estados ON areas_publicas.id_estado = estados.id_estado 
                                INNER JOIN usuarios ON areas_publicas.documento = usuarios.documento 
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
             'labor' => $sheets['labor'],
             'id_estado' => $sheets['estado'],
             'fecha_registro' => $fecha_registro,
             'ciudad' => $sheets['ciudad'],
             'tipo_operacion' => 'registro_inicial' // Nombre de la ciudad
         ];
 
         // Enviar datos a Google Sheets
         enviarAGoogleSheets($datos);
        showErrorOrSuccessAndRedirect("success", "Formulario Registrado", "Se ha registrado la etapa inicial del formulario, debes terminar de rellenar la información restante en el panel de formularios pendientes", "index.php");
        exit();
    } else {
        showErrorOrSuccessAndRedirect("error", "Error de Registro", "Error al momento de registrar los datos, por favor inténtalo nuevamente.", "poda.php");
        exit();
    }
}
?>


<?php
//* Registro de datos de registro de actividades
if ((isset($_POST["MM_formRegisterGrassPruning"])) && ($_POST["MM_formRegisterGrassPruning"] == "formRegisterGrassPruning")) {
    // VARIABLES DE ASIGNACIÓN DE VALORES QUE SE ENVÍAN DESDE EL FORMULARIO DE REGISTRO DE VEHICULO COMPACTADOR
    $fecha_inicio = $_POST['fecha_inicio'];
    $documento = $_POST['documento'];
    $hora_inicio = $_POST['hora_inicio'];
    $ciudad = $_POST['id_ciudad'];
    
    

    // Validamos que no se haya recibido ningún dato vacío
    if (empty($fecha_inicio) ||  empty($hora_inicio) || empty($documento) || empty($ciudad) ) {
        showErrorFieldsEmpty( "cesped.php");
        exit();
    }

    // OBTENEMOS LA FECHA ACTUAL 
    $fecha_registro = date('Y-m-d H:i:s');
    $pendiente = 4;
    $labor = 6;

    // Inserta los datos en la base de datos
    $register = $connection->prepare("INSERT INTO areas_publicas(fecha_inicio, hora_inicio,id_labor, documento, id_estado, fecha_registro,id_ciudad) VALUES(:fecha_inicio, :hora_inicio,:id_labor, :documento, :id_estado, :fecha_registro,:id_ciudad)");

    // Vincular los parámetros
    $register->bindParam(':fecha_inicio', $fecha_inicio);
    $register->bindParam(':hora_inicio', $hora_inicio);
    $register->bindParam(':id_labor', $labor);
    $register->bindParam(':documento', $documento);
    $register->bindParam(':id_estado', $pendiente);
    $register->bindParam(':fecha_registro', $fecha_registro);
    $register->bindParam(':id_ciudad', $ciudad);
    
    if ($register->execute()) {
            
  
        // Obtener el ID autoincremental
        $id_registro = $connection->lastInsertId();

        // Consultar los nombres de la ciudad, documento y usuario
        $querySheets = $connection->prepare("SELECT   areas_publicas.*,  
                               labores.labor, 
                               ciudades.id_ciudad, 
                               ciudades.ciudad, 
                               estados.estado,
                               usuarios.nombres,
                               usuarios.apellidos
                               FROM  areas_publicas 
                               INNER JOIN ciudades ON areas_publicas.id_ciudad = ciudades.id_ciudad 
                               INNER JOIN labores ON areas_publicas.id_labor = labores.id_labor 
                               INNER JOIN estados ON areas_publicas.id_estado = estados.id_estado 
                               INNER JOIN usuarios ON areas_publicas.documento = usuarios.documento
                               WHERE id_registro = :id_registro");
           $querySheets->bindParam(":id_registro", $id_registro);
           $querySheets->execute();
           $sheets = $querySheets->fetch(PDO::FETCH_ASSOC);


           
        $registradorConcatenado = $_POST['documento'] . " (" . $sheets['nombres'] . " " . $sheets['apellidos'] . ")";
        $datos = [
            'id_registro' => $id_registro,
            'fecha_inicio' => $fecha_inicio,
            'hora_inicio' => $hora_inicio,
            'documento' => $registradorConcatenado , 
            'labor' => $sheets['labor'],
            'id_estado' => $sheets['estado'],
            'fecha_registro' => $fecha_registro,
            'ciudad' => $sheets['ciudad'],
            'tipo_operacion' => 'registro_inicial' // Nombre de la ciudad
        ];

        // Enviar datos a Google Sheets
        enviarAGoogleSheets($datos);
        showErrorOrSuccessAndRedirect("success", "Formulario Registrado", "Se ha registrado la etapa inicial del formulario, debes terminar de rellenar la información restante en el panel de formularios pendientes", "index.php");
        exit();
    } else {
        showErrorOrSuccessAndRedirect("error", "Error de Registro", "Error al momento de registrar los datos, por favor inténtalo nuevamente.", "cesped.php");
        exit();
    }
}
?>



<?php
//* Registro de datos de registro de actividades
if ((isset($_POST["MM_formRegisterWashing"])) && ($_POST["MM_formRegisterWashing"] == "formRegisterWashing")) {
    // VARIABLES DE ASIGNACIÓN DE VALORES QUE SE ENVÍAN DESDE EL FORMULARIO DE REGISTRO DE VEHICULO COMPACTADOR
    $fecha_inicio = $_POST['fecha_inicio'];
    $documento = $_POST['documento'];
    $hora_inicio = $_POST['hora_inicio'];
    $ciudad = $_POST['id_ciudad'];
    
    

    // Validamos que no se haya recibido ningún dato vacío
    if (empty($fecha_inicio) ||  empty($hora_inicio) || empty($documento) || empty($ciudad) ) {
        showErrorFieldsEmpty( "lavado.php");
        exit();
    }

    // OBTENEMOS LA FECHA ACTUAL 
    $fecha_registro = date('Y-m-d H:i:s');
    $pendiente = 4;
    $labor = 8;

    // Inserta los datos en la base de datos
    $register = $connection->prepare("INSERT INTO areas_publicas(fecha_inicio, hora_inicio,id_labor, documento, id_estado, fecha_registro,id_ciudad) VALUES(:fecha_inicio, :hora_inicio,:id_labor, :documento, :id_estado, :fecha_registro,:id_ciudad)");

    // Vincular los parámetros
    $register->bindParam(':fecha_inicio', $fecha_inicio);
    $register->bindParam(':hora_inicio', $hora_inicio);
    $register->bindParam(':id_labor', $labor);
    $register->bindParam(':documento', $documento);
    $register->bindParam(':id_estado', $pendiente);
    $register->bindParam(':fecha_registro', $fecha_registro);
    $register->bindParam(':id_ciudad', $ciudad);
    
   
           
    if ($register->execute()) {
        // Obtener el ID autoincremental
        $id_registro = $connection->lastInsertId();

        // Consultar los nombres de la ciudad, documento y usuario
        $querySheets = $connection->prepare("SELECT   areas_publicas.*,  
                               labores.labor, 
                               ciudades.id_ciudad, 
                               ciudades.ciudad, 
                               estados.estado,
                               usuarios.nombres,
                               usuarios.apellidos
                               FROM  areas_publicas 
                               INNER JOIN ciudades ON areas_publicas.id_ciudad = ciudades.id_ciudad 
                               INNER JOIN labores ON areas_publicas.id_labor = labores.id_labor 
                               INNER JOIN estados ON areas_publicas.id_estado = estados.id_estado 
                               INNER JOIN usuarios ON areas_publicas.documento = usuarios.documento
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
            'labor' => $sheets['labor'],
            'id_estado' => $sheets['estado'],
            'fecha_registro' => $fecha_registro,
            'ciudad' => $sheets['ciudad'],
            'tipo_operacion' => 'registro_inicial' // Nombre de la ciudad
        ];

        // Enviar datos a Google Sheets
        enviarAGoogleSheets($datos);
        showErrorOrSuccessAndRedirect("success", "Formulario Registrado", "Se ha registrado la etapa inicial del formulario, debes terminar de rellenar la información restante en el panel de formularios pendientes", "index.php");
        exit();
    } else {
        showErrorOrSuccessAndRedirect("error", "Error de Registro", "Error al momento de registrar los datos, por favor inténtalo nuevamente.", "pendientes.php");
        exit();
    }
}
?>

<?php
// * Método actualizar
if ((isset($_POST["MM_formFinishPublicAreas"])) && ($_POST["MM_formFinishPublicAreas"] == "formFinishPublicAreas")) {
    
    // Variables de asignación de valores que se envían desde el formulario
    $fecha_fin     = $_POST['fecha_fin'];
    $hora_fin      = $_POST['hora_fin'];
    $peso          = isset($_POST['peso']) && $_POST['peso'] !== '' ? $_POST['peso'] : null; // Si está vacío, asignamos null
    $observacion   = $_POST['observacion'];
    $id_registro   = $_POST['id_registro']; // Este ID se envía desde el formulario

    // Validamos que no hayamos recibido ningún dato vacío (exceptuando peso)
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

        // Actualizar los datos en la base de datos
        $finishRegister = $connection->prepare("
            UPDATE areas_publicas 
            SET fecha_finalizacion = :fecha_fin, 
                hora_finalizacion = :hora_fin, 
                peso = :peso, 
                observaciones = :observacion, 
                fecha_actualizacion = :fecha_actualizacion,
                id_estado = :id_estado
            WHERE id_registro = :id_registro
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
            // Consultar los nombres de los campos relacionados
            $querySheets = $connection->prepare("
                SELECT areas_publicas.*, estados.estado
                FROM areas_publicas
                INNER JOIN estados ON areas_publicas.id_estado = estados.id_estado
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
                'peso' => $peso ?? 0, // Enviar 0 a Google Sheets si peso es NULL
                'observacion' => $observacion,
                'id_estado' => $sheets['estado'], // Estado actualizado
                'fecha_actualizacion' => $fecha_actualizacion,
                'tipo_operacion' => 'actualizacion'
            ];

            // Enviar datos a Google Sheets
            enviarAGoogleSheets($datos);

            showErrorOrSuccessAndRedirect("success", "¡Perfecto!", "Los datos se han actualizado correctamente", "index.php");
            exit();
        }
    } catch (\Throwable $th) {
        showErrorOrSuccessAndRedirect("error", "Error de actualización", "Error al momento de actualizar los datos.", "index.php");
        exit();
    }
}
?>