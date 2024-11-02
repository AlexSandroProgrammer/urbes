<?php
$titlePage = "Lista de Recoleccines Sevilla";
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
    GROUP_CONCAT(DISTINCT u.documento, '..', u.nombres, '..', u.apellidos SEPARATOR '__') AS usuarios_tripulacion,
    GROUP_CONCAT(DISTINCT r.ruta SEPARATOR '__') AS rutas
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
LEFT JOIN
    detalle_rutas ON detalle_rutas.id_registro = vehiculo_compactador.id_registro_veh_compactador 
LEFT JOIN
    rutasr AS r ON detalle_rutas.id_ruta = r.id_ruta 
WHERE 
    vehiculo_compactador.ciudad = 2
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
        <div class="card mb-4">
            <h2 class="card-header font-bold">Lista Recolección Veh. Compactador Mariquita</h2>
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
                                    <th class="custom-table-th">Vehículo</th>
                                    <th class="custom-table-th">Ciudad</th>
                                    <th class="custom-table-th">Tripulación</th>
                                    <th class="custom-table-th">Rutas</th>
                                    <th class="custom-table-th">Fecha Inicio</th>
                                    <th class="custom-table-th">Hora Inicio</th>
                                    <th class="custom-table-th">Fecha Fin</th>
                                    <th class="custom-table-th">Hora Fin</th>
                                    <th class="custom-table-th">Km Inicial</th>
                                    <th class="custom-table-th">Foto Km Inicial</th>
                                    <th class="custom-table-th">Km Final</th>
                                    <th class="custom-table-th">Foto Km Final</th>
                                    <th class="custom-table-th">Horómetro Inicial</th>
                                    <th class="custom-table-th">Horómetro Final</th>
                                    <th class="custom-table-th">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recolecciones as $recoleccion): ?>
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
                                    <td><?php echo $recoleccion->id_registro_veh_compactador; ?></td>
                                    <td>
                                        <?php echo ($recoleccion->id_estado == 5) ? '<span class="badge badge-success">Finalizado</span>' : '<span class="badge badge-danger">Pendiente</span>'; ?>
                                    </td>
                                    <td><?php echo $recoleccion->labor; ?></td>
                                    <td style="width: 250px;">
                                        <?php echo $recoleccion->conductor_nombres . ' - ' . $recoleccion->conductor_apellidos; ?>
                                    </td>
                                    <td><?php echo $recoleccion->conductor_documento; ?></td>
                                    <td><?php echo $recoleccion->placa . ' - ' . $recoleccion->vehiculo; ?></td>
                                    <td><?php echo $recoleccion->ciudad; ?></td>
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
                                    <td>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="custom-table-th">Rutas</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    // Verificar si hay rutas
                                                    if (!empty($recoleccion->rutas)) {
                                                        foreach (explode("__", $recoleccion->rutas) as $rutas_Concatenados) {
                                                            $ruta_conca = explode("..", $rutas_Concatenados);
                                                    ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($ruta_conca[0]); ?></td>
                                                </tr>
                                                <?php
                                                        }
                                                    } else {
                                                    ?>
                                                <tr>
                                                    <td colspan="1" class="text-center">No se encontraron rutas.</td>
                                                </tr>
                                                <?php
                                                    }
                                                    ?>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td><?php echo $recoleccion->fecha_inicio; ?></td>
                                    <td><?php echo $recoleccion->hora_inicio; ?></td>
                                    <td class="custom-table-th">
                                        <?php echo !empty($recoleccion->fecha_fin) ? $recoleccion->fecha_fin : 'No hay registros'; ?>
                                    </td>
                                    <td class="custom-table-th">
                                        <?php echo !empty($recoleccion->hora_finalizacion) ? $recoleccion->hora_finalizacion : 'No hay registros'; ?>
                                    </td>
                                    <td class="custom-table-th">
                                        <?php echo !empty($recoleccion->km_inicio) ? $recoleccion->km_inicio : 'No hay registros'; ?>
                                    </td>
                                    <td class="avatar">
                                        <?php if (!empty($recoleccion->foto_kilometraje_inicial)): ?>
                                        <img src="./../../employee/assets/images/<?php echo $recoleccion->foto_kilometraje_inicial; ?>"
                                            alt class="w-px-100 h-px-100" />
                                        <button class="btn btn-primary mt-2 view-photo-btn"
                                            data-photo="./../../employee/assets/images/<?php echo $recoleccion->foto_kilometraje_inicial; ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <?php else: ?>
                                        <img src="./../../employee/assets/images/perfil_sin_foto.png" alt
                                            class="w-px-100 mb-3 h-px-100" />
                                        <p>Sin foto</p>
                                        <?php endif; ?>
                                    </td>
                                    <td class="custom-table-th">
                                        <?php echo !empty($recoleccion->km_final) ? $recoleccion->km_final : 'No hay registros'; ?>
                                    </td>
                                    <td class="avatar">
                                        <?php if (!empty($recoleccion->foto_kilometraje_final)): ?>
                                        <img src="./../../employee/assets/images/<?php echo $recoleccion->foto_kilometraje_final; ?>"
                                            alt class="w-px-100 h-px-100" />
                                        <button class="btn btn-primary mt-2 view-photo-btn"
                                            data-photo="./../../employee/assets/images/<?php echo $recoleccion->foto_kilometraje_final; ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <?php else: ?>
                                        <img src="./../../employee/assets/images/perfil_sin_foto.png" alt
                                            class="w-px-100 mb-3 h-px-100" />
                                        <p>Sin foto</p>
                                        <?php endif; ?>
                                    </td>
                                    <td class="custom-table-th">
                                        <?php echo !empty($recoleccion->horometro_inicio) ? $recoleccion->horometro_inicio : 'No hay registros'; ?>
                                    </td>
                                    <td class="custom-table-th">
                                        <?php echo !empty($recoleccion->horometro_fin) ? $recoleccion->horometro_fin : 'No hay registros'; ?>
                                    </td>
                                    <td class="custom-table-th">
                                        <?php echo !empty($recoleccion->observaciones) ? $recoleccion->observaciones : 'No hay registros'; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php require_once("../components/footer.php"); ?>