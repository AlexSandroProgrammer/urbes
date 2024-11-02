<?php

$titlePage = "Lista Disposicion Mariquita";
require_once("../components/sidebar.php");
// CONSULTA PARA LLAMAR TODAS LAS DISPOSICION DE MARIQUITA
$query = $connection->prepare("SELECT rr.*, e.estado, l.labor, u.documento, u.nombres, u.apellidos, v.vehiculo, v.placa, c.ciudad FROM recoleccion_relleno AS rr INNER JOIN labores AS l ON rr.id_labor = l.id_labor INNER JOIN vehiculos AS v ON rr.id_vehiculo = v.placa INNER JOIN usuarios AS u ON rr.documento = u.documento INNER JOIN estados AS e ON rr.id_estado = e.id_estado INNER JOIN ciudades AS c ON rr.ciudad = c.id_ciudad WHERE rr.ciudad = 1");
$query->execute();
$disposiciones = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Bootstrap modals -->
        <div class="card mb-4">
            <h2 class="card-header font-bold">Lista Disposicion Veh. Compactador Mariquita</h2>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 mt-3">

                        <table id="example"
                            class="table table-striped table-bordered top-table text-center table-responsive"
                            cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th class="custom-table-th">Acciones</th>
                                    <th class="custom-table-th">ID</th>
                                    <th class="custom-table-th">Estado</th>
                                    <th class="custom-table-th">Labor</th>
                                    <th class="custom-table-th">Conductor Asignado</th>
                                    <th class="custom-table-th">Documento</th>
                                    <th class="custom-table-th">Vehiculo</th>
                                    <th class="custom-table-th">Ciudad</th>
                                    <th class="custom-table-th">Fecha Inicio</th>
                                    <th class="custom-table-th">Hora Inicio</th>
                                    <th class="custom-table-th">Fecha Fin</th>
                                    <th class="custom-table-th">Hora Fin</th>
                                    <th class="custom-table-th">Km Inicial</th>
                                    <th class="custom-table-th">Foto Km Inicial</th>
                                    <th class="custom-table-th">Km Final</th>
                                    <th class="custom-table-th">Foto Km Final</th>
                                    <th class="custom-table-th">Toneladas</th>
                                    <th class="custom-table-th">Foto Toneladas</th>
                                    <th class="custom-table-th">Galones</th>
                                    <th class="custom-table-th">Foto Galones</th>
                                    <th class="custom-table-th">Horometro Inicial</th>
                                    <th class="custom-table-th">Horometro Final</th>
                                    <th class="custom-table-th">Observaciones</th>
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
                                    <td>
                                        <?php if ($disposicion['id_estado'] == 5) {
                                                echo '<span class="badge badge-success">Finalizado</span>';
                                            } else {
                                                echo '<span class="badge badge-danger">Pendiente</span>';
                                            }
                                            ?>
                                    </td>
                                    <td><?php echo $disposicion['labor'] ?></td>
                                    <td style="width: 250px;"><?php echo $disposicion['nombres'] ?> -
                                        <?php echo $disposicion['apellidos'] ?>
                                    <td><?php echo $disposicion['documento'] ?></td>
                                    <td><?php echo $disposicion['placa'] ?> - <?php echo $disposicion['vehiculo'] ?>
                                    <td><?php echo $disposicion['ciudad'] ?></td>
                                    </td>
                                    <td><?php echo $disposicion['fecha_inicio'] ?></td>
                                    <td><?php echo $disposicion['hora_inicio'] ?></td>
                                    <td class="custom-table-th">
                                        <?php echo !empty($disposicion['fecha_fin']) ? $disposicion['fecha_fin'] : 'No hay registros'; ?>
                                    </td>
                                    <td class="custom-table-th">
                                        <?php echo !empty($disposicion['hora_finalizacion']) ? $disposicion['hora_finalizacion'] : 'No hay registros'; ?>
                                    </td>
                                    <td class="custom-table-th">
                                        <?php echo !empty($disposicion['km_inicio']) ? $disposicion['km_inicio'] : 'No hay registros'; ?>
                                    </td>
                                    <?php if (isset($disposicion['foto_kilometraje_inicial']) && !empty($disposicion['foto_kilometraje_inicial'])) { ?>
                                    <td class="avatar">
                                        <img src="../../employee/assets/images/<?php echo $disposicion['foto_kilometraje_inicial'] ?>"
                                            alt class="w-px-100 h-px-100 rounded-circle" />
                                        <button class="btn btn-primary mt-2 view-photo-btn"
                                            data-photo="../../employee/assets/images/<?php echo $disposicion['foto_kilometraje_inicial'] ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                    <?php } else { ?>
                                    <td class="avatar">
                                        <img src="../../employee/assets/images/perfil_sin_foto.jpg" alt
                                            class="w-px-100 mb-3 h-px-100 rounded-circle" />
                                        <p>Sin foto</p>
                                    </td>
                                    <?php } ?>

                                    <td class="custom-table-th">
                                        <?php echo !empty($disposicion['km_fin']) ? $disposicion['km_fin'] : 'No hay registros'; ?>
                                    </td>
                                    <?php if (isset($disposicion['foto_kilometraje_final']) && !empty($disposicion['foto_kilometraje_final'])) { ?>
                                    <td class="avatar">
                                        <img src="../../employee/assets/images/<?php echo $disposicion['foto_kilometraje_final'] ?>"
                                            alt class="w-px-100 h-px-100 rounded-circle" />
                                        <button class="btn btn-primary mt-2 view-photo-btn"
                                            data-photo="../../employee/assets/images/<?php echo $disposicion['foto_kilometraje_final'] ?>">
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
                                    <td class="custom-table-th">
                                        <?php echo !empty($disposicion['toneladas']) ? $disposicion['toneladas'] : 'No hay registros'; ?>
                                    </td>
                                    <?php if (isset($disposicion['foto_tonelada']) && !empty($disposicion['foto_tonelada'])) { ?>
                                    <td class="avatar">
                                        <img src="../../employee/assets/images/<?php echo $disposicion['foto_tonelada'] ?>"
                                            alt class="w-px-100 h-px-100 rounded-circle" />
                                        <button class="btn btn-primary mt-2 view-photo-btn"
                                            data-photo="../../employee/assets/images/<?php echo $disposicion['foto_tonelada'] ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                    <?php } else { ?>
                                    <td class="avatar">
                                        <img src="../../employee/assets/images/perfil_sin_foto.jpg" alt
                                            class="w-px-100 mb-3 h-px-100 rounded-circle" />
                                        <p>Sin foto</p>
                                    </td>
                                    <?php } ?>
                                    <td class="custom-table-th">
                                        <?php echo !empty($disposicion['galones']) ? $disposicion['galones'] : 'No hay registros'; ?>
                                    </td>
                                    <?php if (isset($disposicion['foto_galones']) && !empty($disposicion['foto_galones'])) { ?>
                                    <td class="avatar">
                                        <img src="../../employee/assets/images/<?php echo $disposicion['foto_galones'] ?>"
                                            alt class="w-px-100 h-px-100 rounded-circle" />
                                        <button class="btn btn-primary mt-2 view-photo-btn"
                                            data-photo="../../employee/assets/images/<?php echo $disposicion['foto_galones'] ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                    <?php } else { ?>
                                    <td class="avatar">
                                        <img src="../../employee/assets/images/perfil_sin_foto.jpg" alt
                                            class="w-px-100 mb-3 h-px-100 rounded-circle" />
                                        <p>Sin foto</p>
                                    </td>
                                    <?php } ?>
                                    <td><?php echo $disposicion['horometro_inicio'] ?></td>
                                    <td class="custom-table-th">
                                        <?php echo !empty($disposicion['horometro_fin']) ? $disposicion['horometro_fin'] : 'No hay registros'; ?>
                                    </td>
                                    <td class="custom-table-th">
                                        <?php echo !empty($disposicion['observaciones']) ? $disposicion['observaciones'] : 'No hay registros'; ?>
                                    </td>
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