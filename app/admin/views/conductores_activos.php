<?php
$titlePage = "Lista Conductores Activos";
require_once("../components/sidebar.php");
// arreglo con ids de la consulta
$array_keys = [1, 4];
//*  CONSULTA PARA CONSUMIR LOS DATOS DE LOS CONDUCTORES ACTIVOS
$listaConductores = $connection->prepare("SELECT * FROM usuarios INNER JOIN tipo_usuario ON usuarios.id_tipo_usuario = tipo_usuario.id_tipo_usuario INNER JOIN estados ON usuarios.id_estado = estados.id_estado INNER JOIN ciudades ON usuarios.id_ciudad = ciudades.id_ciudad WHERE usuarios.id_tipo_usuario = :id_tipo_usuario AND usuarios.id_estado = :id_estado");
$listaConductores->bindParam(":id_tipo_usuario", $array_keys[1]);
$listaConductores->bindParam(":id_estado", $array_keys[0]);
$listaConductores->execute();
$conductores = $listaConductores->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-4">
            <h2 class="card-header font-bold"><?php echo $titlePage ?></h2>
            <div class="card-body">
                <div class="row gy-3 mb-3">
                    <!-- Default Modal -->
                    <div class="col-xl-3 col-lg-4">
                        <!-- Button trigger modal -->
                        <a class="btn btn-primary" href="registrar_empleado.php">
                            <i class="fas fa-user"></i> Registrar Conductor
                        </a>
                    </div>
                    <div class="col-xl-3 col-lg-4">
                        <div class="btn-group">
                            <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="fas fa-star"></i> Filtrar Conductores
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="conductores_activos.php">Conductores Activos</a>
                                </li>
                                <li><a class="dropdown-item" href="conductores_bloqueados.php">Conductores
                                        Bloqueados</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="conductores_eliminados.php">Conductores
                                        Eliminados</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 mt-5">
                        <table id="example"
                            class="table table-striped table-bordered top-table table-responsive text-center"
                            cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Acciones</th>
                                    <th>Tipo documento</th>
                                    <th>N. documento</th>
                                    <th>Nombres</th>
                                    <th>Apellidos</th>
                                    <th>Celular</th>
                                    <th>Contraseña</th>
                                    <th>Ciudad</th>
                                    <th>EPS</th>
                                    <th>ARL</th>
                                    <th>RH</th>
                                    <th>Fecha Inicio Contrato</th>
                                    <th>Fecha Fin Contrato</th>
                                    <th>Nombre Familiar</th>
                                    <th>Celular Familiar</th>
                                    <th>Parentezco Familiar</th>
                                    <th>Tipo de Usuario</th>
                                    <th>Estado</th>
                                    <th>Fecha registro</th>
                                    <th>Fecha actualizacion</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($conductores as $empleado) {
                                    // desencriptacion de contraseña
                                    $password = bcrypt_password($empleado['password']);
                                    $fecha_inicial = $empleado['fecha_inicio'];
                                    $fecha_final = $empleado['fecha_fin'];
                                    if (isNotEmpty([$fecha_inicial, $fecha_final])) {
                                        $fecha_inicio = DateTime::createFromFormat('Y-m-d', $empleado['fecha_inicio'])->format('d/m/Y');
                                        $fecha_fin = DateTime::createFromFormat('Y-m-d', $empleado['fecha_fin'])->format('d/m/Y');
                                    }
                                ?>
                                <tr>
                                    <td>
                                        <form method="GET" actionz="">
                                            <input type="hidden" name="id_employee-delete"
                                                value="<?= $empleado['documento'] ?>">
                                            <input type="hidden" name="ruta" value="conductores_activos.php">
                                            <button class="btn btn-danger mt-2"
                                                onclick="return confirm('¿Desea eliminar el registro seleccionado?');"
                                                type="submit">
                                                <i class="bx bx-trash" title="Eliminar"></i>
                                            </button>
                                        </form>
                                        <form method="GET" class="mt-2" action="editar_empleado.php">
                                            <input type="hidden" name="id_employee-edit"
                                                value="<?= $empleado['documento'] ?>">
                                            <input type="hidden" name="ruta" value="conductores_activos.php">
                                            <button class="btn btn-success"
                                                onclick="return confirm('¿Desea actualizar el registro seleccionado?');"
                                                type="submit">
                                                <i class="bx bx-refresh" title="Actualizar"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td><?php echo $empleado['tipo_documento'] ?></td>
                                    <td><?php echo $empleado['documento'] ?></td>
                                    <td><?php echo $empleado['nombres'] ?></td>
                                    <td><?php echo $empleado['apellidos'] ?></td>
                                    <td><?php echo $empleado['celular'] ?></td>
                                    <td><?php echo $password ?></td>
                                    <td><?php echo $empleado['ciudad'] ?></td>
                                    <td><?php echo $empleado['eps'] ?></td>
                                    <td><?php echo $empleado['arl'] ?></td>
                                    <td><?php echo $empleado['rh'] ?></td>
                                    <td><?php echo $fecha_inicio ?></td>
                                    <td><?php echo $fecha_fin ?></td>
                                    <td><?php echo $empleado['nombre_familiar'] ?></td>
                                    <td><?php echo $empleado['celular_familiar'] ?></td>
                                    <td><?php echo $empleado['parentezco_familiar'] ?></td>
                                    <td><?php echo $empleado['tipo_usuario'] ?></td>
                                    <td><?php echo $empleado['estado'] ?></td>
                                    <td><?php echo $empleado['fecha_registro'] ?></td>
                                    <td><?php echo $empleado['fecha_actualizacion'] ?></td>
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