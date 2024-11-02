<?php
$titlePage = "Editar Área Pública";
require_once("../components/sidebar.php");

if (isset($_GET['id_registro'])) {
    $id_registro = $_GET['id_registro'];

    // Consulta para obtener los datos del área pública por ID
    $getAreaPublicaById = $connection->prepare("SELECT ap.*, e.estado, l.labor, u.nombres, u.apellidos, u.documento, c.ciudad
        FROM areas_publicas AS ap
        INNER JOIN labores AS l ON ap.id_labor = l.id_labor
        INNER JOIN usuarios AS u ON ap.documento = u.documento
        INNER JOIN estados AS e ON ap.id_estado = e.id_estado
        INNER JOIN ciudades AS c ON ap.id_ciudad = c.id_ciudad
        WHERE ap.id_registro = :id_registro");
    $getAreaPublicaById->bindParam(":id_registro", $id_registro);
    $getAreaPublicaById->execute();
    $areaPublica = $getAreaPublicaById->fetch(PDO::FETCH_ASSOC);
    if ($areaPublica) {
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
                        <h3 class="fw-bold py-2">Editar Área Pública - ID: <?php echo $areaPublica['id_registro'] ?>
                        </h3>
                        <h6 class="mb-0">Por favor edita los datos necesarios del área pública.</h6>
                    </div>
                    <div class="card-body">
                        <form action="" name="FormUpdatePublic" method="POST" enctype="multipart/form-data"
                            autocomplete="off">
                            <div class="row">
                                <input type="hidden" name="id_registro" value="<?php echo $id_registro ?>">

                                <!-- Estado -->
                                <h6 class="mb-3 fw-bold"><i class="bx bx-map"></i> Datos del Área Pública</h6>
                                <div class="mb-3 col-12 col-lg-6">
                                    <label for="estado" class="form-label">Estado</label>
                                    <div class="input-group input-group-merge">
                                        <span id="estado-icon" class="input-group-text"><i
                                                class="fas fa-check-circle"></i></span>
                                        <select class="form-select" name="estado" required>
                                            <option value="<?php echo $areaPublica['id_estado'] ?>">
                                                <?php echo $areaPublica['estado'] ?></option>

                                        </select>
                                    </div>
                                </div>

                                <!-- Labor -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label for="labor" class="form-label">Labor</label>
                                    <div class="input-group input-group-merge">
                                        <span id="labor-icon" class="input-group-text"><i
                                                class="fas fa-tasks"></i></span>
                                        <select class="form-select" name="labor" required>
                                            <option value="<?php echo $areaPublica['id_labor'] ?>">
                                                <?php echo $areaPublica['labor'] ?></option>
                                            <?php
                                                    $listLabores = $connection->prepare("SELECT * FROM labores WHERE id_labor = 6 OR id_labor = 7 OR id_labor = 8");
                                                    $listLabores->execute();
                                                    $labores = $listLabores->fetchAll(PDO::FETCH_ASSOC);
                                                    foreach ($labores as $labor) {
                                                        echo "<option value='{$labor['id_labor']}'>{$labor['labor']}</option>";
                                                    }
                                                    ?>
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
                                            <option value="<?php echo $areaPublica['documento'] ?>">
                                                <?php echo $areaPublica['nombres'] . ' ' . $areaPublica['apellidos'] ?>
                                            </option>

                                        </select>
                                    </div>
                                </div>

                                <!-- Ciudad -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label for="ciudad" class="form-label">Ciudad</label>
                                    <div class="input-group input-group-merge">
                                        <span id="ciudad-icon" class="input-group-text"><i
                                                class="fas fa-city"></i></span>
                                        <select class="form-select" name="ciudad" required>
                                            <option value="<?php echo $areaPublica['id_ciudad'] ?>">
                                                <?php echo $areaPublica['ciudad'] ?></option>

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
                                            value="<?php echo $areaPublica['fecha_inicio'] ?>" required />
                                    </div>
                                </div>

                                <!-- Fecha Finalización -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label for="fecha_finalizacion" class="form-label">Fecha de Finalización</label>
                                    <div class="input-group input-group-merge">
                                        <span id="fecha_finalizacion-icon" class="input-group-text"><i
                                                class="fas fa-calendar"></i></span>
                                        <input type="date" class="form-control" name="fecha_finalizacion"
                                            value="<?php echo $areaPublica['fecha_finalizacion'] ?>" />
                                    </div>
                                </div>

                                <!-- Hora Inicio -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label for="hora_inicio" class="form-label">Hora Inicio</label>
                                    <div class="input-group input-group-merge">
                                        <span id="hora_inicio-icon" class="input-group-text"><i
                                                class="fas fa-clock"></i></span>
                                        <input type="time" class="form-control" name="hora_inicio"
                                            value="<?php echo $areaPublica['hora_inicio'] ?>" required />
                                    </div>
                                </div>

                                <!-- Hora Finalización -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label for="hora_finalizacion" class="form-label">Hora Finalización</label>
                                    <div class="input-group input-group-merge">
                                        <span id="hora_finalizacion-icon" class="input-group-text"><i
                                                class="fas fa-clock"></i></span>
                                        <input type="time" class="form-control" name="hora_fin"
                                            value="<?php echo $areaPublica['hora_finalizacion'] ?>" />
                                    </div>
                                </div>

                                <!-- Peso -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label for="peso" class="form-label">Peso en KG</label>
                                    <div class="input-group input-group-merge">
                                        <span id="peso-icon" class="input-group-text"><i
                                                class="fas fa-weight-hanging"></i></span>
                                        <input type="number" step="0.001" class="form-control" name="peso"
                                            value="<?php echo $areaPublica['peso'] ?>" required />
                                    </div>
                                </div>

                                <!-- Observaciones -->
                                <div class="mb-3 col-12">
                                    <label for="observaciones" class="form-label">Observaciones</label>
                                    <div class="input-group input-group-merge">
                                        <span id="observaciones-icon" class="input-group-text"><i
                                                class="fas fa-comment-alt"></i></span>
                                        <textarea class="form-control" name="observaciones" rows="3"
                                            placeholder="Escriba observaciones..."><?php echo $areaPublica['observaciones'] ?></textarea>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <a href="areas_publicas_mariquita.php" class="btn btn-danger">Cancelar</a>
                                    <input type="submit" class="btn btn-primary" value="Actualizar">
                                    <input type="hidden" class="btn btn-info" value="FormUpdatePublic"
                                        name="MM_FormUpdatePublic"></input>
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
        showErrorOrSuccessAndRedirect("error", "Error de ruta", "Los datos del área pública no fueron encontrados", "lista_areas_publicas.php");
    }
} else {
    showErrorOrSuccessAndRedirect("error", "Error de ruta", "Los datos del área pública no fueron encontrados", "lista_areas_publicas.php");
}
    ?>