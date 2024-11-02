<?php
$titlePage = "Editar Registro Mecanica Veh. Compactador";
require_once("../components/sidebar.php");

if (isset($_GET['id_registro'])) {
    $id_registro = $_GET['id_registro'];

    // Consulta para obtener los datos del Registro Mecanica Veh. Compactador por ID
    $getMecanicaById = $connection->prepare("SELECT mvc.*, e.estado, ac.actividad, u.documento, u.nombres, u.apellidos, v.placa, v.vehiculo FROM mecanica AS mvc INNER JOIN actividades AS ac ON mvc.id_actividad = ac.id_actividad INNER JOIN usuarios AS u ON mvc.documento = u.documento INNER JOIN estados AS e ON mvc.id_estado = e.id_estado INNER JOIN vehiculos AS v ON mvc.id_vehiculo = v.placa WHERE mvc.id_registro = :id_registro");
    $getMecanicaById->bindParam(":id_registro", $id_registro);
    $getMecanicaById->execute();
    $mecanica = $getMecanicaById->fetch(PDO::FETCH_ASSOC);
    if ($mecanica) {
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Layout -->
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header justify-content-between align-items-center">
                        <h3 class="fw-bold py-2">Editar Registro Mecanica Veh. Compactador - ID:
                            <?php echo $mecanica['id_registro'] ?>
                        </h3>
                        <h6 class="mb-0">Por favor edita los datos necesarios del Registro Mecanica Veh. Compactador.
                        </h6>
                    </div>
                    <div class="card-body">
                        <form action="" name="FormMecanica" method="POST" enctype="multipart/form-data"
                            autocomplete="off">
                            <div class="row">
                                <input type="hidden" name="id_registro" value="<?php echo $id_registro ?>">
                                <!-- Estado -->
                                <h6 class="mb-3 fw-bold"><i class="bx bx-map"></i> Datos del Registro Mecanica Veh.
                                    Compactador</h6>
                                <div class="mb-3 col-12 col-lg-6">
                                    <label for="estado" class="form-label">Estado</label>
                                    <div class="input-group input-group-merge">
                                        <span id="estado-icon" class="input-group-text"><i
                                                class="fas fa-check-circle"></i></span>
                                        <select class="form-select" name="id_estado" required>
                                            <option value="<?php echo $mecanica['id_estado'] ?>">
                                                <?php echo $mecanica['estado'] ?></option>

                                        </select>
                                    </div>
                                </div>

                                <!-- Conductor Asignado -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label for="documento" class="form-label">Empleado</label>
                                    <div class="input-group input-group-merge">
                                        <span id="documento-icon" class="input-group-text"><i
                                                class="fas fa-user"></i></span>
                                        <select class="form-select" name="documento" required>
                                            <option value="<?php echo $mecanica['documento'] ?>">
                                                <?php echo $mecanica['nombres'] . ' ' . $mecanica['apellidos'] ?>
                                            </option>

                                        </select>
                                    </div>
                                </div>
                                <!-- Labor Mantenimiento -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label for="labor_mantenimiento" class="form-label">Labor Mantenimiento</label>
                                    <div class="input-group input-group-merge">
                                        <span id="labor_mantenimiento-icon" class="input-group-text"><i
                                                class="fas fa-user"></i></span>
                                        <input type="text" class="form-control" name="labor_mantenimiento"
                                            value="<?php echo $mecanica['labor_mantenimiento'] ?>" required />
                                    </div>
                                </div>
                                <!-- vehiculo -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label for="placa" class="form-label">Vehiculo</label>
                                    <div class="input-group input-group-merge">
                                        <span id="placa-icon" class="input-group-text"><i
                                                class="fas fa-truck"></i></span>
                                        <select class="form-select" name="id_vehiculo" required>
                                            <option value="<?php echo $mecanica['placa'] ?>">
                                                <?php echo $mecanica['placa'] ?> - <?php echo $mecanica['vehiculo'] ?>
                                            </option>
                                            <?php
                                                    $listVehiculos = $connection->prepare("SELECT * FROM vehiculos");
                                                    $listVehiculos->execute();
                                                    $vehiculos = $listVehiculos->fetchAll(PDO::FETCH_ASSOC);
                                                    foreach ($vehiculos as $vehiculo) {
                                                        echo "<option value='{$vehiculo['placa']}'>{$vehiculo['placa']} {$vehiculo['vehiculo']}</option>";
                                                    }
                                                    ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- Fecha Inicio -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                                    <div class="input-group input-group-merge">
                                        <span id="fecha_inicio-icon" class="input-group-text"><i
                                                class="fas fa-calendar"></i></span>
                                        <input type="date" class="form-control" name="fecha_inicio"
                                            value="<?php echo $mecanica['fecha_inicio'] ?>" required />
                                    </div>
                                </div>

                                <!-- Fecha Finalización -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label for="fecha_fin" class="form-label">Fecha de Finalización</label>
                                    <div class="input-group input-group-merge">
                                        <span id="fecha_fin-icon" class="input-group-text"><i
                                                class="fas fa-calendar-day"></i></span>
                                        <input type="date" class="form-control" name="fecha_fin"
                                            value="<?php echo $mecanica['fecha_fin'] ?>" />
                                    </div>
                                </div>

                                <!-- Hora Inicio -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label for="hora_inicio" class="form-label">Hora Inicio</label>
                                    <div class="input-group input-group-merge">
                                        <span id="hora_inicio-icon" class="input-group-text"><i
                                                class="fas fa-clock"></i></span>
                                        <input type="time" class="form-control" name="hora_inicio"
                                            value="<?php echo $mecanica['hora_inicio'] ?>" required />
                                    </div>
                                </div>

                                <!-- Hora Finalización -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label for="hora_finalizacion" class="form-label">Hora Finalización</label>
                                    <div class="input-group input-group-merge">
                                        <span id="hora_finalizacion-icon" class="input-group-text"><i
                                                class="fas fa-clock"></i></span>
                                        <input type="time" class="form-control" name="hora_finalizacion"
                                            value="<?php echo $mecanica['hora_finalizacion'] ?>" />
                                    </div>
                                </div>
                                <!-- Observaciones -->
                                <div class="mb-3 col-12">
                                    <label for="observaciones" class="form-label">Observaciones</label>
                                    <div class="input-group input-group-merge">
                                        <span id="observaciones-icon" class="input-group-text"><i
                                                class="fas fa-comment-alt"></i></span>
                                        <textarea class="form-control" name="observaciones" rows="3"
                                            placeholder="Escriba observaciones..."><?php echo $mecanica['observaciones'] ?></textarea>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <a href="index.php" class="btn btn-danger">Cancelar</a>
                                    <input type="submit" class="btn btn-primary" value="Actualizar">
                                    <input type="hidden" class="btn btn-info" value="FormMecanica"
                                        name="MM_FormMecanica"></input>
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
    } else {
        showErrorOrSuccessAndRedirect("error", "Error de ruta", "Los datos del Registro Mecanica Veh. Compactador no fueron encontrados", "mecanica_vehiculo.php");
    }
} else {
    showErrorOrSuccessAndRedirect("error", "Error de ruta", "Los datos del Registro Mecanica Veh. Compactador no fueron encontrados", "mecanica_vehiculo.php");
}
    ?>