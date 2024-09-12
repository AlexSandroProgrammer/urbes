<?php
$titlePage = "Lista de Rutas de barridoW";
require_once("../components/sidebar.php");
$getZones = $connection->prepare("SELECT * FROM zonas INNER JOIN ciudades on zonas.id_ciudad = ciudades.id_ciudad");
$getZones->execute();
$zonas = $getZones->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Bootstrap modals -->
        <div class="card mb-4">
            <h2 class="card-header font-bold">Lista de rutas de barrido</h2>
            <div class="card-body">
                <div class="row gy-3 mb-3">
                    <!-- Default Modal -->
                    <div class="col-lg-2 col-md-6">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formZona">
                            <i class="fas fa-layer-group"></i> Registrar
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" id="formZona" tabindex="-1" aria-hidden="true">
                            <form class="modal-dialog" action="" method="POST" autocomplete="off"
                                name="formRegisterZone">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel1">Registro de ruta de operacion
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label" for="ciudad">Nombre de la ruta</label>
                                            <div class="input-group input-group-merge">
                                                <span id="nombre_area-span" class="input-group-text"><i
                                                        class="fas fa-layer-group"></i> </span>
                                                <input type="text" required minlength="2" maxlength="100" autofocus
                                                    class="form-control" name="zona" id="zona"
                                                    placeholder="Ingresa el nombre de la ruta" />
                                            </div>
                                        </div>


                                        <div class="mb-3">
                                            <label class="form-label" for="ciudad">Elija la ciudad a la que
                                                pertenece</label>
                                            <div class="input-group input-group-merge">
                                                <span id="estado-2" class="input-group-text"><i
                                                        class="fas fa-layer-group"></i></span>
                                                <select class="form-select" name="ciudad" required>
                                                    <option value="">Seleccionar la ciudad</option>
                                                    <?php
                                                    // CONSUMO DE DATOS DE LOS PROCESOS
                                                    $ciudades = $connection->prepare("SELECT * FROM ciudades");
                                                    $ciudades->execute();
                                                    $ciudad = $ciudades->fetchAll(PDO::FETCH_ASSOC);
                                                    // Verificar si no hay datos
                                                    if (empty($ciudad)) {
                                                        echo "<option value=''>No hay datos...</option>";
                                                    } else {
                                                        // Iterar sobre los estados
                                                        foreach ($ciudad as $city) {
                                                            echo "<option value='{$city['id_ciudad']}'>{$city['ciudad']}</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                            Cancelar
                                        </button>
                                        <input type="submit" class="btn btn-primary" value="Registrar"></input>
                                        <input type="hidden" class="btn btn-info" value="formRegisterZone"
                                            name="MM_formRegisterZone"></input>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
                if (!empty($_GET["id_zone"])) {
                    $id_zone = $_GET["id_zone"];
                    // CONSUMO DE DATOS DE LOS PROCESOS
                    $queryZone = $connection->prepare("SELECT * FROM zonas INNER JOIN ciudades ON zonas.id_ciudad = ciudades.id_ciudad  WHERE id_zona = :id_zone");
                    $queryZone->bindParam(":id_zone", $id_zone);
                    $queryZone->execute();
                    $selectZone = $queryZone->fetch(PDO::FETCH_ASSOC);
                    if ($selectZone) {
                ?>
                <div class="row">
                    <div class="col-xl">
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Actualizacion datos de la ruta
                                    <?php echo $selectZone['zona'] ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <form action="" method="POST" autocomplete="off" name="formUpdateZone">
                                    <div class=" mb-3">
                                        <label class="form-label" for="ruta">Ruta</label>
                                        <div class="input-group input-group-merge">
                                            <span id="nombre-area" class="input-group-text"><i
                                                    class="fas fa-layer-group"></i></span>
                                            <input type="text" minlength="1" maxlength="70" autofocus
                                                class="form-control" required name="zona" id="zona"
                                                placeholder="Ingresa el nombre de la ruta"
                                                value="<?php echo $selectZone['zona']  ?>" aria-describedby="zona-2" />
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="actividad">Elija la actividad a la que
                                            pertenece</label>
                                        <div class="input-group input-group-merge">
                                            <span id="estado-2" class="input-group-text"><i
                                                    class="fas fa-city"></i></span>
                                            <select class="form-select" name="ciudad" required>
                                                <option value="<?php echo $selectZone['id_ciudad'] ?>">
                                                    <?php echo $selectZone['ciudad'] ?>
                                                </option>
                                                <?php
                                                        // CONSUMO DE DATOS DE LOS PROCESOS
                                                        $ciudades = $connection->prepare("SELECT * FROM ciudades");
                                                        $ciudades->execute();
                                                        $ciudad = $ciudades->fetchAll(PDO::FETCH_ASSOC);
                                                        // Verificar si no hay datos
                                                        if (empty($ciudad)) {
                                                            echo "<option value=''>No hay datos...</option>";
                                                        } else {
                                                            // Iterar sobre los estados
                                                            foreach ($ciudad as $city) {
                                                                echo "<option value='{$city['id_ciudad']}'>{$city['ciudad']}</option>";
                                                            }
                                                        }
                                                        ?>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" class="form-control" id="id_zona" name="id_zona"
                                        value="<?php echo $selectZone['id_zona']  ?>" />
                                    <div class="modal-footer">
                                        <a class="btn btn-danger" href="zonas.php">
                                            Cancelar
                                        </a>
                                        <input type="submit" class="btn btn-primary" value="Actualizar"></input>
                                        <input type="hidden" class="btn btn-info" value="formUpdateZone"
                                            name="MM_formUpdateZone"></input>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    } else {
                        showErrorOrSuccessAndRedirect("error", "Registro no encontrado", "El registro que buscas no esta registrado.", "zonas.php");
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
                                        <th>Rutas</th>
                                        <th>Ciudad</th>
                                        <th>Fecha Registro</th>
                                        <th>Fecha Actualizacion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach($zonas as $zone) {
                                    ?>
                                    <tr>
                                        <td>
                                            <form method="GET" class="mt-2" action="zonas.php">
                                                <input type="hidden" name="id_zone" value="<?= $zone['id_zona'] ?>">
                                                <button class="btn btn-success"
                                                    onclick="return confirm('¿Desea actualizar el registro seleccionado?');"
                                                    type="submit">
                                                    <i class="bx bx-refresh" title="Actualizar"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td><?php echo $zone['zona'] ?></td>
                                        <td><?php echo $zone['ciudad'] ?></td>
                                        <td><?php echo $zone['fecha_registro'] ?></td>
                                        <td><?php echo $zone['fecha_actualizacion'] ?></td>
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