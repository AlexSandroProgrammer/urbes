<?php
$titlePage = "Editar Carro de Barrido";
require_once("../components/sidebar.php");
// Obtener el ID del registro desde el parámetro GET
$id_registro_barrido = $_GET['id_registro_barrido'];
// Consulta para obtener los detalles del carro barrido seleccionado
$queryBarrido = $connection->prepare("SELECT cb.*, c.ciudad AS ciudad_nombre, GROUP_CONCAT(dz.id_zona SEPARATOR '__') AS zonas_seleccionadas FROM carro_barrido AS cb LEFT JOIN detalle_zonas AS dz ON cb.id_registro_barrido = dz.id_registro LEFT JOIN ciudades AS c ON cb.ciudad = c.id_ciudad WHERE cb.id_registro_barrido = :id_registro_barrido GROUP BY cb.id_registro_barrido");
$queryBarrido->bindParam(':id_registro_barrido', $id_registro_barrido);
$queryBarrido->execute();
$carro_barrido = $queryBarrido->fetch(PDO::FETCH_ASSOC);
// Obtener las zonas disponibles para esta ciudad
$id_city = $carro_barrido['ciudad'];
$queryZona = $connection->prepare("SELECT * FROM zonas WHERE id_ciudad = :id_ciudad");
$queryZona->bindParam(":id_ciudad", $id_city);
$queryZona->execute();
$zonas = $queryZona->fetchAll(PDO::FETCH_ASSOC);
// Separar las zonas seleccionadas
$zonas_seleccionadas = explode('__', $carro_barrido['zonas_seleccionadas']);
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header justify-content-between align-items-center">
                        <h3 class="fw-bold">Actualizar Carro de Barrido - ID:
                            <?= $carro_barrido['id_registro_barrido'] ?></h3>
                        <h6 class="mb-0">Por favor edita los datos necesarios del Carro de Barrido.</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" name="FormCarroBarrido" enctype="multipart/form-data" autocomplete="off">
                            <input type="hidden" name="id_registro_barrido" value="<?= $id_registro_barrido ?>">
                            <div class="row">
                                <!-- Fecha Inicio -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="fecha_inicio">Fecha Inicio</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                        <input type="date" class="form-control" name="fecha_inicio"
                                            value="<?= $carro_barrido['fecha_inicio'] ?>" required />
                                    </div>
                                </div>
                                <!-- Hora Inicio -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="hora_inicio">Hora Inicio</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                        <input type="time" class="form-control" name="hora_inicio"
                                            value="<?= $carro_barrido['hora_inicio'] ?>" required />
                                    </div>
                                </div>
                                <!-- Fecha Fin -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="fecha_fin">Fecha Fin</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
                                        <input type="date" class="form-control" name="fecha_fin"
                                            value="<?= $carro_barrido['fecha_fin'] ?>" required />
                                    </div>
                                </div>
                                <!-- Hora Fin -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="hora_fin">Hora Fin</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                        <input type="time" class="form-control" name="hora_fin"
                                            value="<?= $carro_barrido['hora_fin'] ?>" required />
                                    </div>
                                </div>
                                <!-- Ciudad -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="ciudad">Ciudad</label>
                                    <select class="form-select" name="ciudad" required>
                                        <option value="<?= $carro_barrido['ciudad'] ?>">
                                            <?= $carro_barrido['ciudad_nombre'] ?>
                                        </option>
                                    </select>
                                </div>
                                <!-- Zonas -->
                                <div class="mb-3 col-12">
                                    <label class="form-label">Selecciona las Zonas:</label>
                                    <div class="row">
                                        <?php foreach ($zonas as $zona): ?>
                                        <div class="col-md-6 col-lg-4 mb-2">
                                            <div class="d-flex align-items-center">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="zonas[]"
                                                        value="<?= $zona['id_zona'] ?>"
                                                        <?= in_array($zona['id_zona'], $zonas_seleccionadas) ? 'checked' : '' ?>>
                                                    <label
                                                        class="form-check-label ms-2"><?= htmlspecialchars($zona['zona']) ?></label>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <!-- Peso -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="peso">Peso</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="fas fa-weight-hanging"></i></span>
                                        <input type="number" class="form-control" name="peso" step="0.001"
                                            value="<?= $carro_barrido['peso'] ?>" placeholder="Ingresa el peso en kg"
                                            required />
                                    </div>
                                </div>
                                <!-- Observaciones -->
                                <div class="mb-3 col-12">
                                    <label class="form-label" for="observaciones">Observaciones</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="fas fa-comment-alt"></i></span>
                                        <textarea class="form-control" name="observaciones" rows="3"
                                            placeholder="Escriba observaciones..."
                                            required><?= $carro_barrido['observaciones'] ?></textarea>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <a href="index.php" class="btn btn-danger">Cancelar</a>
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
    ?>