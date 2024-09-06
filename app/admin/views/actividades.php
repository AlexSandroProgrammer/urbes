<?php
$titlePage = "Lista de actividades";
require_once("../components/sidebar.php");
$getActivities = $connection->prepare("SELECT * FROM actividades");
$getActivities->execute();
$activities = $getActivities->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Bootstrap modals -->
        <div class="card mb-4">
            <h2 class="card-header font-bold">Lista de Actividades</h2>
            <div class="card-body">
                <div class="row gy-3 mb-3">
                    <!-- Default Modal -->
                    <div class="col-lg-2 col-md-6">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#formActividad">
                            <i class="fas fa-layer-group"></i> Registrar
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" id="formActividad" tabindex="-1" aria-hidden="true">
                            <form class="modal-dialog" action="" method="POST" autocomplete="off"
                                name="formRegisterActivity">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel1">Registro de Actividades</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label" for="actividad">Nombre de la actividad</label>
                                            <div class="input-group input-group-merge">
                                                <span id="nombre_area-span" class="input-group-text"><i
                                                        class="fas fa-layer-group"></i> </span>
                                                <input type="text" required minlength="2" maxlength="100" autofocus
                                                    class="form-control" name="actividad" id="actividad"
                                                    placeholder="Ingresa el nombre de la actividad" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                            Cancelar
                                        </button>
                                        <input type="submit" class="btn btn-primary" value="Registrar"></input>
                                        <input type="hidden" class="btn btn-info" value="formRegisterActivity"
                                            name="MM_formRegisterActivity"></input>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
                if (!empty($_GET["id_activity"])) {
                    $id_activity = $_GET["id_activity"];
                    // CONSUMO DE DATOS DE LOS PROCESOS
                    $queryActivities = $connection->prepare("SELECT * FROM actividades WHERE id_actividad = :id_activity");
                    $queryActivities->bindParam(":id_activity", $id_activity);
                    $queryActivities->execute();
                    $selectActivity = $queryActivities->fetch(PDO::FETCH_ASSOC);
                    if ($selectActivity) {
                ?>
                        <div class="row">
                            <div class="col-xl">
                                <div class="card mb-4">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Actualizacion datos Actividad
                                            <?php echo $selectActivity['actividad'] ?>
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" autocomplete="off" name="formUpdateActivity">
                                            <div class=" mb-3">
                                                <label class="form-label" for="actividad">Actividad</label>
                                                <div class="input-group input-group-merge">
                                                    <span id="nombre-area" class="input-group-text"><i
                                                            class="fas fa-layer-group"></i></span>
                                                    <input type="text" minlength="4" maxlength="70" autofocus
                                                        class="form-control" required name="actividad" id="actividad"
                                                        placeholder="Ingresa el nombre de la actividad"
                                                        value="<?php echo $selectActivity['actividad']  ?>"
                                                        aria-describedby="actividad-2" />
                                                </div>
                                            </div>
                                            <input type="hidden" class="form-control" id="id_actividad" name="id_actividad"
                                                value="<?php echo $selectActivity['id_actividad']  ?>" />
                                            <div class="modal-footer">
                                                <a class="btn btn-danger" href="actividades.php">
                                                    Cancelar
                                                </a>
                                                <input type="submit" class="btn btn-primary" value="Actualizar"></input>
                                                <input type="hidden" class="btn btn-info" value="formUpdateActivity"
                                                    name="MM_formUpdateActivity"></input>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                    } else {
                        showErrorOrSuccessAndRedirect("error", "Registro no encontrado", "El registro que buscas no esta registrado.", "actividades.php");
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
                                        <th>Actividades</th>
                                        <th>Fecha Registro</th>
                                        <th>Fecha Actualizacion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($activities as $activity) {
                                    ?>
                                        <tr>
                                            <td>
                                                <form method="GET" class="mt-2" action="actividades.php">
                                                    <input type="hidden" name="id_activity"
                                                        value="<?= $activity['id_actividad'] ?>">
                                                    <button class="btn btn-success"
                                                        onclick="return confirm('¿Desea actualizar el registro seleccionado?');"
                                                        type="submit">
                                                        <i class="bx bx-refresh" title="Actualizar"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            <td><?php echo $activity['actividad'] ?></td>
                                            <td><?php echo $activity['fecha_registro'] ?></td>
                                            <td><?php echo $activity['fecha_actualizacion'] ?></td>
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