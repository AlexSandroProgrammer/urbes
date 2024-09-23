<?php
$titlePage = "Lista Socios Bloqueados";
require_once("../components/sidebar.php");
// arreglo con ids de la consulta
$array_keys = [1, 2];
//*  CONSULTA PARA CONSUMIR LOS DATOS DE LOS SOCIOS BLOQUEADOS
$listaSocios = $connection->prepare("SELECT * FROM usuarios INNER JOIN tipo_usuario ON usuarios.id_tipo_usuario = tipo_usuario.id_tipo_usuario INNER JOIN estados ON usuarios.id_estado = estados.id_estado WHERE usuarios.id_tipo_usuario = :id_tipo_usuario AND usuarios.id_estado = :id_estado");
$listaSocios->bindParam(":id_tipo_usuario", $array_keys[0]);
$listaSocios->bindParam(":id_estado", $array_keys[1]);
$listaSocios->execute();
$socios = $listaSocios->fetchAll(PDO::FETCH_ASSOC);
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
                        <a class="btn btn-primary" href="registrar_socio.php">
                            <i class="fas fa-user"></i> Registrar Socio
                        </a>
                    </div>
                    <div class="col-xl-3 col-lg-4">
                        <div class="btn-group">
                            <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="fas fa-star"></i> Filtrar Socios
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="socios_activos.php">Socios Activos</a>
                                </li>
                                <li><a class="dropdown-item" href="socios_bloqueados.php">Socios Bloqueados</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="socios_eliminados.php">Socios Eliminados</a>
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
                                    <th>Tipo de Usuario</th>
                                    <th>Estado</th>
                                    <th>Fecha registro</th>
                                    <th>Fecha actualizacion</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($socios as $socio) {

                                    // desencriptacion de contraseña
                                    $password = bcrypt_password($socio['password']);
                                ?>
                                <tr>
                                    <td>
                                        <form method="GET" action="">
                                            <input type="hidden" name="id_partner-delete"
                                                value="<?= $socio['documento'] ?>">
                                            <input type="hidden" name="ruta" value="socios_bloqueados.php">
                                            <button class="btn btn-danger mt-2"
                                                onclick="return confirm('¿Desea eliminar el registro seleccionado?');"
                                                type="submit">
                                                <i class="bx bx-trash" title="Eliminar"></i>
                                            </button>
                                        </form>
                                        <form method="GET" class="mt-2" action="editar_socio.php">
                                            <input type="hidden" name="id_partner-edit"
                                                value="<?= $socio['documento'] ?>">
                                            <input type="hidden" name="ruta" value="socios_bloqueados.php">
                                            <button class="btn btn-success"
                                                onclick="return confirm('¿Desea actualizar el registro seleccionado?');"
                                                type="submit">
                                                <i class="bx bx-refresh" title="Actualizar"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td><?php echo $socio['tipo_documento'] ?></td>
                                    <td><?php echo $socio['documento'] ?></td>
                                    <td><?php echo $socio['nombres'] ?></td>
                                    <td><?php echo $socio['apellidos'] ?></td>
                                    <td><?php echo $socio['celular'] ?></td>
                                    <td><?php echo $password ?></td>
                                    <td><?php echo $socio['tipo_usuario'] ?></td>
                                    <td><?php echo $socio['estado'] ?></td>
                                    <td><?php echo $socio['fecha_registro'] ?></td>
                                    <td><?php echo $socio['fecha_actualizacion'] ?></td>
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