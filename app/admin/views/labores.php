<?php
$titlePage = "Lista de Labores";
require_once("../components/sidebar.php");
$getLabors = $connection->prepare("SELECT * FROM labores INNER JOIN actividades ON labores.id_actividad = actividades.id_actividad ");
$getLabors->execute();
$labors = $getLabors->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Bootstrap modals -->
        <div class="card mb-4">
            <h2 class="card-header font-bold">Lista de Labores</h2>
            <div class="card-body">
                <div class="row gy-2 mb-2">
                    <!-- Default Modal -->
                    <div class="col-lg-2 col-md-6">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#formLabores">
                            <i class="fas fa-layer-group"></i> Registrar
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" id="formLabores" tabindex="-1" aria-hidden="true">
                            <form class="modal-dialog" action="" method="POST" autocomplete="off"
                                name="formRegisterLabors">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel1">Registro de Labores</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label" for="labor">Nombre de la Labor</label>
                                            <div class="input-group input-group-merge">
                                                <span id="nombre_area-span" class="input-group-text"><i
                                                        class="fas fa-layer-group"></i> </span>
                                                <input type="text" required minlength="2" maxlength="100" autofocus
                                                    class="form-control" name="labor" id="labor"
                                                    placeholder="Ingresa el nombre de la labor" />
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="actividad">Elija la actividad a la que
                                                pertenece</label>
                                            <div class="input-group input-group-merge">
                                                <span id="estado-2" class="input-group-text"><i
                                                        class="fas fa-layer-group"></i></span>
                                                <select class="form-select" name="actividad" required>
                                                    <option value="">Seleccionar la actividad</option>
                                                    <?php
                                                    // CONSUMO DE DATOS DE LOS PROCESOS
                                                    $actividades = $connection->prepare("SELECT * FROM actividades");
                                                    $actividades->execute();
                                                    $actividades_a = $actividades->fetchAll(PDO::FETCH_ASSOC);
                                                    // Verificar si no hay datos
                                                    if (empty($actividades_a)) {
                                                        echo "<option value=''>No hay datos...</option>";
                                                    } else {
                                                        // Iterar sobre los estados
                                                        foreach ($actividades_a as $activi) {
                                                            echo "<option value='{$activi['id_actividad']}'>{$activi['actividad']}</option>";
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
                                        <input type="hidden" class="btn btn-info" value="formRegisterLabors"
                                            name="MM_formRegisterLabors"></input>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
                if (!empty($_GET["id_labor"])) {
                    $id_labors = $_GET["id_labor"];
                    // CONSUMO DE DATOS DE LOS PROCESOS
                    $queryLabors = $connection->prepare("SELECT * FROM labores INNER JOIN actividades ON labores.id_actividad = actividades.id_actividad  WHERE labores.id_labor = :id_labors");
                    $queryLabors->bindParam(":id_labors", $id_labors);
                    $queryLabors->execute();
                    $selectLabors = $queryLabors->fetch(PDO::FETCH_ASSOC);
                    if ($selectLabors) {
                ?>
                <div class="row">
                    <div class="col-xl">
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Actualizacion datos la labor
                                    <?php echo $selectLabors['labor'] ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <form action="" method="POST" autocomplete="off" name="formUpdateLabors">
                                    <div class=" mb-3">
                                        <label class="form-label" for="labor">Labor</label>
                                        <div class="input-group input-group-merge">
                                            <span id="nombre-area" class="input-group-text"><i
                                                    class="fas fa-layer-group"></i></span>
                                            <input type="text" minlength="4" maxlength="70" autofocus
                                                class="form-control" required name="labor" id="labor"
                                                placeholder="Ingresa el nombre de la Labor"
                                                value="<?php echo $selectLabors['labor']  ?>"
                                                aria-describedby="actividad-2" />
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="actividad">Elija la actividad a la que
                                            pertenece</label>
                                        <div class="input-group input-group-merge">
                                            <span id="estado-2" class="input-group-text"><i
                                                    class="fas fa-user"></i></span>
                                            <select class="form-select" name="actividad" required>
                                                <option value="<?php echo $selectLabors['id_actividad'] ?>">
                                                    <?php echo $selectLabors['actividad'] ?>
                                                </option>
                                                <?php
                                                        // CONSUMO DE DATOS DE LOS PROCESOS
                                                        $actividades = $connection->prepare("SELECT * FROM actividades");
                                                        $actividades->execute();
                                                        $actividades_a = $actividades->fetchAll(PDO::FETCH_ASSOC);
                                                        // Verificar si no hay datos
                                                        if (empty($actividades_a)) {
                                                            echo "<option value=''>No hay datos...</option>";
                                                        } else {
                                                            // Iterar sobre los estados
                                                            foreach ($actividades_a as $activi) {
                                                                echo "<option value='{$activi['id_actividad']}'>{$activi['actividad']}</option>";
                                                            }
                                                        }
                                                        ?>
                                            </select>
                                        </div>
                                    </div>
                            </div>
                            <input type="hidden" class="form-control" id="id_labor" name="id_labor"
                                value="<?php echo $selectLabors['id_labor']  ?>" />
                            <div class="modal-footer">
                                <a class="btn btn-danger" href="labores.php">
                                    Cancelar
                                </a>
                                <input type="submit" class="btn btn-primary" value="Actualizar"></input>
                                <input type="hidden" class="btn btn-info" value="formUpdateLabors"
                                    name="MM_formUpdateLabors"></input>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                    } else {
                        showErrorOrSuccessAndRedirect("error", "Registro no encontrado", "El registro que buscas no esta registrado.", "labores.php");
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
                                    <th>Labores</th>
                                    <th>Actividad</th>
                                    <th>Fecha Registro</th>
                                    <th>Fecha Actualizacion</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                        foreach ($labors as $work) {
                        ?>
                                <tr>
                                    <td>
                                        <form method="GET" class="mt-2" action="labores.php">
                                            <input type="hidden" name="id_labor" value="<?= $work['id_labor'] ?>">
                                            <button class="btn btn-success"
                                                onclick="return confirm('¿Desea actualizar el registro seleccionado?');"
                                                type="submit">
                                                <i class="bx bx-refresh" title="Actualizar"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td><?php echo $work['labor'] ?></td>
                                    <td><?php echo $work['actividad'] ?></td>
                                    <td><?php echo $work['fecha_registro'] ?></td>
                                    <td><?php echo $work['fecha_actualizacion'] ?></td>

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