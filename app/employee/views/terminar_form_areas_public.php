<?php
$titlePage = "Actualizacion areas pubicas";
require_once("../components/navbar.php");
$today = date('Y-m-d');

if (isset($_GET['stmp'])) {
    // Obtener el valor del parámetro 'stmp'
    $id_registro = $_GET['stmp'];
    

// Preparamos la consulta para buscar el usuario
$queryRegis = $connection->prepare("SELECT   areas_publicas.*, 
                                labores.id_labor, 
                                labores.labor, 
                                usuarios.documento, 
                                usuarios.nombres, 
                                usuarios.apellidos, 
                                ciudades.id_ciudad, 
                                ciudades.ciudad, 
                                estados.id_estado,
                                estados.estado
            
                                FROM  areas_publicas 
                                INNER JOIN usuarios ON areas_publicas.documento = usuarios.documento 
                                INNER JOIN ciudades ON areas_publicas.id_ciudad = ciudades.id_ciudad 
                                INNER JOIN labores ON areas_publicas.id_labor = labores.id_labor 
                                INNER JOIN estados ON areas_publicas.id_estado = estados.id_estado 
                                WHERE id_registro = :id_registro");
$queryRegis->bindParam(":id_registro", $id_registro);
$queryRegis->execute();
$Register = $queryRegis->fetch(PDO::FETCH_ASSOC);
// nombre complet
} else {
    echo "No se ha proporcionado ningún id_registro en la URL.";
}


// nombre completo
$nombre_completo = $Register['nombres'] . ' ' . $Register['apellidos'];
$labor = $Register['labor']; 
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
                        <h3 class="fw-bold">FINALIZAR FORMULARIO DE <?= $labor ?> </h3>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST" enctype="multipart/form-data" autocomplete="off"
                            name="formFinishPublicAreas" onsubmit="disableSubmitButton(this);">
                            <div class="row">
                                <h5 class="mb-5 text-center"> <i class="bx bx-user"></i> Bienvenido(a)
                                    <?= $nombre_completo ?> al registro del
                                    formulario de <?= $labor ?> , te
                                    Invitamos a finalizarlo , Por Favor Completa La Siguiente Informacion </h5>
                                <!-- fecha_inicio -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="fecha_inicio">Fecha Inicio</label>
                                    <div class="input-group input-group-merge">
                                        <span id="nombre_area-span" class="input-group-text"><i
                                                class="fas fa-calendar"></i></span>
                                        <input type="date" required readonly class="form-control ps-2"
                                            name="fecha_inicio" id="fecha_inicio"
                                            value="<?php echo htmlspecialchars($Register['fecha_inicio']); ?>" />
                                    </div>
                                </div>


                                <!-- hora_inicio -->
                                <div class="mb-3 col-12 col-lg-6 col-xl-6">
                                    <label class="form-label" for="hora_inicio">Hora Inicio de Lavado</label>
                                    <div class="input-group input-group-merge">
                                        <span id="hora_inicio_span" class="input-group-text">
                                            <i class="fas fa-clock"></i>
                                        </span>
                                        <input type="time" readonly required class="form-control ps-2 "
                                            name="hora_inicio" id="hora_inicio"
                                            value="<?php echo htmlspecialchars($Register['hora_inicio']); ?>" />
                                    </div>
                                </div>


                                <!-- numero de documento -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="documento">Número de Documento</label>
                                    <div class="input-group input-group-merge">
                                        <span id="documento-icon" class="input-group-text">
                                            <i class="fas fa-id-card"></i>
                                        </span>
                                        <input type="text" minlength="6" maxlength="10" oninput="maxlengthNumber(this)"
                                            onkeypress="return multiplenumber(event);" class="form-control ps-2 "
                                            readonly required id="documento"
                                            value="<?php echo htmlspecialchars($Register['documento']); ?>"
                                            name="documento" placeholder="Ingresa tu número de documento" autofocus />
                                    </div>
                                </div>
                                <!-- nombres -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="nombres">Nombre Completo</label>
                                    <div class="input-group input-group-merge">
                                        <span id="nombres_span" class="input-group-text"><i
                                                class="fas fa-user"></i></span>
                                        <input type="text" required minlength="2" maxlength="100"
                                            class="form-control ps-2" name="nombres" id="nombres" readonly
                                            value="<?php echo ($nombre_completo); ?>"
                                            placeholder="Ingresar nombres completos" />
                                    </div>
                                </div>

                                <!-- ciudad -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="nombres">Ciudad</label>
                                    <div class="input-group input-group-merge">
                                        <span id="nombres_span" class="input-group-text"><i
                                                class="fas fa-city"></i></span>
                                        <input type="text" required minlength="2" maxlength="100"
                                            class="form-control ps-2" name="ciudad" id="ciudad" readonly
                                            value="<?php echo htmlspecialchars($Register['ciudad']); ?>"
                                            placeholder="Ingresa la ciudad" />
                                        <input type="hidden" name="id_ciudad" id="id_ciudad"
                                            value="<?php echo htmlspecialchars($Register['id_ciudad']); ?>"
                                            placeholder="Ingresa la ciudad" />
                                    </div>


                                </div>
                                <!-- fecha_fin -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="fecha_inicio">Fecha Finalizacion</label>
                                    <div class="input-group input-group-merge">
                                        <span id="nombre_area-span" class="input-group-text"><i
                                                class="fas fa-calendar-day"></i></span>
                                        <input type="date" required class="form-control ps-2" name="fecha_fin"
                                            id="fecha_fin" readonly min="<?php echo $today; ?>"
                                            max="<?php echo $today; ?>" value="<?php echo $today; ?>" />
                                    </div>
                                </div>
                                <!-- hora_fin -->
                                <div class="mb-3 col-12 col-lg-6 col-xl-6">
                                    <label class="form-label" for="hora_inicio">Hora finalizacion</label>
                                    <div class="input-group input-group-merge">
                                        <span id="hora_inicio_span" class="input-group-text">
                                            <i class="fas fa-clock"></i>
                                        </span>
                                        <input type="time" readonly required class="form-control ps-2" name="hora_fin"
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

                                <!-- peso -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="documento">Peso Final en KG</label>
                                    <div class="input-group input-group-merge">
                                        <span id="documento-icon" class="input-group-text">
                                            <i class="fas fa-weight-hanging"></i>
                                        </span>
                                        <input type="number" step="0.001" min="0" class="form-control ps-2 " required
                                            id="peso" name="peso" placeholder="Ingresa el peso de la poda" autofocus />
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


                                <div class="mt-4">
                                    <a href="index.php" class="btn btn-danger">
                                        Cancelar
                                    </a>
                                    <input type="submit" class="btn btn-primary" value="Registrar"></input>
                                    <input type="hidden" class="btn btn-info" value="formFinishPublicAreas"
                                        name="MM_formFinishPublicAreas"></input>
                                    <input type="hidden" class="btn btn-info"
                                        value="<?php echo htmlspecialchars($Register['id_registro']); ?>""
                                        name=" id_registro"></input>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php
    require_once("../components/footer.php");

    ?>