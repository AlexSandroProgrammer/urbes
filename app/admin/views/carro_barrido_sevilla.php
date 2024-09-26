<?php

$titlePage = "Lista Carro de Barrido Sevilla";
require_once("../components/sidebar.php");

// CONSULTA PARA LLAMAR TODAS LAS DISPOSICIONES DE SEVILLA
$query = $connection->prepare("
    SELECT 
        cb.*, 
        e.estado, 
        ac.actividad, 
        u.documento, 
        u.nombres, 
        u.apellidos, 
        c.ciudad AS ciudad_carro, 
        GROUP_CONCAT(z.zona, '..', cz.ciudad SEPARATOR '__') AS zonas_rutas
    FROM 
        carro_barrido AS cb
    INNER JOIN 
        detalle_zonas AS dz ON dz.id_registro = cb.id_registro_barrido
    INNER JOIN 
        zonas AS z ON dz.id_zona = z.id_zona
    INNER JOIN 
        ciudades AS cz ON z.id_ciudad = cz.id_ciudad -- Ciudad de la zona
    INNER JOIN 
        actividades AS ac ON cb.id_actividad = ac.id_actividad
    INNER JOIN 
        usuarios AS u ON cb.documento = u.documento
    INNER JOIN 
        estados AS e ON cb.id_estado = e.id_estado
    INNER JOIN 
        ciudades AS c ON cb.ciudad = c.id_ciudad -- Ciudad del carro de barrido
    WHERE 
        cb.ciudad = 2
    GROUP BY 
        cb.id_registro_barrido
");
$query->execute();
$carro_barridos = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Bootstrap modals -->
        <div class="card mb-4">
            <h2 class="card-header font-bold">Lista Carro Barrido Sevilla</h2>
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
                                    <th class="custom-table-th">Ciudad del Carro</th>
                                    <th class="custom-table-th">Fecha Inicio</th>
                                    <th class="custom-table-th">Fecha Fin</th>
                                    <th class="custom-table-th">Hora Inicio</th>
                                    <th class="custom-table-th">Hora Fin</th>
                                    <th class="custom-table-th">Zonas y Ciudades</th>
                                    <th class="custom-table-th">Peso</th>
                                    <th class="custom-table-th">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($carro_barridos as $carro_barrido) { ?>
                                <tr>
                                    <td class="custom-table-th">
                                        <form method="GET" class="mt-2" action="editar_lista_carro_barrido.php">
                                            <input type="hidden" name="id_registro_barrido"
                                                value="<?php echo $carro_barrido['id_registro_barrido'] ?>">
                                            <button class="btn btn-success"
                                                onclick="return confirm('¿Desea actualizar el registro seleccionado?');"
                                                type="submit">
                                                <i class="bx bx-refresh" title="Actualizar"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="custom-table-th"><?php echo $carro_barrido['id_registro_barrido'] ?></td>
                                    <td>
                                        <?php if ($carro_barrido['id_estado'] == 5) {
                                            echo '<span class="badge badge-success">Finalizado</span>';
                                        } else {
                                            echo '<span class="badge badge-danger">Pendiente</span>';
                                        } ?>
                                    </td>
                                    <td class="custom-table-th"><?php echo $carro_barrido['actividad'] ?></td>
                                    <td class="custom-table-th"><?php echo $carro_barrido['nombres'] ?> -
                                        <?php echo $carro_barrido['apellidos'] ?></td>
                                    <td class="custom-table-th"><?php echo $carro_barrido['documento'] ?></td>
                                    <td class="custom-table-th"><?php echo $carro_barrido['ciudad_carro'] ?></td>
                                    <td class="custom-table-th"><?php echo $carro_barrido['fecha_inicio'] ?></td>
                                    <td class="custom-table-th">
                                        <?php echo !empty($carro_barrido['fecha_fin']) ? $carro_barrido['fecha_fin'] : 'No hay registros'; ?>
                                    </td>
                                    <td class="custom-table-th"><?php echo $carro_barrido['hora_inicio'] ?></td>
                                    <td class="custom-table-th">
                                        <?php echo !empty($carro_barrido['hora_fin']) ? $carro_barrido['hora_fin'] : 'No hay registros'; ?>
                                    </td>
                                    <td>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="custom-table-th">Zona</th>
                                                    <th class="custom-table-th">Ciudad de la Zona</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Descomponer las zonas concatenadas y mostrarlas en la tabla
                                                foreach (explode("__", $carro_barrido['zonas_rutas']) as $zona_concatenada) {
                                                    $zona = explode("..", $zona_concatenada);
                                                ?>
                                                <tr>
                                                    <td><?php echo $zona[0]; // Nombre de la zona ?></td>
                                                    <td><?php echo $zona[1]; // Ciudad de la zona ?></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td class="custom-table-th">
                                        <?php echo !empty($carro_barrido['peso']) ? $carro_barrido['peso'] : 'No hay registros'; ?>
                                    </td>
                                    <td class="custom-table-th">
                                        <?php echo !empty($carro_barrido['observaciones']) ? $carro_barrido['observaciones'] : 'No hay registros'; ?>
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
    require_once("../components/footer.php");
    ?>
</div>