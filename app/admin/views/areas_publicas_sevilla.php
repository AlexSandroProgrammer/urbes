<?php

$titlePage = "Lista Areas Publicas Sevilla";
require_once("../components/sidebar.php");
// CONSULTA PARA LLAMAR TODAS LAS DISPOSICION DE SEVILLA
$query = $connection->prepare("SELECT ap.*, e.estado, l.labor, u.documento, u.nombres, u.apellidos, c.ciudad FROM areas_publicas AS ap INNER JOIN labores AS l ON ap.id_labor = l.id_labor INNER JOIN usuarios AS u ON ap.documento = u.documento INNER JOIN estados AS e ON ap.id_estado = e.id_estado INNER JOIN ciudades AS c ON ap.id_ciudad = c.id_ciudad WHERE ap.id_ciudad = 2");
$query->execute();
$areas_publicas = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Bootstrap modals -->
        <div class="card mb-4">
            <h2 class="card-header font-bold">Lista Areas Publicas Sevilla</h2>
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
                                    <th class="custom-table-th">Nombre Empleado</th>
                                    <th class="custom-table-th">Documento</th>
                                    <th class="custom-table-th">Ciudad</th>
                                    <th class="custom-table-th">Fecha Inicio</th>
                                    <th class="custom-table-th">Hora Inicio</th>
                                    <th class="custom-table-th">Fecha Fin</th>
                                    <th class="custom-table-th">Hora Fin</th>
                                    <th class="custom-table-th">Peso</th>

                                    <th class="custom-table-th">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($areas_publicas as $area_publica) { ?>
                                <tr>
                                    <td class="custom-table-th">
                                        <form method="GET" class="mt-2" action="editar_lista_area_publica.php">
                                            <input type="hidden" name="id_registro"
                                                value="<?php echo $area_publica['id_registro'] ?>">
                                            <button class="btn btn-success"
                                                onclick="return confirm('¿Desea actualizar el registro seleccionado?');"
                                                type="submit">
                                                <i class="bx bx-refresh" title="Actualizar"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="custom-table-th"><?php echo $area_publica['id_registro'] ?></td>
                                    <td class="custom-table-th"><?php echo $area_publica['estado'] ?></td>
                                    <td class="custom-table-th"><?php echo $area_publica['labor'] ?></td>
                                    <td class="custom-table-th"><?php echo $area_publica['nombres'] ?> -
                                        <?php echo $area_publica['apellidos'] ?></td>
                                    <td class="custom-table-th"><?php echo $area_publica['documento'] ?></td>
                                    <td class="custom-table-th"><?php echo $area_publica['ciudad'] ?></td>
                                    <td class="custom-table-th"><?php echo $area_publica['fecha_inicio'] ?></td>
                                    <td class="custom-table-th">
                                        <?php echo !empty($area_publica['fecha_finalizacion']) ? $area_publica['fecha_finalizacion'] : 'No hay registros'; ?>
                                    </td>
                                    <td class="custom-table-th"><?php echo $area_publica['hora_inicio'] ?></td>
                                    <td class="custom-table-th">
                                        <?php echo !empty($area_publica['hora_finalizacion']) ? $area_publica['hora_finalizacion'] : 'No hay registros'; ?>
                                    </td>
                                    <td class="custom-table-th">
                                        <?php echo !empty($area_publica['peso']) ? $area_publica['peso'] : 'No hay registros'; ?>
                                    </td>

                                    <td class="custom-table-th">
                                        <?php echo !empty($area_publica['observaciones']) ? $area_publica['observaciones'] : 'No hay registros'; ?>
                                    </td>
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