<?php
$titlePage = "Lista de Vehiculos de Operacion";
require_once("../components/sidebar.php");
$getVehiculos = $connection->prepare("SELECT * FROM vehiculos");
$getVehiculos->execute();
$vehiculos = $getVehiculos->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Bootstrap modals -->
        <div class="card mb-4">
            <h2 class="card-header font-bold">Lista de Vehiculos de Operacion</h2>
            <div class="card-body">
                <div class="row gy-3 mb-3">
                    <!-- Default Modal -->
                    <div class="col-lg-2 col-md-6">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#formVehiculos">
                            <i class="fas fa-layer-group"></i> Registrar
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" id="formVehiculos" tabindex="-1" aria-hidden="true">
                            <form class="modal-dialog" action="" method="POST" autocomplete="off"
                                name="formRegisterCar">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel1">Registro de Vehiculos de
                                            Operacion</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label" for="placa">Placa</label>
                                            <div class="input-group input-group-merge">
                                                <span id="nombre_area-span" class="input-group-text"><i
                                                        class="fas fa-layer-group"></i> </span>
                                                <input type="text" required minlength="2" maxlength="20" autofocus
                                                    class="form-control" name="placa" id="placa"
                                                    placeholder="Ingrese la placa del vehiculo" />
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="ciudad">Nombre del vehiculo</label>
                                                <div class="input-group input-group-merge">
                                                    <span id="nombre_area-span" class="input-group-text"><i
                                                            class="fas fa-layer-group"></i> </span>
                                                    <input type="text" required minlength="2" maxlength="100" autofocus
                                                        class="form-control" name="vehiculo" id="vehiculo"
                                                        placeholder="Ingrese el vehiculo" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                                Cancelar
                                            </button>
                                            <input type="submit" class="btn btn-primary" value="Registrar"></input>
                                            <input type="hidden" class="btn btn-info" value="formRegisterCar"
                                                name="MM_formRegisterCar"></input>
                                        </div>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
                if (!empty($_GET["placa"])) {
                    $placa = $_GET["placa"];
                    // CONSUMO DE DATOS DE LOS PROCESOS
                    $queryCar = $connection->prepare("SELECT * FROM vehiculos WHERE placa = :placa");
                    $queryCar->bindParam(":placa", $placa);
                    $queryCar->execute();
                    $selectCar = $queryCar->fetch(PDO::FETCH_ASSOC);
                    if ($selectCar) {
                ?>
                <div class="row">
                    <div class="col-xl">
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Actualizacion datos del vehiculo
                                    <?php echo $selectCar['placa']?>
                                    <?php echo $selectCar['vehiculo']?>

                                </h5>
                            </div>
                            <div class="card-body">
                                <form action="" method="POST" autocomplete="off" name="formUpdateCar">
                                    <div class="mb-3">
                                        <label class="form-label" for="placa">Placa del Vehículo</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                            <input type="text" minlength="4" maxlength="70" autofocus
                                                class="form-control" required name="placaUpdate" id="placaUpdate"
                                                placeholder="Actualice la placa"
                                                value="<?php echo $selectCar['placa'] ?>"
                                                aria-describedby="placa-help" />
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="vehiculo">Nombre del Vehículo</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i class="fas fa-car"></i></span>
                                            <input type="text" minlength="4" maxlength="70" autofocus
                                                class="form-control" required name="vehiculo" id="vehiculo"
                                                placeholder="Ingresa el nombre del vehículo"
                                                value="<?php echo $selectCar['vehiculo'] ?>"
                                                aria-describedby="nombre-vehiculo-help" />
                                        </div>
                                    </div>


                            </div>
                            <input type="hidden" class="form-control" id="placa" name="placa"
                                value="<?php echo $selectCar['placa']  ?>" />
                            <div class="modal-footer">
                                <a class="btn btn-danger" href="vehiculos.php">
                                    Cancelar
                                </a>
                                <input type="submit" class="btn btn-primary" value="Actualizar"></input>
                                <input type="hidden" class="btn btn-info" value="formUpdateCar"
                                    name="MM_formUpdateCar"></input>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                    } else {
                        showErrorOrSuccessAndRedirect("error", "Registro no encontrado", "El registro que buscas no esta registrado.", "vehiculos.php");
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
                                    <th>Placa</th>
                                    <th>vehiculo</th>
                                    <th>Fecha Registro</th>
                                    <th>Fecha Actualizacion</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($vehiculos as $car) {
                                    ?>
                                <tr>
                                    <td>
                                        <form method="GET" class="mt-2" action="vehiculos.php">
                                            <input type="hidden" name="placa" value="<?= $car['placa'] ?>">
                                            <button class="btn btn-success"
                                                onclick="return confirm('¿Desea actualizar el registro seleccionado?');"
                                                type="submit">
                                                <i class="bx bx-refresh" title="Actualizar"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td><?php echo $car['placa'] ?></td>
                                    <td><?php echo $car['vehiculo'] ?></td>
                                    <td><?php echo $car['fecha_registro'] ?></td>
                                    <td><?php echo $car['fecha_actualizacion'] ?></td>
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