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
                                    <input type="text" class="form-control" name="documento"
                                        value="<?= $disposicion['documento'] ?>" readonly />
                                </div>

                                <!-- Vehículo -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="vehiculo">Vehículo</label>
                                    <select class="form-control" name="vehiculo">
                                        <?php
                                        // Consulta para obtener todos los vehículos disponibles
                                        $queryVehiculos = $connection->prepare("SELECT placa, vehiculo FROM vehiculos");
                                        $queryVehiculos->execute();
                                        $vehiculos = $queryVehiculos->fetchAll(PDO::FETCH_ASSOC);

                                        // Mostrar las opciones en el select
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
                                        // Consulta para obtener todas las ciudades disponibles
                                        $queryCiudades = $connection->prepare("SELECT id_ciudad, ciudad FROM ciudades");
                                        $queryCiudades->execute();
                                        $ciudades = $queryCiudades->fetchAll(PDO::FETCH_ASSOC);

                                        // Mostrar las opciones en el select
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
                                    <input type="date" class="form-control" name="fecha_inicio"
                                        value="<?= $disposicion['fecha_inicio'] ?>" required />
                                </div>

                                <!-- Hora Inicio -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="hora_inicio">Hora Inicio</label>
                                    <input type="time" class="form-control" name="hora_inicio"
                                        value="<?= $disposicion['hora_inicio'] ?>" required />
                                </div>

                                <!-- Fecha Fin -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="fecha_fin">Fecha Fin</label>
                                    <input type="date" class="form-control" name="fecha_fin"
                                        value="<?= $disposicion['fecha_fin'] ?>" required />
                                </div>

                                <!-- Hora Fin -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="hora_fin">Hora Fin</label>
                                    <input type="time" class="form-control" name="hora_fin"
                                        value="<?= $disposicion['hora_finalizacion'] ?>" required />
                                </div>

                                <!-- Kilometraje Inicial -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="km_inicio">Kilometraje Inicial</label>
                                    <input type="number" class="form-control" name="km_inicio"
                                        value="<?= $disposicion['km_inicio'] ?>" required />
                                </div>

                                <!-- Foto Kilometraje Inicial -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="foto_km_inicio">Foto Kilometraje Inicial</label>
                                    <input type="file" class="form-control" name="foto_km_inicio" accept="image/*" />
                                    <?php if (!empty($disposicion['foto_kilometraje_inicial'])): ?>
                                    <img src="../assets/images/<?= $disposicion['foto_kilometraje_inicial'] ?>"
                                        alt="Kilometraje Inicial" class="img-fluid mt-2" style="max-width: 150px;">
                                    <?php endif; ?>
                                </div>

                                <!-- Kilometraje Final -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="km_fin">Kilometraje Final</label>
                                    <input type="number" class="form-control" name="km_fin"
                                        value="<?= $disposicion['km_fin'] ?>" required />
                                </div>

                                <!-- Foto Kilometraje Final -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="foto_km_fin">Foto Kilometraje Final</label>
                                    <input type="file" class="form-control" name="foto_km_fin" accept="image/*" />
                                    <?php if (!empty($disposicion['foto_kilometraje_final'])): ?>
                                    <img src="../assets/images/<?= $disposicion['foto_kilometraje_final'] ?>"
                                        alt="Kilometraje Final" class="img-fluid mt-2" style="max-width: 150px;">
                                    <?php endif; ?>
                                </div>

                                <!-- Toneladas -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="toneladas">Toneladas</label>
                                    <input type="number" step="0.01" class="form-control" name="toneladas"
                                        value="<?= $disposicion['toneladas'] ?>" required />
                                </div>

                                <!-- Botón de Actualización -->
                                <div class="mt-4">
                                    <a href="index.php" class="btn btn-danger">Cancelar</a>
                                    <button type="submit" class="btn btn-primary">Actualizar</button>
                                    <input type="hidden" class="btn btn-info" value="FormUpDispocision"
                                        name="MM_FormUpDispocision"></input>
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