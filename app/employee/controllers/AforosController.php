<?php
function enviarAforos($datos)
{
    $url = 'https://script.google.com/macros/s/AKfycbwr5wWo7PJMUrD8ocxcuvJXF8HtS7Puv9zKaamjq0PsQFgnGB4wR2xe4fdQmzS7XuYP2g/exec'; // Reemplaza con la URL de tu aplicación web de Google Apps Script

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
    $pesoTotal = 0; // Acumulador para el peso total
    $fechaActual = $_POST['fecha_registro'] ?? date('Y-m-d');
    $nombres = $_POST['nombres'];

    // Arreglos para pesos y fotos
    $pesos = [];
    $fotos = [];

    // Extraer pesos y fotos
    for ($i = 1; $i <= 5; $i++) {
        $peso = filter_input(INPUT_POST, 'peso' . $i, FILTER_VALIDATE_FLOAT);
        $foto = $_FILES['foto_aforo' . $i] ?? null;

        // Almacenar pesos
        $pesos[$i] = $peso ? $peso : null; // Guardar null si no hay peso

        if ($peso) {
            $pesoTotal += $peso; // Sumar al peso total
        }

        // Manejar fotos
        if ($foto) {
            $fotos[$i] = manejarImagenSubida('foto_aforo' . $i, "../assets/images/", 5000);
        } else {
            $fotos[$i] = null; // Guardar null si no se sube una foto
        }
    }

    // Verificar campos obligatorios
    if (empty($matricula) || empty($fechaActual)) {
        showErrorFieldsEmpty("aforos.php");
        exit();
    }

    // Insertar aforo
    $insertAforo = $connection->prepare("
        INSERT INTO aforos (matricula_empresa, fecha_registro, peso_1, peso_2, peso_3, peso_4, peso_5, peso, foto_1, foto_2, foto_3, foto_4, foto_5, documento) 
        VALUES (:matricula, :fecha_registro, :peso1, :peso2, :peso3, :peso4, :peso5, :peso_total, :foto1, :foto2, :foto3, :foto4, :foto5, :documento)"
    );

    // Asignar valores a los parámetros
    $insertAforo->bindParam(':matricula', $matricula);
    $insertAforo->bindParam(':fecha_registro', $fechaActual);
    $insertAforo->bindParam(':documento', $documento);

    // Asignar pesos
    $insertAforo->bindParam(':peso1', $pesos[1]);
    $insertAforo->bindParam(':peso2', $pesos[2]);
    $insertAforo->bindParam(':peso3', $pesos[3]);
    $insertAforo->bindParam(':peso4', $pesos[4]);
    $insertAforo->bindParam(':peso5', $pesos[5]);

    // Asignar peso total
    $insertAforo->bindParam(':peso_total', $pesoTotal);

    // Asignar fotos
    $foto1 = $fotos[1] ?? null;
    $foto2 = $fotos[2] ?? null;
    $foto3 = $fotos[3] ?? null;
    $foto4 = $fotos[4] ?? null;
    $foto5 = $fotos[5] ?? null;

    $insertAforo->bindParam(':foto1', $foto1);
    $insertAforo->bindParam(':foto2', $foto2);
    $insertAforo->bindParam(':foto3', $foto3);
    $insertAforo->bindParam(':foto4', $foto4);
    $insertAforo->bindParam(':foto5', $foto5);

    try {
        if ($insertAforo->execute()) {
            $id_registro = $connection->lastInsertId();
            manejarReporteMensual($matricula, $pesoTotal, $fechaActual);
            $registradorConcatenado = $documento . " (" . $nombres .  ")";

           
            // Enviar datos a Google Sheets
        $datos = [
            'id_registro' => $id_registro,
            'fecha_registro' => $fechaActual,
            'documento' => $registradorConcatenado,
            'peso 1' => $pesos[1] ?? 0, // Usar 0 si no hay peso
            'peso 2' => $pesos[2] ?? 0, // Usar 0 si no hay peso
            'peso 3' => $pesos[3] ?? 0, // Usar 0 si no hay peso
            'peso 4' => $pesos[4] ?? 0, // Usar 0 si no hay peso
            'peso 5' => $pesos[5] ?? 0, // Usar 0 si no hay peso
            'peso' => $pesoTotal,
            'tipo_actividad' => 'Aforos'
        ];

            enviarAforos($datos);

            // Verificar si es el último día del mes
            if (date('Y-m-d') == date('Y-m-t')) {
                manejarCorteMensual($matricula, $pesoTotal);
            }

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

// Nueva función para manejar el corte mensual
function manejarCorteMensual($matricula, $pesoTotal)
{
   

    $mes = date('m');
    $anio = date('Y');

    // Verificar si ya se ha realizado el corte
    $queryCorte = $connection->prepare("SELECT * FROM reporte_mensual WHERE matricula_empresa = :matricula AND mes = :mes AND anio = :anio");
    $queryCorte->bindParam(':matricula', $matricula);
    $queryCorte->bindParam(':mes', $mes);
    $queryCorte->bindParam(':anio', $anio);
    $queryCorte->execute();

    if ($queryCorte->rowCount() > 0) {
        // Actualizar el registro del corte
        $reporte = $queryCorte->fetch(PDO::FETCH_ASSOC);
        $updateCorte = $connection->prepare("UPDATE reporte_mensual SET fecha_corte = NOW() WHERE id = :id");
        $updateCorte->bindParam(":id", $reporte['id']);
        $updateCorte->execute();
    } else {
        // Si no hay corte, se puede agregar un mensaje o hacer algo adicional
        // showErrorOrSuccessAndRedirect("info", "Corte Mensual", "No hay registros para el corte mensual.", "aforos.php");
    }
}

// Lógica para el corte mensual
$currentMonth = date('m');
$currentYear = date('Y');
?>