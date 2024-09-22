<?php

$titlePage = "Lista Disposicion Sevilla";
require_once("../components/sidebar.php");
// CONSULTA PARA LLAMAR TODAS LAS DISPOSICION DE SEVILLA
$query = $connection->prepare("SELECT rr.*, e.estado, l.labor, u.documento, u.nombres, u.apellidos, v.vehiculo, v.placa, c.ciudad FROM recoleccion_relleno AS rr INNER JOIN labores AS l ON rr.id_labor = l.id_labor INNER JOIN vehiculos AS v ON rr.id_vehiculo = v.placa INNER JOIN usuarios AS u ON rr.documento = u.documento INNER JOIN estados AS e ON rr.id_estado = e.id_estado INNER JOIN ciudades AS c ON rr.ciudad = c.id_ciudad WHERE rr.ciudad = 2");
$query->execute();
$disposiciones = $query->fetchAll(PDO::FETCH_ASSOC);

?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Bootstrap modals -->
        <div class="card mb-4">
            <h2 class="card-header font-bold">Lista Disposicion Veh. Compactador Sevilla</h2>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 mt-3">

                        <table id="example"
                            class="table table-striped table-bordered top-table text-center table-responsive"
                            cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Acciones</th>
                                    <th>ID</th>
                                    <th>Estado</th>
                                    <th>Labor</th>
                                    <th>Conductor Asignado</th>
                                    <th>Documento</th>
                                    <th>Vehiculo</th>
                                    <th>Ciudad</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Km Inicial</th>
                                    <th>Foto Km Inicial</th>
                                    <th>Km Final</th>
                                    <th>Foto Km Final</th>
                                    <th>Toneladas</th>
                                    <th>Galones</th>
                                    <th>Horometro Inicial</th>
                                    <th>Horometro Final</th>
                                    <th>Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($disposiciones as $disposicion) {
                                ?>
                                    <tr>
                                        <td>
                                            <form method="GET" class="mt-2" action="editar_lista_disposicion.php">
                                                <input type="hidden" name="id_registro"
                                                    value="<?php echo $disposicion['id_recoleccion'] ?>">
                                                <button class="btn btn-success"
                                                    onclick="return confirm('¿Desea actualizar el registro seleccionado?');"
                                                    type="submit">
                                                    <i class="bx bx-refresh" title="Actualizar"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td><?php echo $disposicion['id_recoleccion'] ?></td>
                                        <td><?php echo $disposicion['estado'] ?></td>
                                        <td><?php echo $disposicion['labor'] ?></td>
                                        <td style="width: 250px;"><?php echo $disposicion['nombres'] ?> -
                                            <?php echo $disposicion['apellidos'] ?>
                                        <td><?php echo $disposicion['documento'] ?></td>
                                        <td><?php echo $disposicion['placa'] ?> - <?php echo $disposicion['vehiculo'] ?>
                                        <td><?php echo $disposicion['ciudad'] ?></td>
                                        </td>
                                        <td><?php echo $disposicion['fecha_inicio'] ?></td>
                                        <td><?php echo $disposicion['fecha_fin'] ?></td>
                                        <td><?php echo $disposicion['km_inicio'] ?></td>
                                        <?php if (isset($disposicion['foto_kilometraje_inicial']) && !empty($disposicion['foto_kilometraje_inicial'])) { ?>
                                            <td class="avatar">
                                                <img src="../assets/images/<?php echo $disposicion['foto_kilometraje_inicial'] ?>"
                                                    alt class="w-px-100 h-px-100 rounded-circle" />
                                                <button class="btn btn-primary mt-2 view-photo-btn"
                                                    data-photo="../assets/images/<?php echo $disposicion['foto_kilometraje_inicial'] ?>">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        <?php } else { ?>
                                            <td class="avatar">
                                                <img src="../assets/images/perfil_sin_foto.jpg" alt
                                                    class="w-px-100 mb-3 h-px-100 rounded-circle" />
                                                <p>Sin foto</p>
                                            </td>
                                        <?php } ?>
                                        <td><?php echo $disposicion['km_fin'] ?></td>
                                        <?php if (isset($disposicion['foto_kilometraje_final']) && !empty($disposicion['foto_kilometraje_final'])) { ?>
                                            <td class="avatar">
                                                <img src="../assets/images/<?php echo $disposicion['foto_kilometraje_final'] ?>"
                                                    alt class="w-px-100 h-px-100 rounded-circle" />
                                                <button class="btn btn-primary mt-2 view-photo-btn"
                                                    data-photo="../assets/images/<?php echo $disposicion['foto_kilometraje_final'] ?>">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        <?php } else { ?>
                                            <td class="avatar">
                                                <img src="../assets/images/perfil_sin_foto.jpg" alt
                                                    class="w-px-100 mb-3 h-px-100 rounded-circle" />
                                                <p>Sin foto</p>
                                            </td>
                                        <?php } ?>
                                        <td><?php echo $disposicion['toneladas'] ?></td>
                                        <td><?php echo $disposicion['galones'] ?></td>
                                        <td><?php echo $disposicion['horometro_inicio'] ?></td>
                                        <td><?php echo $disposicion['horometro_fin'] ?></td>
                                        <td><?php echo $disposicion['observaciones'] ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    require_once("../components/footer.php")
    ?>