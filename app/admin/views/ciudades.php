<?php
$titlePage = "Lista de Ciudades de operacion";
require_once("../components/sidebar.php");
$getCiudades = $connection->prepare("SELECT * FROM ciudades");
$getCiudades->execute();
$ciudades = $getCiudades->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Bootstrap modals -->
        <div class="card mb-4">
            <h2 class="card-header font-bold">Lista de Ciudades de Operacion</h2>
            <div class="card-body">
                <div class="row gy-3 mb-3">
                    <!-- Default Modal -->
                    <div class="col-lg-2 col-md-6">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#formCiudad">
                            <i class="fas fa-layer-group"></i> Registrar
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" id="formCiudad" tabindex="-1" aria-hidden="true">
                            <form class="modal-dialog" action="" method="POST" autocomplete="off"
                                name="formRegisterCity">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel1">Registro de Ciudades de Operacion</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label" for="ciudad">Nombre de la Ciudad</label>
                                            <div class="input-group input-group-merge">
                                                <span id="nombre_area-span" class="input-group-text"><i
                                                        class="fas fa-layer-group"></i> </span>
                                                <input type="text" required minlength="2" maxlength="100" autofocus
                                                    class="form-control" name="ciudad" id="ciudad"
                                                    placeholder="Ingresa el nombre de la ciudad" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                            Cancelar
                                        </button>
                                        <input type="submit" class="btn btn-primary" value="Registrar"></input>
                                        <input type="hidden" class="btn btn-info" value="formRegisterCity"
                                            name="MM_formRegisterCity"></input>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
                if (!empty($_GET["id_city"])) {
                    $id_city = $_GET["id_city"];
                    // CONSUMO DE DATOS DE LOS PROCESOS
                    $queryCity = $connection->prepare("SELECT * FROM ciudades WHERE id_ciudad = :id_city");
                    $queryCity->bindParam(":id_city", $id_city);
                    $queryCity->execute();
                    $selectCity = $queryCity->fetch(PDO::FETCH_ASSOC);
                    if ($selectCity) {
                ?>
                        <div class="row">
                            <div class="col-xl">
                                <div class="card mb-4">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Actualizacion datos de la ciudad
                                            <?php echo $selectCity['ciudad'] ?>
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" autocomplete="off" name="formUpdateCity">
                                            <div class=" mb-3">
                                                <label class="form-label" for="ciudad">Ciudad</label>
                                                <div class="input-group input-group-merge">
                                                    <span id="nombre-area" class="input-group-text"><i
                                                            class="fas fa-layer-group"></i></span>
                                                    <input type="text" minlength="4" maxlength="70" autofocus
                                                        class="form-control" required name="ciudad" id="ciudad"
                                                        placeholder="Ingresa el nombre de la Ciudad"
                                                        value="<?php echo $selectCity['ciudad']  ?>"
                                                        aria-describedby="ciudad-2" />
                                                </div>
                                            </div>
                                            <input type="hidden" class="form-control" id="id_ciudad" name="id_ciudad"
                                                value="<?php echo $selectCity['id_ciudad']  ?>" />
                                            <div class="modal-footer">
                                                <a class="btn btn-danger" href="ciudades.php">
                                                    Cancelar
                                                </a>
                                                <input type="submit" class="btn btn-primary" value="Actualizar"></input>
                                                <input type="hidden" class="btn btn-info" value="formUpdateCity"
                                                    name="MM_formUpdateCity"></input>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                    } else {
                        showErrorOrSuccessAndRedirect("error", "Registro no encontrado", "El registro que buscas no esta registrado.", "ciudades.php");
                        exit();
                    }
                }
                ?>
                <div class="row">
                    <div class="col-lg-12 mt-3">
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered top-table" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>Acciones</th>
                                        <th>Ciudades</th>
                                        <th>Fecha Registro</th>
                                        <th>Fecha Actualizacion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach($ciudades as $city) {
                                    ?>
                                        <tr>
                                            <td>
                                                <form method="GET" class="mt-2" action="ciudades.php">
                                                    <input type="hidden" name="id_city"
                                                        value="<?= $city['id_ciudad'] ?>">
                                                    <button class="btn btn-success"
                                                        onclick="return confirm('¿Desea actualizar el registro seleccionado?');"
                                                        type="submit">
                                                        <i class="bx bx-refresh" title="Actualizar"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            <td><?php echo $city['ciudad'] ?></td>
                                            <td><?php echo $city['fecha_registro'] ?></td>
                                            <td><?php echo $city['fecha_actualizacion'] ?></td>
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
    </div>
    <?php
    require_once("../components/footer.php")
    ?>