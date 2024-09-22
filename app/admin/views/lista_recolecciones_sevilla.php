<?php

$titlePage = "Lista de Recolecciones Sevilla";
require_once("../components/sidebar.php");
// CONSULTA PARA LLAMAR TODAS LAS RECOLECCIONES

$query = $connection->query("SELECT 
    vehiculo_compactador.id_registro_veh_compactador, 
    vehiculo_compactador.*, 
    usuarios.nombres, 
    usuarios.apellidos, 
    usuarios.documento, 
    labores.labor,
    estados.estado,
    vehiculos.vehiculo,
    vehiculos.placa,
    ciudades.ciudad,
    GROUP_CONCAT(usuarios.documento, '..', usuarios.nombres, '..', usuarios.apellidos SEPARATOR '__') AS usuarios 
FROM 
    vehiculo_compactador 
INNER JOIN 
    detalle_tripulacion ON detalle_tripulacion.id_registro = vehiculo_compactador.id_registro_veh_compactador 
INNER JOIN 
    usuarios ON usuarios.documento = detalle_tripulacion.documento 
INNER JOIN 
    labores ON vehiculo_compactador.id_labor = labores.id_labor 
INNER JOIN 
    vehiculos ON vehiculo_compactador.id_vehiculo = vehiculos.placa 
INNER JOIN 
    ciudades ON vehiculo_compactador.ciudad = ciudades.id_ciudad 
INNER JOIN 
    estados ON vehiculo_compactador.id_estado = estados.id_estado
    WHERE vehiculo_compactador.ciudad = 2
GROUP BY 
    vehiculo_compactador.id_registro_veh_compactador, 
    usuarios.nombres, 
    usuarios.apellidos, 
    usuarios.documento 
ORDER BY 
    vehiculo_compactador.id_registro_veh_compactador");
$recolecciones = $query->fetchAll(PDO::FETCH_OBJ);

?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Bootstrap modals -->
        <div class="card mb-4">
            <h2 class="card-header font-bold">Lista Recoleccion Veh. Compactador Sevilla</h2>
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
                                    <th class="custom-table-th">Tripulacion</th>
                                    <th class="custom-table-th">Fecha Inicio</th>
                                    <th class="custom-table-th">Fecha Fin</th>
                                    <th class="custom-table-th">Km Inicial</th>
                                    <th class="custom-table-th">Foto Km Inicial</th>
                                    <th class="custom-table-th">Km Final</th>
                                    <th class="custom-table-th">Foto Km Final</th>
                                    <th class="custom-table-th">Horometro Inicial</th>
                                    <th class="custom-table-th">Horometro Final</th>
                                    <th class="custom-table-th">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($recolecciones as $recoleccion) {
                                ?>
                                <tr>
                                    <td>
                                        <form method="GET" class="mt-2" action="editar_lista_recoleccion.php">
                                            <input type="hidden" name="id_registro"
                                                value="<?php echo $recoleccion->id_registro_veh_compactador ?>">
                                            <button class="btn btn-success"
                                                onclick="return confirm('¿Desea actualizar el registro seleccionado?');"
                                                type="submit">
                                                <i class="bx bx-refresh" title="Actualizar"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td><?php echo $recoleccion->id_registro_veh_compactador ?></td>
                                    <td>

                                        <?php if ($recoleccion->id_estado == 5) {
                                                echo '<span class="badge badge-success">Finalizado</span>';
                                            } else {
                                                echo '<span class="badge badge-danger">Pendiente</span>';
                                            }
                                            ?>
                                    </td>
                                    <td><?php echo $recoleccion->labor ?></td>
                                    <td style="width: 250px;"><?php echo $recoleccion->nombres ?> -
                                        <?php echo $recoleccion->apellidos ?>
                                    <td><?php echo $recoleccion->documento ?></td>
                                    <td><?php echo $recoleccion->placa ?> - <?php echo $recoleccion->vehiculo ?>
                                    <td><?php echo $recoleccion->ciudad ?></td>
                                    </td>
                                    <td>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="custom-table-th">Documento</th>
                                                    <th class="custom-table-th">Nombres</th>
                                                    <th class="custom-table-th">Apellidos</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach (explode("__", $recoleccion->usuarios) as $usuariosConcatenados) {
                                                        $usuario = explode("..", $usuariosConcatenados)
                                                    ?>
                                                <tr>
                                                    <td><?php echo $usuario[0] ?></td>
                                                    <td><?php echo $usuario[1] ?></td>
                                                    <td><?php echo $usuario[2] ?></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td><?php echo $recoleccion->fecha_inicio ?></td>
                                    <td><?php echo $recoleccion->fecha_fin ?></td>
                                    <td><?php echo $recoleccion->km_inicio ?></td>
                                    <?php if (isset($recoleccion->foto_kilometaje_inicial) && !empty($recoleccion->foto_kilometaje_inicial)) { ?>
                                    <td class="avatar">
                                        <img src="../assets/images/<?php echo $recoleccion->foto_kilometaje_inicial ?>"
                                            alt class="w-px-100 h-px-100 rounded-circle" />
                                        <button class="btn btn-primary mt-2 view-photo-btn"
                                            data-photo="../assets/images/<?php echo $recoleccion->foto_kilometaje_inicial ?>">
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
                                    <td><?php echo $recoleccion->km_fin ?></td>
                                    <?php if (isset($recoleccion->foto_kilometaje_final) && !empty($recoleccion->foto_kilometaje_final)) { ?>
                                    <td class="avatar">
                                        <img src="../assets/images/<?php echo $recoleccion->foto_kilometaje_final ?>"
                                            alt class="w-px-100 h-px-100 rounded-circle" />
                                        <button class="btn btn-primary mt-2 view-photo-btn"
                                            data-photo="../assets/images/<?php echo $recoleccion->foto_kilometaje_final ?>">
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
                                    <td><?php echo $recoleccion->horometro_inicio ?></td>
                                    <td><?php echo $recoleccion->horometro_fin ?></td>
                                    <td><?php echo $recoleccion->observaciones ?></td>
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