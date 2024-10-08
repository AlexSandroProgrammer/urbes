<?php
function enviarAforos($datos)
{
    $url = 'https://script.google.com/macros/s/AKfycby_KrWS05GoP7_CRSjb1PAgiHDMzTuHj4hcHfeJ4qPIgGoFXTuohF1hFGhtRMQcem_-rA/exec'; // Reemplaza con la URL de tu aplicación web de Google Apps Script

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

// Método para registrar aforo
if ((isset($_POST["MM_formRegisterAforo"])) && ($_POST["MM_formRegisterAforo"] == "formRegisterAforo")) {

    // Variables del formulario
    $matricula = filter_input(INPUT_POST, 'matricula', FILTER_VALIDATE_INT);
    $documento = filter_input(INPUT_POST, 'documento', FILTER_VALIDATE_INT);
    $peso = filter_input(INPUT_POST, 'peso', FILTER_VALIDATE_FLOAT);
    $fechaActual = $_POST['fecha_registro'] ?? date('Y-m-d');
    $nombres = $_POST['nombres'];

    // Verificar campos obligatorios
    if (isEmpty([$matricula, $peso, $fechaActual])) {
        showErrorFieldsEmpty("aforos.php");
        exit();
    }

    // Manejo de la imagen
    $foto_aforo = manejarImagenSubida('foto_aforo', "../assets/images/", 5000);

    // Insertar aforo
    $insertAforo = $connection->prepare("INSERT INTO aforos (matricula_empresa, fecha_registro, peso, foto, documento) VALUES (:matricula, :fecha_registro, :peso, :foto_aforo, :documento)");
    $insertAforo->bindParam(':matricula', $matricula);
    $insertAforo->bindParam(':fecha_registro', $fechaActual);
    $insertAforo->bindParam(':peso', $peso);
    $insertAforo->bindParam(':foto_aforo', $foto_aforo);
    $insertAforo->bindParam(':documento', $documento);
    try {
        if ($insertAforo->execute()) {
            $id_registro = $connection->lastInsertId();
            manejarReporteMensual($matricula, $peso, $fechaActual);
            $registradorConcatenado = $_POST['documento'] . " (" . $_POST['nombres'] .  ")";
            $datos = [
                'id_registro' => $id_registro,
                'fecha_registro' => $fechaActual,
                'documento' => $registradorConcatenado,
                'peso' => $peso,
                'tipo_actividad' => 'Aforos'
            ];
            enviarAforos($datos);
            showErrorOrSuccessAndRedirect("success", "Registro Exitoso", "El formulario fue registrado correctamente.", "index.php");
        } else {
            throw new Exception("Error al registrar el aforo.");
        }
    } catch (Exception $e) {
        showErrorOrSuccessAndRedirect("error", "Error de Registro", $e->getMessage(), "aforos.php");
    }
    exit();
}

// Función para manejar la imagen subida
function manejarImagenSubida($campo, $rutaDestino, $limiteKB)
{
    $permitidos = ['image/jpeg', 'image/png', 'image/jpg'];
    if (isset($_FILES[$campo]) && is_uploaded_file($_FILES[$campo]['tmp_name'])) {
        $tipoArchivo = $_FILES[$campo]['type'];
        $tamanioArchivo = $_FILES[$campo]['size'];
        if (!in_array($tipoArchivo, $permitidos)) {
            showErrorOrSuccessAndRedirect("error", "Error de archivo", "Formato de imagen no permitido.", "aforos.php");
            exit();
        }
        if ($tamanioArchivo > ($limiteKB * 1024)) {
            showErrorOrSuccessAndRedirect("error", "Error de archivo", "El tamaño de la imagen supera los 5MB.", "aforos.php");
            exit();
        }
        $extension = pathinfo($_FILES[$campo]['name'], PATHINFO_EXTENSION);
        $nombreArchivo = uniqid() . '_' . time() . '.' . $extension;
        $rutaCompleta = $rutaDestino . $nombreArchivo;
        createDirectoryIfNotExists($rutaDestino);
        if (!move_uploaded_file($_FILES[$campo]['tmp_name'], $rutaCompleta)) {
            throw new Exception("Error al mover la imagen.");
        }

        return $nombreArchivo;
    }
    return null;
}

// Función para manejar el reporte mensual
function manejarReporteMensual($matricula, $peso, $fechaActual)
{
    global $connection;

    $mes = date('m', strtotime($fechaActual));
    $anio = date('Y', strtotime($fechaActual));

    // Verificar si existe un registro del mes
    $queryReporte = $connection->prepare("SELECT * FROM reporte_mensual WHERE matricula_empresa = :matricula AND mes = :mes AND anio = :anio");
    $queryReporte->bindParam(":matricula", $matricula);
    $queryReporte->bindParam(":mes", $mes);
    $queryReporte->bindParam(":anio", $anio);
    $queryReporte->execute();

    if ($queryReporte->rowCount() > 0) {
        // Actualizar el peso total
        $reporte = $queryReporte->fetch(PDO::FETCH_ASSOC);
        $nuevoPesoTotal = $reporte['peso_total'] + $peso;

        $updateReporte = $connection->prepare("UPDATE reporte_mensual SET peso_total = :peso_total WHERE id = :id");
        $updateReporte->bindParam(":peso_total", $nuevoPesoTotal);
        $updateReporte->bindParam(":id", $reporte['id']);
        $updateReporte->execute();
    } else {
        // Insertar nuevo reporte
        $insertReporte = $connection->prepare("INSERT INTO reporte_mensual (matricula_empresa, mes, anio, peso_total) VALUES (:matricula, :mes, :anio, :peso_total)");
        $insertReporte->bindParam(":matricula", $matricula);
        $insertReporte->bindParam(":mes", $mes);
        $insertReporte->bindParam(":anio", $anio);
        $insertReporte->bindParam(":peso_total", $peso);
        $insertReporte->execute();
    }
}

// Lógica para el corte mensual
$currentMonth = date('m');
$currentYear = date('Y');

// Verificar la fecha de corte desde la base de datos
$queryCorte = $connection->prepare("SELECT fecha_corte FROM reporte_mensual WHERE mes = :mes AND anio = :anio");
$queryCorte->bindParam(":mes", $currentMonth);
$queryCorte->bindParam(":anio", $currentYear);
$queryCorte->execute();
$corteData = $queryCorte->fetch(PDO::FETCH_ASSOC);

if (!$corteData || empty($corteData['fecha_corte'])) {
    // Verificar si es el último día del mes
    if (date('d') == date('t')) {
        // Guardar la fecha de corte en la base de datos
        $corteQuery = $connection->prepare("UPDATE reporte_mensual SET fecha_corte = :fecha_corte WHERE mes = :mes AND anio = :anio");
        $corteFecha = date('Y-m-d H:i:s'); // Fecha y hora actual del corte
        $corteQuery->bindParam(":fecha_corte", $corteFecha);
        $corteQuery->bindParam(":mes", $currentMonth);
        $corteQuery->bindParam(":anio", $currentYear);
        
        // Ejecutar la consulta de corte mensual
        if ($corteQuery->execute()) {
?>
<div class="row g-0">
    <div class="col-12 ui-bg-overlay-container p-3">
        <div class="toast-container w-100">
            <div class="bs-toast toast fade show bg-primary w-100" role="alert" aria-live="assertive"
                aria-atomic="true">
                <div class="toast-header">
                    <div class="d-flex w-100 justify-content-between flex-wrap">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-check me-2"></i>
                            <div class="fw-semibold">Notificacion</div>
                        </div>
                    </div>
                </div>
                <div class="toast-body">
                    <?php echo "Corte mensual realizado en la fecha: " . $corteFecha; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
        } else {
            echo '<div class="row g-0">
    <div class="col-12 ui-bg-overlay-container p-3">
        <div class="alert alert-danger" role="alert">
            Error al realizar el corte mensual. Por favor, inténtalo de nuevo.
        </div>
    </div>
</div>';
        }
    }
}
?>