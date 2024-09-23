<?php
$titlePage = "Editar Carro de Barrido";
require_once("../components/sidebar.php");

if (isset($_GET['id_registro_barrido'])) {
    $id_registro_barrido = $_GET['id_registro_barrido'];

    // Consulta para obtener los datos del Carro de Barrido por ID
    $getCarrobarridoById = $connection->prepare("SELECT cb.*, e.estado, ac.actividad, u.documento, u.nombres, u.apellidos, c.ciudad, cb.ciudad AS id_ciudad_seleccionda FROM carro_barrido AS cb INNER JOIN actividades AS ac ON cb.id_actividad = ac.id_actividad INNER JOIN usuarios AS u ON cb.documento = u.documento INNER JOIN estados AS e ON cb.id_estado = e.id_estado INNER JOIN ciudades AS c ON cb.ciudad = c.id_ciudad WHERE cb.id_registro_barrido = :id_registro_barrido");
    $getCarrobarridoById->bindParam(":id_registro_barrido", $id_registro_barrido);
    $getCarrobarridoById->execute();
    $carrobarrido = $getCarrobarridoById->fetch(PDO::FETCH_ASSOC);
    if ($carrobarrido) {
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
                                <h3 class="fw-bold py-2">Editar Carro de Barrido - ID:
                                    <?php echo $carrobarrido['id_registro_barrido'] ?>
                                </h3>
                                <h6 class="mb-0">Por favor edita los datos necesarios del Carro de Barrido.</h6>
                            </div>
                            <div class="card-body">
                                <form action="" name="FormCarroBarrido" method="POST" enctype="multipart/form-data"
                                    autocomplete="off">
                                    <div class="row">
                                        <input type="hidden" name="id_registro_barrido"
                                            value="<?php echo $id_registro_barrido ?>">
                                        <!-- Estado -->
                                        <h6 class="mb-3 fw-bold"><i class="bx bx-map"></i> Datos del Carro de Barrido</h6>
                                        <div class="mb-3 col-12 col-lg-6">
                                            <label for="estado" class="form-label">Estado</label>
                                            <div class="input-group input-group-merge">
                                                <span id="estado-icon" class="input-group-text"><i
                                                        class="fas fa-check-circle"></i></span>
                                                <select class="form-select" name="id_estado" required>
                                                    <option value="<?php echo $carrobarrido['id_estado'] ?>">
                                                        <?php echo $carrobarrido['estado'] ?></option>
                                                    <?php
                                                    $listEstados = $connection->prepare("SELECT * FROM estados WHERE id_estado = 4 OR id_estado = 5");
                                                    $listEstados->execute();
                                                    $estados = $listEstados->fetchAll(PDO::FETCH_ASSOC);
                                                    foreach ($estados as $estado) {
                                                        echo "<option value='{$estado['id_estado']}'>{$estado['estado']}</option>";
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
                                                    <option value="<?php echo $carrobarrido['documento'] ?>">
                                                        <?php echo $carrobarrido['nombres'] . ' ' . $carrobarrido['apellidos'] ?>
                                                    </option>
                                                    <?php
                                                    $listUsuarios = $connection->prepare("SELECT * FROM usuarios WHERE id_tipo_usuario = 3");
                                                    $listUsuarios->execute();
                                                    $usuarios = $listUsuarios->fetchAll(PDO::FETCH_ASSOC);
                                                    foreach ($usuarios as $usuario) {
                                                        echo "<option value='{$usuario['documento']}'>{$usuario['nombres']} {$usuario['apellidos']}</option>";
                                                    }
                                                    ?>
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
                                                    <option value="<?php echo $carrobarrido['id_ciudad_seleccionda'] ?>">
                                                        <?php echo $carrobarrido['ciudad'] ?></option>
                                                    <?php
                                                    $listCiudades = $connection->prepare("SELECT * FROM ciudades");
                                                    $listCiudades->execute();
                                                    $ciudades = $listCiudades->fetchAll(PDO::FETCH_ASSOC);
                                                    foreach ($ciudades as $ciudad) {
                                                        echo "<option value='{$ciudad['id_ciudad']}'>{$ciudad['ciudad']}</option>";
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
                                                    value="<?php echo $carrobarrido['fecha_inicio'] ?>" required />
                                            </div>
                                        </div>

                                        <!-- Fecha Finalización -->
                                        <div class="mb-3 col-12 col-lg-6">
                                            <label for="fecha_fin" class="form-label">Fecha de Finalización</label>
                                            <div class="input-group input-group-merge">
                                                <span id="fecha_fin-icon" class="input-group-text"><i
                                                        class="fas fa-calendar"></i></span>
                                                <input type="date" class="form-control" name="fecha_fin"
                                                    value="<?php echo $carrobarrido['fecha_fin'] ?>" />
                                            </div>
                                        </div>

                                        <!-- Hora Inicio -->
                                        <div class="mb-3 col-12 col-lg-6">
                                            <label for="hora_inicio" class="form-label">Hora Inicio</label>
                                            <div class="input-group input-group-merge">
                                                <span id="hora_inicio-icon" class="input-group-text"><i
                                                        class="fas fa-clock"></i></span>
                                                <input type="time" class="form-control" name="hora_inicio"
                                                    value="<?php echo $carrobarrido['hora_inicio'] ?>" required />
                                            </div>
                                        </div>

                                        <!-- Hora Finalización -->
                                        <div class="mb-3 col-12 col-lg-6">
                                            <label for="hora_finalizacion" class="form-label">Hora Finalización</label>
                                            <div class="input-group input-group-merge">
                                                <span id="hora_finalizacion-icon" class="input-group-text"><i
                                                        class="fas fa-clock"></i></span>
                                                <input type="time" class="form-control" name="hora_fin"
                                                    value="<?php echo $carrobarrido['hora_fin'] ?>" />
                                            </div>
                                        </div>

                                        <!-- Peso -->
                                        <div class="mb-3 col-12 col-lg-6">
                                            <label for="peso" class="form-label">Peso en KG</label>
                                            <div class="input-group input-group-merge">
                                                <span id="peso-icon" class="input-group-text"><i
                                                        class="fas fa-weight-hanging"></i></span>
                                                <input type="number" class="form-control" name="peso"
                                                    value="<?php echo $carrobarrido['peso'] ?>" required />
                                            </div>
                                        </div>

                                        <!-- Observaciones -->
                                        <div class="mb-3 col-12">
                                            <label for="observaciones" class="form-label">Observaciones</label>
                                            <div class="input-group input-group-merge">
                                                <span id="observaciones-icon" class="input-group-text"><i
                                                        class="fas fa-comment-alt"></i></span>
                                                <textarea class="form-control" name="observaciones" rows="3"
                                                    placeholder="Escriba observaciones..."><?php echo $carrobarrido['observaciones'] ?></textarea>
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <a href="areas_publicas_mariquita.php" class="btn btn-danger">Cancelar</a>
                                            <input type="submit" class="btn btn-primary" value="Actualizar">
                                            <input type="hidden" class="btn btn-info" value="FormCarroBarrido"
                                                name="MM_FormCarroBarrido"></input>
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
        showErrorOrSuccessAndRedirect("error", "Error de ruta", "Los datos del Carro de Barrido no fueron encontrados", "lista_areas_publicas.php");
    }
} else {
    showErrorOrSuccessAndRedirect("error", "Error de ruta", "Los datos del Carro de Barrido no fueron encontrados", "lista_areas_publicas.php");
}
    ?>