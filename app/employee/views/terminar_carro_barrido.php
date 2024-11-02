<?php
$titlePage = "Actualizacion de Carro Barrido";
require_once("../components/navbar.php");
$today = date('Y-m-d');

if (isset($_GET['stmp'])) {
    // Obtener el valor del parámetro 'stmp'
    $id_registro = $_GET['stmp'];
    
    // Preparamos la consulta para buscar el registro con todas las zonas
    $queryRegis = $connection->prepare("
        SELECT 
            carro_barrido.*, 
            usuarios.documento, 
            usuarios.nombres, 
            usuarios.apellidos, 
            ciudades.id_ciudad, 
            ciudades.ciudad, 
            estados.id_estado,
            estados.estado,
            detalle_zonas.id_detalle_zona,
            zonas.id_zona,
            zonas.zona,
            actividades.actividad
        FROM carro_barrido
        INNER JOIN usuarios ON carro_barrido.documento = usuarios.documento 
        INNER JOIN ciudades ON carro_barrido.ciudad = ciudades.id_ciudad 
        INNER JOIN actividades ON carro_barrido.id_actividad = actividades.id_actividad
        INNER JOIN estados ON carro_barrido.id_estado = estados.id_estado 
        INNER JOIN detalle_zonas ON carro_barrido.id_registro_barrido = detalle_zonas.id_registro
        INNER JOIN zonas ON detalle_zonas.id_zona = zonas.id_zona
        WHERE carro_barrido.id_registro_barrido = :id_registro
    ");
    $queryRegis->bindParam(":id_registro", $id_registro, PDO::PARAM_INT);
    $queryRegis->execute();
    $results = $queryRegis->fetchAll(PDO::FETCH_ASSOC); // Obtener todas las filas

    if (!empty($results)) {
        // Acceder al primer registro del resultado
        $carroBarrido = $results[0];

        // nombre completo
        $nombre_completo = $carroBarrido['nombres'] . ' ' . $carroBarrido['apellidos'];
        $actividad = $carroBarrido['actividad'];
    } else {
        echo "No se encontraron datos para el id_registro proporcionado.";
        exit;
    }
} else {
    echo "No se ha proporcionado ningún id_registro en la URL.";
    exit;
}
?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Layout -->
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header mt-3 justify-content-between align-items-center text-center">
                        <h3 class="fw-bold">FINALIZAR FORMULARIO DE <?= $actividad ?> </h3>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST" enctype="multipart/form-data" autocomplete="off"
                            name="formFinishSweepingCart" onsubmit="disableSubmitButton(this);">
                            <div class="row">
                                <h5 class="mb-5 text-center"> <i class="bx bx-user"></i> Bienvenido(a)
                                    <?= $nombre_completo ?> al registro del formulario de <?= $actividad ?>, te
                                    invitamos a finalizarlo. Por favor, completa la siguiente información.
                                </h5>

                                <!-- fecha_inicio -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="fecha_inicio">Fecha Inicio</label>
                                    <div class="input-group input-group-merge">
                                        <span id="nombre_area-span" class="input-group-text"><i
                                                class="fas fa-calendar"></i></span>
                                        <input type="date" required readonly class="form-control ps-2"
                                            name="fecha_inicio" id="fecha_inicio"
                                            value="<?= htmlspecialchars($carroBarrido['fecha_inicio']); ?>" />
                                    </div>
                                </div>

                                <!-- hora_inicio -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="hora_inicio">Hora Inicio</label>
                                    <div class="input-group input-group-merge">
                                        <span id="hora_inicio_span" class="input-group-text"><i
                                                class="fas fa-clock"></i></span>
                                        <input type="time" readonly required class="form-control ps-2"
                                            name="hora_inicio" id="hora_inicio"
                                            value="<?= htmlspecialchars($carroBarrido['hora_inicio']); ?>" />
                                    </div>
                                </div>

                                <!-- documento -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="documento">Número de Documento</label>
                                    <div class="input-group input-group-merge">
                                        <span id="documento-icon" class="input-group-text"><i
                                                class="fas fa-id-card"></i></span>
                                        <input type="text" minlength="6" maxlength="10" class="form-control ps-2"
                                            readonly required id="documento"
                                            value="<?= htmlspecialchars($carroBarrido['documento']); ?>"
                                            name="documento" />
                                    </div>
                                </div>

                                <!-- nombres -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="nombres">Nombre Completo</label>
                                    <div class="input-group input-group-merge">
                                        <span id="nombres_span" class="input-group-text"><i
                                                class="fas fa-user"></i></span>
                                        <input type="text" required class="form-control ps-2" name="nombres"
                                            id="nombres" readonly value="<?= $nombre_completo; ?>" />
                                    </div>
                                </div>

                                <!-- ciudad -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="ciudad">Ciudad</label>
                                    <div class="input-group input-group-merge">
                                        <span id="ciudad_span" class="input-group-text"><i
                                                class="fas fa-city"></i></span>
                                        <input type="text" required class="form-control ps-2" name="ciudad" id="ciudad"
                                            readonly value="<?= htmlspecialchars($carroBarrido['ciudad']); ?>" />
                                    </div>
                                </div>

                                <!-- Mostrar todas las zonas -->
                                <!-- Zonas en un select multiple -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="zonas">Zonas</label>
                                    <div class="input-group input-group-merge">
                                        <span id="zonas_span" class="input-group-text"><i
                                                class="fas fa-map-marker-alt"></i></span>
                                        <select class="form-select " id="zonas" name="zonas[]" multiple readonly>
                                            <?php 
                                                foreach ($results as $row) {
                                                echo '<option value="' . htmlspecialchars($row['id_zona']) . '" selected>' . htmlspecialchars($row['zona']) . '</option>';
                                                 }
                                             ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- fecha_fin -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="fecha_fin">Fecha Finalización</label>
                                    <div class="input-group input-group-merge">
                                        <span id="fecha_fin_span" class="input-group-text"><i
                                                class="fas fa-calendar-day"></i></span>
                                        <input type="date" required class="form-control ps-2" name="fecha_fin"
                                            id="fecha_fin" readonly value="<?= $today; ?>" />
                                    </div>
                                </div>

                                <!-- hora_fin (rellenado por el usuario) -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="hora_fin">Hora Finalización</label>
                                    <div class="input-group input-group-merge">
                                        <span id="hora_fin_span" class="input-group-text"><i
                                                class="fas fa-clock"></i></span>
                                        <input type="time" required class="form-control ps-2" readonly name="hora_fin"
                                            id="hora_fin" />
                                    </div>
                                </div>
                                <script>
                                // Función para actualizar la hora en el campo de hora_inicio
                                function actualizarHora() {
                                    // Obtener la hora actual
                                    const ahora = new Date();
                                    // Formatear la hora en formato HH:MM (24 horas)
                                    const horas = String(ahora.getHours()).padStart(2, '0');
                                    const minutos = String(ahora.getMinutes()).padStart(2, '0');

                                    // Actualizar el valor del input con la hora formateada
                                    document.getElementById('hora_fin').value = `${horas}:${minutos}`;
                                }
                                // Actualizar la hora cada segundo
                                setInterval(actualizarHora, 1000);
                                // Llamar a la función inmediatamente para establecer la hora inicial
                                actualizarHora();
                                </script>

                                <!-- peso final -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="peso">Peso Final Barrido en KG</label>
                                    <div class="input-group input-group-merge">
                                        <span id="peso_span" class="input-group-text"><i
                                                class="fas fa-weight-hanging"></i></span>
                                        <input type="number" step="0.001" min="0" class="form-control ps-2" required
                                            id="peso" name="peso" placeholder="Ingresa el peso Barrido" />
                                    </div>
                                </div>

                                <!-- observaciones -->
                                <div class="mb-3 col-12 d-flex justify-content-center">
                                    <div class="col-12 col-lg-6">
                                        <div class="text-center mt-3">
                                            <label class="form-label" for="observaciones">Novedades y
                                                Observaciones</label>
                                        </div>
                                        <div class="input-group input-group-merge">
                                            <span id="observaciones-icon" class="input-group-text">
                                                <i class="fas fa-weight-hanging"></i>
                                            </span>
                                            <textarea minlength="0" maxlength="500" class="form-control ps-2" required
                                                id="observacion" name="observacion"
                                                placeholder="Ingresa las novedades y observaciones aquí" rows="3"
                                                autofocus></textarea>
                                        </div>
                                    </div>
                                </div>


                                <!-- botones -->
                                <div class="mt-4">
                                    <a href="index.php" class="btn btn-danger">Cancelar</a>
                                    <input type="submit" class="btn btn-primary" value="Registrar">
                                    <input type="hidden" class="btn btn-info" value="formFinishSweepingCart"
                                        name="MM_formFinishSweepingCart"></input>
                                    <input type="hidden" name="id_registro"
                                        value="<?= htmlspecialchars($carroBarrido['id_registro_barrido']); ?>">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once("../components/footer.php"); ?>