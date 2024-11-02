<?php
$titlePage = "Listado de Estados";
require_once("../components/sidebar.php");
$getStates = $connection->prepare("SELECT * FROM estados");
$getStates->execute();
$states = $getStates->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Bootstrap modals -->
        <div class="card mb-4">
            <h2 class="card-header font-bold">Lista de Estados</h2>
            <div class="card-body">
                <div class="row gy-3 mb-3">
                    <!-- Default Modal -->
                    <div class="col-lg-2 col-md-6">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#formEstados">
                            <i class="fas fa-layer-group"></i> Registrar
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" id="formEstados" tabindex="-1" aria-hidden="true">
                            <form class="modal-dialog" action="" method="POST" autocomplete="off"
                                name="formRegisterState">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel1">Registro de Estado</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label" for="estado">Nombre de Estado</label>
                                            <div class="input-group input-group-merge">
                                                <span id="nombre_area-span" class="input-group-text"><i
                                                        class="fas fa-layer-group"></i> </span>
                                                <input type="text" required minlength="2" maxlength="100" autofocus
                                                    class="form-control" name="estado" id="estado"
                                                    placeholder="Ingresa el nombre del estado" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                            Cancelar
                                        </button>
                                        <input type="submit" class="btn btn-primary" value="Registrar"></input>
                                        <input type="hidden" class="btn btn-info" value="formRegisterState"
                                            name="MM_formRegisterState"></input>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 mt-3">
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered top-table" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($states as $state) {
                                    ?>
                                    <tr>
                                        <td><?php echo $state['id_estado'] ?></td>
                                        <td><?php echo $state['estado'] ?></td>
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