<?php

$titlePage = "Lista de Recolecciones Mariquita";
require_once("../components/sidebar.php");
// CONSULTA PARA LLAMAR TODAS LAS RECOLECCIONES

$query = $connection->query("SELECT 
    vehiculo_compactador.id_registro_veh_compactador, 
    vehiculo_compactador.*, 
    vehiculo_compactador.foto_kilometraje_final,
    datos_conductor.nombres AS conductor_nombres, 
    datos_conductor.apellidos AS conductor_apellidos, 
    datos_conductor.documento AS conductor_documento, 
    labores.labor, 
    estados.estado, 
    vehiculos.vehiculo, 
    vehiculos.placa, 
    ciudades.ciudad, 
    GROUP_CONCAT(u.documento, '..', u.nombres, '..', u.apellidos SEPARATOR '__') AS usuarios_tripulacion 
FROM 
    vehiculo_compactador 
INNER JOIN 
    detalle_tripulacion ON detalle_tripulacion.id_registro = vehiculo_compactador.id_registro_veh_compactador 
INNER JOIN 
    usuarios AS u ON u.documento = detalle_tripulacion.documento 
INNER JOIN 
    labores ON vehiculo_compactador.id_labor = labores.id_labor 
INNER JOIN 
    vehiculos ON vehiculo_compactador.id_vehiculo = vehiculos.placa 
INNER JOIN 
    usuarios AS datos_conductor ON vehiculo_compactador.documento = datos_conductor.documento  
INNER JOIN 
    ciudades ON vehiculo_compactador.ciudad = ciudades.id_ciudad 
INNER JOIN 
    estados ON vehiculo_compactador.id_estado = estados.id_estado 
WHERE 
    vehiculo_compactador.ciudad = 1 
GROUP BY 
    vehiculo_compactador.id_registro_veh_compactador 
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
            <h2 class="card-header font-bold">Lista Recoleccion Veh. Compactador Mariquita</h2>
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
                                        <td style="width: 250px;">
                                            <?php echo $recoleccion->conductor_nombres ?> -
                                            <?php echo $recoleccion->conductor_apellidos ?>
                                        </td>
                                        <td><?php echo $recoleccion->conductor_documento ?></td>

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
                                                    <?php
                                                    foreach (explode("__", $recoleccion->usuarios_tripulacion) as $usuarios_tripulacionConcatenados) {
                                                        $usuario_tripulacion = explode("..", $usuarios_tripulacionConcatenados);
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $usuario_tripulacion[0]; ?></td>
                                                            <td><?php echo $usuario_tripulacion[1]; ?></td>
                                                            <td><?php echo $usuario_tripulacion[2]; ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td><?php echo $recoleccion->fecha_inicio ?></td>
                                        <td class="custom-table-th">
                                            <?php echo !empty($recoleccion->fecha_fin) ? $recoleccion->fecha_fin : 'No hay registros'; ?>
                                        </td>
                                        <td class="custom-table-th">
                                            <?php echo !empty($recoleccion->km_inicio) ? $recoleccion->km_inicio : 'No hay registros'; ?>
                                        </td>
                                        <?php
                                        if (!empty($recoleccion->foto_kilometraje_inicial)) { ?>
                                            <td class="avatar">
                                                <img src="./../../employee/assets/images/<?php echo $recoleccion->foto_kilometraje_inicial ?>"
                                                    alt class="w-px-100 h-px-100" />
                                                <button class="btn btn-primary mt-2 view-photo-btn"
                                                    data-photo="./../../employee/assets/images/<?php echo $recoleccion->foto_kilometraje_inicial ?>">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        <?php } else { ?>
                                            <td class="avatar">
                                                <img src="./../../employee/assets/images/perfil_sin_foto.png" alt
                                                    class="w-px-100 mb-3 h-px-100" />
                                                <p>Sin foto</p>
                                            </td>
                                        <?php } ?>
                                        <td class="custom-table-th">
                                            <?php echo !empty($recoleccion->km_fin) ? $recoleccion->km_fin : 'No hay registros'; ?>
                                        </td>
                                        <?php
                                        if (!empty($recoleccion->foto_kilometraje_final)) { ?>
                                            <td class="avatar">
                                                <img src="./../../employee/assets/images/<?php echo $recoleccion->foto_kilometraje_final ?>"
                                                    alt class="w-px-100 h-px-100 rounded-circle" />
                                                <button class="btn btn-primary mt-2 view-photo-btn"
                                                    data-photo="./../../employee/assets/images/<?php echo $recoleccion->foto_kilometraje_final ?>">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        <?php } else { ?>
                                            <td class="avatar">
                                                <img src="./../../employee/assets/images/perfil_sin_foto.png" alt
                                                    class="w-px-100 mb-3 h-px-100" />
                                                <p>Sin foto</p>
                                            </td>
                                        <?php } ?>
                                        <td><?php echo $recoleccion->horometro_inicio ?></td>
                                        <td class="custom-table-th">
                                            <?php echo !empty($recoleccion->horometro_fin) ? $recoleccion->horometro_fin : 'No hay registros'; ?>
                                        </td>
                                        <td class="custom-table-th">
                                            <?php echo !empty($recoleccion->observaciones) ? $recoleccion->observaciones : 'No hay registros'; ?>
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