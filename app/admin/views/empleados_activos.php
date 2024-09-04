<?php
$titlePage = "Lista Empleados Activos";
require_once("../components/sidebar.php");
// arreglo con ids de la consulta
$array_keys = [1, 3];
//*  CONSULTA PARA CONSUMIR LOS DATOS DE LOS EMPLEADOS ACTIVOS
$listaEmpleados = $connection->prepare("SELECT * FROM usuarios INNER JOIN tipo_usuario ON usuarios.id_tipo_usuario = tipo_usuario.id_tipo_usuario INNER JOIN estados ON usuarios.id_estado = estados.id_estado WHERE usuarios.id_tipo_usuario = :id_tipo_usuario AND usuarios.id_estado = :id_estado");
$listaEmpleados->bindParam(":id_tipo_usuario", $array_keys[1]);
$listaEmpleados->bindParam(":id_estado", $array_keys[0]);
$listaEmpleados->execute();
$empleados = $listaEmpleados->fetchAll(PDO::FETCH_ASSOC);
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
                        <a class="btn btn-primary" href="registrar-aprendiz.php">
                            <i class="fas fa-layer-group"></i> Registrar Empleado
                        </a>
                    </div>
                    <div class="col-xl-3 col-lg-4">
                        <div class="btn-group">
                            <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="fas fa-star"></i> Filtrar Aprendices
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="aprendices-lectiva.php">Aprendices Etapa Lectiva</a>
                                </li>
                                <li><a class="dropdown-item" href="aprendices-se.php">Aprendices SENA EMPRESA</a></li>
                                <li><a class="dropdown-item" href="aprendices-productiva.php">Aprendices Etapa
                                        Productiva</a>
                                </li>
                                <li><a class="dropdown-item" href="aprendices-historico.php">Aprendices Historico</a>
                                </li>
                                <li><a class="dropdown-item" href="aprendices-bloqueados.php">Aprendices Bloqueados</a>
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
                                    <th>EPS</th>
                                    <th>ARL</th>
                                    <th>Nombre Familiar</th>
                                    <th>Celular Familiar</th>
                                    <th>Parentezco Familiar</th>
                                    <th>Tipo de Usuario</th>
                                    <th>Estado</th>
                                    <th>Fecja registro</th>
                                    <th>Fecja actualizacion</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($empleados as $empleado) {
                                ?>
                                    <tr>
                                        <td>
                                            <form method="GET" action="">
                                                <input type="hidden" name="id_empleado-delete"
                                                    value="<?= $empleado['documento'] ?>">
                                                <input type="hidden" name="ruta" value="aprendices-se.php">
                                                <button class="btn btn-danger mt-2"
                                                    onclick="return confirm('¿Desea eliminar el registro seleccionado?');"
                                                    type="submit">
                                                    <i class="bx bx-trash" title="Eliminar"></i>
                                                </button>
                                            </form>
                                            <form method="GET" class="mt-2" action="editar-empleado.php">
                                                <input type="hidden" name="id_empleado-edit"
                                                    value="<?= $empleado['documento'] ?>">
                                                <input type="hidden" name="ruta" value="aprendices-se.php">
                                                <button class="btn btn-success"
                                                    onclick="return confirm('¿Desea actualizar el registro seleccionado?');"
                                                    type="submit">
                                                    <i class="bx bx-refresh" title="Actualizar"></i>
                                                </button>
                                            </form>
                                            <a href="aprendices-se.php?document=<?php echo $empleado['documento'] ?>&ruta=aprendices-se.php"
                                                class="btn btn-info mt-2" title="Cambiar Imagen"><i
                                                    class='bx bx-image-add'></i></a>
                                        </td>
                                        <td><?php echo $empleado['documento'] ?></td>
                                        <td><?php echo $empleado['nombres'] ?></td>
                                        <td><?php echo $empleado['apellidos'] ?></td>
                                        <td><?php echo $empleado['email'] ?></td>
                                        <td><?php echo $empleado['celular'] ?></td>
                                        <td><?php echo $empleado['id_ficha'] ?></td>
                                        <td><?php echo $empleado['nombre_programa'] ?></td>
                                        <td><?php echo strtoupper($empleado['patrocinio']) ?></td>
                                        <td><?php echo $empleado['nombre_empresa'] ?></td>
                                        <td><?php echo $edad ?></td>
                                        <td><?php echo $empleado['sexo'] ?></td>
                                        <td><?php echo $empleado['tipo_usuario'] ?></td>
                                        <td><?php echo $empleado['estado_empleado'] ?></td>
                                        <td><?php echo $empleado['nombre_estado_se'] ?></td>
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