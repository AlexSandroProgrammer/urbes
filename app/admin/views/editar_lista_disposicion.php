<?php
$titlePage = "Editar Disposición Veh. Compactador";
require_once("../components/sidebar.php");

// Obtener el ID del registro desde el parámetro GET
$id_registro_disposicion = $_GET['id_registro'];

// Consulta para obtener los detalles de la disposición seleccionada
$queryDisposicion = $connection->prepare("
    SELECT 
        rr.*, 
        e.estado, 
        l.labor, 
        u.nombres, 
        u.apellidos, 
        v.vehiculo, 
        v.placa, 
        c.ciudad 
    FROM 
        recoleccion_relleno AS rr
    INNER JOIN 
        labores AS l ON rr.id_labor = l.id_labor
    INNER JOIN 
        vehiculos AS v ON rr.id_vehiculo = v.placa
    INNER JOIN 
        usuarios AS u ON rr.documento = u.documento
    INNER JOIN 
        estados AS e ON rr.id_estado = e.id_estado
    INNER JOIN 
        ciudades AS c ON rr.ciudad = c.id_ciudad
    WHERE 
        rr.id_recoleccion = :id_registro_disposicion
");
$queryDisposicion->bindParam(':id_registro_disposicion', $id_registro_disposicion);
$queryDisposicion->execute();
$disposicion = $queryDisposicion->fetch(PDO::FETCH_ASSOC);
?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="fw-bold">Actualizar Disposición Veh. Compactador - ID:
                            <?= $disposicion['id_recoleccion'] ?></h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" name="FormUpDispocision" enctype="multipart/form-data" autocomplete="off">
                            <input type="hidden" name="id_registro_disposicion"
                                value="<?= $disposicion['id_recoleccion'] ?>">
                            <input type="hidden" name="MM_FormUpDispocision" value="FormUpDispocision">

                            <div class="row">
                                <!-- Estado -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="estado">Estado</label>
                                    <select class="form-control" name="estado">
                                        <option value="5" <?= $disposicion['id_estado'] == 5 ? 'selected' : '' ?>>
                                            Finalizado</option>
                                        <option value="1" <?= $disposicion['id_estado'] != 5 ? 'selected' : '' ?>>
                                            Pendiente</option>
                                    </select>
                                </div>

                                <!-- Labor -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="labor">Labor</label>
                                    <input type="text" class="form-control" name="labor"
                                        value="<?= $disposicion['labor'] ?>" readonly />
                                </div>

                                <!-- Conductor Asignado -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="conductor">Conductor</label>
                                    <input type="text" class="form-control"
                                        value="<?= $disposicion['nombres'] ?> <?= $disposicion['apellidos'] ?>"
                                        readonly />
                                </div>

                                <!-- Documento -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="documento">Documento</label>
                                    <input type="number" class="form-control" name="documento"
                                        value="<?= $disposicion['documento'] ?>" readonly />
                                </div>

                                <!-- Vehículo -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="vehiculo">Vehículo</label>
                                    <select class="form-control" name="vehiculo">
                                        <?php
                                        $queryVehiculos = $connection->prepare("SELECT placa, vehiculo FROM vehiculos");
                                        $queryVehiculos->execute();
                                        $vehiculos = $queryVehiculos->fetchAll(PDO::FETCH_ASSOC);

                                        foreach ($vehiculos as $vehiculo) {
                                            $selected = $disposicion['placa'] == $vehiculo['placa'] ? 'selected' : '';
                                            echo "<option value='{$vehiculo['placa']}' $selected>{$vehiculo['placa']} - {$vehiculo['vehiculo']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <!-- Ciudad -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="ciudad">Ciudad</label>
                                    <select class="form-control" name="ciudad">
                                        <?php
                                        $queryCiudades = $connection->prepare("SELECT id_ciudad, ciudad FROM ciudades");
                                        $queryCiudades->execute();
                                        $ciudades = $queryCiudades->fetchAll(PDO::FETCH_ASSOC);

                                        foreach ($ciudades as $ciudad) {
                                            $selected = $disposicion['ciudad'] == $ciudad['ciudad'] ? 'selected' : '';
                                            echo "<option value='{$ciudad['id_ciudad']}' $selected>{$ciudad['ciudad']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <!-- Fecha Inicio -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="fecha_inicio">Fecha Inicio</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-calendar-alt"></i></span>
                                        <input type="date" class="form-control" name="fecha_inicio"
                                            value="<?= $disposicion['fecha_inicio'] ?>" required />
                                    </div>
                                </div>

                                <!-- Hora Inicio -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="hora_inicio">Hora Inicio</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-clock"></i></span>
                                        <input type="time" class="form-control" name="hora_inicio"
                                            value="<?= $disposicion['hora_inicio'] ?>" required />
                                    </div>
                                </div>

                                <!-- Fecha Fin -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="fecha_fin">Fecha Fin</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-calendar-alt"></i></span>
                                        <input type="date" class="form-control" name="fecha_fin"
                                            value="<?= $disposicion['fecha_fin'] ?>" required />
                                    </div>
                                </div>

                                <!-- Hora Fin -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="hora_fin">Hora Fin</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-clock"></i></span>
                                        <input type="time" class="form-control" name="hora_fin"
                                            value="<?= $disposicion['hora_finalizacion'] ?>" required />
                                    </div>
                                </div>

                                <!-- Kilometraje Inicial -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="km_inicio">Kilometraje Inicial</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-road"></i></span>
                                        <input type="number" step="0.01" class="form-control" name="km_inicio"
                                            value="<?= $disposicion['km_inicio'] ?>" required />
                                    </div>
                                </div>

                                <!-- Horómetro Inicial -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="horometro_inicio">Horómetro Inicial</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-tachometer-alt"></i></span>
                                        <input type="number" step="0.01" class="form-control" name="horometro_inicio"
                                            value="<?= $disposicion['horometro_inicio'] ?>" required />
                                    </div>
                                </div>

                                <!-- Foto Kilometraje Inicial -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="foto_km_inicio">Foto Kilometraje Inicial</label>
                                    <input type="file" class="form-control" name="foto_km_inicio" accept="image/*" />
                                    <?php if (!empty($disposicion['foto_kilometraje_inicial'])): ?>
                                    <img src="../../employee/assets/images/<?= $disposicion['foto_kilometraje_inicial'] ?>"
                                        alt="Kilometraje Inicial" class="img-fluid mt-2" style="max-width: 150px;">
                                    <?php endif; ?>
                                </div>

                                <!-- Kilometraje Final -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="km_fin">Kilometraje Final</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-road"></i></span>
                                        <input type="number" step="0.01" class="form-control" name="km_fin"
                                            value="<?= $disposicion['km_fin'] ?>" required />
                                    </div>
                                </div>

                                <!-- Horómetro Final -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="horometro_fin">Horómetro Final</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-tachometer-alt"></i></span>
                                        <input type="number" step=0.01 class="form-control" name="horometro_fin"
                                            value="<?= $disposicion['horometro_fin'] ?>" required />
                                    </div>
                                </div>

                                <!-- Foto Kilometraje Final -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="foto_km_fin">Foto Kilometraje Final</label>
                                    <input type="file" class="form-control" name="foto_km_fin" accept="image/*" />
                                    <?php if (!empty($disposicion['foto_kilometraje_final'])): ?>
                                    <img src="../../employee/assets/images/<?= $disposicion['foto_kilometraje_final'] ?>"
                                        alt="Kilometraje Final" class="img-fluid mt-2" style="max-width: 150px;">
                                    <?php endif; ?>
                                </div>

                                <!-- Toneladas -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="toneladas">Toneladas</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-weight"></i></span>
                                        <input type="number" step="0.01" class="form-control" name="toneladas"
                                            value="<?= $disposicion['toneladas'] ?>" required />
                                    </div>
                                </div>

                                <!-- Galones -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="galones">Galones</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-gas-pump"></i></span>
                                        <input type="number" step="0.01" class="form-control" name="galones"
                                            value="<?= $disposicion['galones'] ?>" required />
                                    </div>
                                </div>

                                <!-- Observaciones -->
                                <div class="mb-3 col-12">
                                    <label class="form-label" for="observaciones">Observaciones</label>
                                    <textarea class="form-control" name="observaciones"
                                        rows="3"><?= $disposicion['observaciones'] ?></textarea>
                                </div>

                                <!-- Botón de Actualización -->
                                <div class="mt-4">
                                    <a href="index.php" class="btn btn-danger">Cancelar</a>
                                    <button type="submit" class="btn btn-primary">Actualizar</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- / Content -->