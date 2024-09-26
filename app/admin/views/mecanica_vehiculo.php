<?php

$titlePage = "Lista Mecanica Vehiculo Compactador";
require_once("../components/sidebar.php");
// CONSULTA PARA LLAMAR TODos los registros de Mecanica Vehiculo COMPACTADOR
$query = $connection->prepare("SELECT mvc.*, e.estado, ac.actividad, u.documento, u.nombres, u.apellidos, v.placa, v.vehiculo FROM mecanica AS mvc INNER JOIN actividades AS ac ON mvc.id_actividad = ac.id_actividad INNER JOIN usuarios AS u ON mvc.documento = u.documento INNER JOIN estados AS e ON mvc.id_estado = e.id_estado INNER JOIN vehiculos AS v ON mvc.id_vehiculo = v.placa");
$query->execute();
$mecanica_vehiculos = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Bootstrap modals -->
        <div class="card mb-4">
            <h2 class="card-header font-bold">Lista Mecanica Veh. Compactador</h2>
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
                                    <th class="custom-table-th">Actividad</th>
                                    <th class="custom-table-th">Empleado</th>
                                    <th class="custom-table-th">Documento</th>
                                    <th class="custom-table-th">Vehiculo</th>
                                    <th class="custom-table-th">Actividad</th>
                                    <th class="custom-table-th">Labor Mantenimiento</th>
                                    <th class="custom-table-th">Fecha Inicio</th>
                                    <th class="custom-table-th">Fecha Fin</th>
                                    <th class="custom-table-th">Hora Inicio</th>
                                    <th class="custom-table-th">Hora Fin</th>
                                    <th class="custom-table-th">Fecha Registro</th>
                                    <th class="custom-table-th">Fecha Actualizacion</th>
                                    <th class="custom-table-th">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($mecanica_vehiculos as $mecanica_vehiculo) { ?>
                                <tr>
                                    <td class="custom-table-th">
                                        <form method="GET" class="mt-2" action="editar_lista_mecanica.php">
                                            <input type="hidden" name="id_registro"
                                                value="<?php echo $mecanica_vehiculo['id_registro'] ?>">
                                            <button class="btn btn-success"
                                                onclick="return confirm('¿Desea actualizar el registro seleccionado?');"
                                                type="submit">
                                                <i class="bx bx-refresh" title="Actualizar"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="custom-table-th"><?php echo $mecanica_vehiculo['id_registro'] ?>
                                    </td>
                                    <td class="custom-table-th"><?php echo $mecanica_vehiculo['estado'] ?></td>
                                    <td class="custom-table-th"><?php echo $mecanica_vehiculo['actividad'] ?></td>
                                    <td class="custom-table-th"><?php echo $mecanica_vehiculo['nombres'] ?> -
                                        <?php echo $mecanica_vehiculo['apellidos'] ?></td>
                                    <td class="custom-table-th"><?php echo $mecanica_vehiculo['documento'] ?></td>
                                    <td class="custom-table-th"><?php echo $mecanica_vehiculo['vehiculo'] ?> -
                                        <?php echo $mecanica_vehiculo['placa'] ?></td>
                                    <td class="custom-table-th"><?php echo $mecanica_vehiculo['actividad'] ?>
                                    </td>
                                    <td class="custom-table-th"><?php echo $mecanica_vehiculo['labor_mantenimiento'] ?>
                                    </td>
                                    <td class="custom-table-th"><?php echo $mecanica_vehiculo['fecha_inicio'] ?></td>
                                    <td class="custom-table-th">
                                        <?php echo !empty($mecanica_vehiculo['fecha_fin']) ? $mecanica_vehiculo['fecha_fin'] : 'No hay registros'; ?>
                                    </td>
                                    <td class="custom-table-th"><?php echo $mecanica_vehiculo['hora_inicio'] ?></td>
                                    <td class="custom-table-th">
                                        <?php echo !empty($mecanica_vehiculo['hora_finalizacion']) ? $mecanica_vehiculo['hora_finalizacion'] : 'No hay registros'; ?>
                                    </td>
                                    <td class="custom-table-th"><?php echo $mecanica_vehiculo['fecha_registro'] ?>
                                    </td>
                                    <td class="custom-table-th">
                                        <?php echo !empty($mecanica_vehiculo['fecha_actualizacion']) ? $mecanica_vehiculo['fecha_actualizacion'] : 'No hay registros'; ?>
                                    </td>
                                    <td class="custom-table-th"><?php echo $mecanica_vehiculo['observaciones'] ?></td>
                                </tr>
                                <?php } ?>
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