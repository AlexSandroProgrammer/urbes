<?php
$titlePage = "Terminar Registro Form. Vehiculo Compactador";
require_once("../components/navbar.php");
// asignamos la query a una variable
$documento = $_SESSION['documento'];
// Preparamos la consulta para buscar el usuario
$queryUser = $connection->prepare("SELECT * FROM usuarios WHERE documento = :documento");
$queryUser->bindParam(":documento", $documento);
$queryUser->execute();
$user = $queryUser->fetch(PDO::FETCH_ASSOC);
// nombre completo
$nombre_completo = $user['nombres'] . ' ' . $user['apellidos'];
$tipo_usuario = $_SESSION['id_rol'];
if ($tipo_usuario != 4) {
    // El usuario no es un conductor, redirige al index con un mensaje de error
    showErrorOrSuccessAndRedirect("error", "Error de usuario", "No eres un conductor por lo tanto no puedes ingresar a este formulario", "index.php");
    exit();
}
// fecha inicio
$fecha_final = date('Y-m-d');
if (isNotEmpty([$_GET['stmp']])) {
    $stmp = $_GET['stmp'];
    // Preparamos la consulta para obtener los datos del registro
$query = "
    SELECT 
        vehiculo_compactador.*,
        labores.id_labor,
        labores.labor,
        vehiculos.placa,
        vehiculos.vehiculo,
        usuarios.documento,
        usuarios.nombres,
        usuarios.apellidos,
        ciudades.id_ciudad,
        ciudades.ciudad,
        estados.id_estado,
        estados.estado
        
    FROM 
        vehiculo_compactador 
    INNER JOIN 
        labores ON vehiculo_compactador.id_labor = labores.id_labor 
    INNER JOIN 
        vehiculos ON vehiculo_compactador.id_vehiculo = vehiculos.placa 
    INNER JOIN 
        usuarios ON vehiculo_compactador.documento = usuarios.documento 
    JOIN 
        ciudades ON vehiculo_compactador.ciudad = ciudades.id_ciudad 
    INNER JOIN 
        estados ON vehiculo_compactador.id_estado = estados.id_estado 
 
    WHERE 
        vehiculo_compactador.id_registro_veh_compactador = :id_registro_veh_compact;
";

// Ejecutamos la query
$execute = $connection->prepare($query);
$execute->bindParam(":id_registro_veh_compact", $stmp);
$execute->execute();
$data = $execute->fetch(PDO::FETCH_ASSOC);
    
    if (isEmpty([$data])) {
        showErrorOrSuccessAndRedirect('error', "Lo sentimos...!", "Error al momento de obtener los datos del registro ", "index.php");
        exit();
    }
    // CONSULTA PARA TRAER DETALLE DE TRIPULACION
    $id_registro = $data['id_registro_veh_compactador'];

    $employees = $connection->prepare("SELECT * FROM detalle_tripulacion INNER JOIN usuarios ON detalle_tripulacion.documento = usuarios.documento WHERE id_registro = :id_registro");
    $employees->bindParam(":id_registro", $id_registro);
    $employees->execute();
    $detalle = $employees->fetchAll(PDO::FETCH_ASSOC);
    if (isEmpty([$detalle])) {
        echo 'No funciono';
    }
    $rutas = $connection->prepare("SELECT * FROM detalle_rutas INNER JOIN rutasr ON detalle_rutas.id_ruta= rutasr.id_ruta WHERE id_registro = :id_registro");
    $rutas->bindParam(":id_registro", $id_registro);
    $rutas->execute();
    $ruta = $rutas->fetchAll(PDO::FETCH_ASSOC);
    if (isEmpty([$rutas])) {
        echo 'No funciono';
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
                        <h3 class="fw-bold">EDITAR DATOS VEHICULO COMPACTADOR</h3>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST" enctype="multipart/form-data" autocomplete="off"
                            name="formUpdateRecoleccion" onsubmit="disableSubmitButton(this);">
                            <div class="row">
                                <h5 class="mb-5 text-center"> <i class="bx bx-user"></i> Hola(a)
                                    <?= $nombre_completo ?>, te invitamos a terminar de rellanar al registro del
                                    formulario de vehiculo compactador de tipo <?= $data['labor'] ?>. </h5>
                                <!-- fecha_inicio -->
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label class="form-label" for="fecha_inicio">Fecha Inicio</label>
                                    <div class="input-group input-group-merge">
                                        <span id="nombre_area-span" class="input-group-text"><i
                                                class="fas fa-calendar-day"></i></span>
                                        <input type="date" required readonly="readonly" class="form-control ps-2"
                                            value="<?= $data['fecha_inicio'] ?>" id="fecha_inicio" />
                                    </div>
                                </div>

                                <input type="hidden" name="id_registro_veh_compactador"
                                    value="<?= $data['id_registro_veh_compactador'] ?>">
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label class="form-label" for="fecha_final">Fecha Final</label>
                                    <div class="input-group input-group-merge">
                                        <span id="nombre_area-span" class="input-group-text"><i
                                                class="fas fa-calenday-day"></i></span>
                                        <input type="date" required readonly="readonly" class="form-control ps-2"
                                            value="<?= $fecha_final ?>" name="fecha_final" id="fecha_final" />
                                    </div>
                                </div>
                                <!-- equipo de transporte -->
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label for="vehiculo" class="form-label">Equipo de Transporte</label>
                                    <div class="input-group input-group-merge">
                                        <span id="vehiculo-2" class="input-group-text"><i
                                                class="fas fa-truck"></i></span>
                                        <input type="text" required readonly="readonly" class="form-control ps-2"
                                            value="<?= $data['vehiculo'] ?> <?= $data['placa'] ?>" id="vehiculo" />
                                    </div>
                                </div>
                                <?php
                                    if ($user['id_tipo_usuario'] == 4) {
                                    ?>
                                <!-- numero de documento -->
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label class="form-label" for="documento">CONDUCTOR ENCARGADO DE REGISTRO</label>
                                    <div class="input-group input-group-merge">
                                        <span id="documento-icon" class="input-group-text"><i
                                                class="fas fa-id-card"></i></span>
                                        <input type="text" minlength="6" maxlength="10" readonly="readonly"
                                            value="<?= $data['documento'] ?>" class="form-control ps-2" required
                                            id="documento" placeholder="Ingresa tu numero de documento" />
                                    </div>
                                </div>
                                <?php
                                    }
                                    ?>
                                <!-- hora_inicio -->
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label class="form-label" for="hora_inicio">Hora Inicio de Recolección</label>
                                    <div class="input-group input-group-merge">
                                        <span id="hora_inicio_span" class="input-group-text">
                                            <i class="fas fa-clock"></i>
                                        </span>
                                        <input type="time" readonly="readonly" required
                                            value="<?= $data['hora_inicio'] ?>" class="form-control ps-2"
                                            id="hora_inicio" />
                                    </div>
                                </div>
                                <!-- hora_finalizacion -->
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label class="form-label" for="hora_finalizacion">Hora Fin de Recolección</label>
                                    <div class="input-group input-group-merge">
                                        <span id="hora_finalizacion_span" class="input-group-text">
                                            <i class="fas fa-clock"></i>
                                        </span>
                                        <input type="time" readonly="readonly" required class="form-control ps-2"
                                            name="hora_finalizacion" id="hora_finalizacion" />
                                    </div>
                                </div>
                                <script>
                                // Función para actualizar la hora en el campo de hora_finalizacion
                                function actualizarHora() {
                                    // Obtener la hora actual
                                    const ahora = new Date();
                                    // Formatear la hora en formato HH:MM (24 horas)
                                    const horas = String(ahora.getHours()).padStart(2, '0');
                                    const minutos = String(ahora.getMinutes()).padStart(2, '0');
                                    // Actualizar el valor del input con la hora formateada
                                    document.getElementById('hora_finalizacion').value = `${horas}:${minutos}`;
                                }
                                // Actualizar la hora cada segundo
                                setInterval(actualizarHora, 1000);
                                // Llamar a la función inmediatamente para establecer la hora inicial
                                actualizarHora();
                                </script>

                                <!-- foto_kilometraje inicial -->
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label class="form-label" for="foto_kilometraje">Foto del Kilometraje
                                        Inicial</label>
                                    <div class="input-group input-group-merge text-center">
                                        <img src="../assets/images/<?= $data['foto_kilometraje_inicial'] ?>" width="150"
                                            alt="No se encontro foto del kilometraje inicial">
                                    </div>
                                </div>

                                <!-- kilometraje inicial -->
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label class="form-label" for="kilometraje">Kilometraje Inicial</label>
                                    <div class="input-group input-group-merge">
                                        <span id="kilometraje_span" class="input-group-text"><i
                                                class="fas fa-road"></i></span>
                                        <input type="number" step="0.001" value="<?= $data['km_inicio'] ?>"
                                            readonly="readonly" required class="form-control ps-2" id="kilometraje"
                                            placeholder="Ingresar kilometraje" />
                                    </div>
                                </div>

                                <!-- foto_kilometraje final -->
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label class="form-label" for="foto_kilometraje_final">Foto del Kilometraje
                                        Final</label>
                                    <div class="input-group input-group-merge">
                                        <span id="nombre_area-span" class="input-group-text"><i
                                                class="fas fa-camera"></i></span>
                                        <input type="file" accept="image/*" class="form-control ps-2"
                                            name="foto_kilometraje_final" id="foto_kilometraje_final" />

                                    </div>
                                </div>

                                <!-- kilometraje -->
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label class="form-label" for="kilometraje_final">Kilometraje Final</label>
                                    <div class="input-group input-group-merge">
                                        <span id="kilometraje_span" class="input-group-text"><i
                                                class="fas fa-road"></i></span>
                                        <input type="number" step="0.001" min="0" class="form-control ps-2"
                                            name="kilometraje_final" id="kilometraje_final"
                                            placeholder="Ingresar kilometraje" />
                                    </div>
                                </div>
                                <!-- horometro -->
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label class="form-label" for="horometro">Horometro Inicial</label>
                                    <div class="input-group input-group-merge">
                                        <span id="horometro_span" class="input-group-text"><i
                                                class="fas fa-clock"></i></span>
                                        <input type="number" step="0.01" min="0" required
                                            value="<?= $data['horometro_inicio'] ?>" readonly="readonly"
                                            class="form-control ps-2" id="horometro" placeholder="Ingresar horometro" />
                                    </div>
                                </div>
                                <!-- horometro final -->
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label class="form-label" for="horometro_final">Horometro Final</label>
                                    <div class="input-group input-group-merge">
                                        <span id="horometro_span" class="input-group-text"><i
                                                class="fas fa-clock"></i></span>
                                        <input type="number" step="0.001" min="0" required class="form-control ps-2"
                                            id="horometro_final" name="horometro_final"
                                            placeholder="Ingresar horometro final" />
                                    </div>
                                </div>
                                <div class="mb-3 col-12 col-lg-6 col-xl-6">
                                    <label for="ciudad" class="form-label">Ciudad de Recoleccion</label>
                                    <div class="input-group input-group-merge">
                                        <span id="ciudad-2" class="input-group-text"><i class="fas fa-city"></i></span>
                                        <input type="text" class="form-control ps-2" id="ciudad" name="ciudad"
                                            placeholder="Ingresar ciudad" readonly="readonly"
                                            value="<?= $data['ciudad'] ?>" />
                                    </div>
                                </div>
                                <!-- Mostrar todas las zonas -->
                                <!-- Zonas en un select multiple -->
                                <div class="mb-3 col-12 col-lg-6 col-xl-6">
                                    <label class="form-label" for="rutas">Rutas</label>
                                    <div class="input-group input-group-merge">
                                        <span id="zonas_span" class="input-group-text"><i
                                                class="fas fa-map-marker-alt"></i></span>
                                        <select class="form-select " id="rutas" name="rutas[]" multiple readonly>
                                            <?php 
                                                foreach ($ruta as $rut) {
                                                echo '<option value="' . htmlspecialchars($rut['id_ruta']) . '" selected>' . htmlspecialchars($rut['ruta']) . '</option>';
                                                 }
                                             ?>
                                        </select>
                                    </div>
                                </div>


                                <div class="mb-3 col-12 col-lg-6 col-xl-8">
                                    <label for="observaciones" class="form-label">Observaciones</label>
                                    <div class="input-group input-group-merge">
                                        <span id="observaciones-2" class="input-group-text">
                                            <i class="fas fa-weight-hanging"></i>
                                        </span>
                                        <textarea required class="form-control ps-2" id="observaciones" rows="5"
                                            name="observaciones" placeholder="Ingresar observación"></textarea>
                                    </div>
                                </div>

                                <!-- Contenedor de los empleados -->
                                <div class="mb-3 col-12" id="empleados">
                                    <!-- Mostrar la lista de empleados -->
                                    <h5>Tripulacion Asignada</h5>
                                    <ul class="list-group">
                                        <?php foreach ($detalle as $employee): ?>
                                        <li class="list-group-item">
                                            <strong>Nombre Completo:</strong>
                                            <?php echo htmlspecialchars($employee['nombres'] . ' ' . $employee['apellidos']); ?><br>
                                            <strong>Documento:</strong>
                                            <?php echo htmlspecialchars($employee['documento']); ?><br>

                                        </li>
                                        <?php endforeach; ?>
                                    </ul>

                                </div>
                                <input type="hidden" id="empleadosInput" name="empleados">
                                <div class="mt-4">
                                    <!-- Botón de Cancelar -->
                                    <a href="index.php" class="btn btn-danger" id="cancelarBtn">
                                        Cancelar
                                    </a>
                                    <input type="submit" class="btn btn-primary" value="Registrar"></input>
                                    <input type="hidden" class="btn btn-info" value="formUpdateRecoleccion"
                                        name="MM_formUpdateRecoleccion"></input>
                                </div>
                            </div>
                        </form>
                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const cancelarBtn = document.getElementById('cancelarBtn');
                            // Escucha el evento click en el botón Cancelar
                            cancelarBtn.addEventListener('click', function(event) {
                                // Elimina la propiedad que desees del localStorage
                                localStorage.removeItem(
                                    'empleados'
                                ); // Ajusta según el nombre de la propiedad a eliminar

                                window.location.href("index.php");
                            });
                        });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
        require_once("../components/footer.php");

        ?>


    <?php
} else {
    showErrorOrSuccessAndRedirect("error", "Parametros incorrectos", "No puedes ingresar en esta pagina", "index.php");
}
    ?>