<?php
$titlePage = "Listado de Estados";
require_once("../components/sidebar.php");
$getCompany = $connection->prepare("SELECT * FROM empresas");
$getCompany->execute();
$companys = $getCompany->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Bootstrap modals -->
        <div class="card mb-3">
            <h2 class="card-header font-bold">Lista de Empresas</h2>
            <div class="card-body">
                <div class="row gy-3 mb-3">
                    <!-- Default Modal -->
                    <div class="col-lg-2 col-md-6">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#formEmpresas">
                            <i class="fas fa-layer-group"></i> Registrar
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" id="formEmpresas" tabindex="-1" aria-hidden="true">
                            <form class="modal-dialog" action="" method="POST" autocomplete="off"
                                name="formRegisterCompany">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel1">Registro de Empresas</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="">
                                            <label class="form-label" for="matricula">Matricula</label>
                                            <div class="input-group input-group-merge">
                                                <span id="nombre_area-span" class="input-group-text"><i
                                                        class="fas fa-layer-group"></i> </span>
                                                <input type="number" required min="0" autofocus class="form-control"
                                                    name="matricula" id="matricula"
                                                    placeholder="Ingresa la matricula de la empresa" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-body">
                                        <div class="">
                                            <label class="form-label" for="empresa">Nombre de Empresa</label>
                                            <div class="input-group input-group-merge">
                                                <span id="nombre_area-span" class="input-group-text"><i
                                                        class="fas fa-layer-group"></i> </span>
                                                <input type="text" required minlength="2" maxlength="100" autofocus
                                                    class="form-control" name="empresa" id="empresa"
                                                    placeholder="Ingresa el nombre de la empresa" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-body">
                                        <div class="">
                                            <label class="form-label" for="frecuencia">Frecuencia</label>
                                            <div class="input-group input-group-merge">
                                                <span id="nombre_area-span" class="input-group-text"><i
                                                        class="fas fa-layer-group"></i> </span>
                                                <input type="text" required minlength="2" maxlength="100" autofocus
                                                    class="form-control" name="frecuencia" id="frecuencia"
                                                    placeholder="Ingresa el nombre de la frecuencia" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                            Cancelar
                                        </button>
                                        <input type="submit" class="btn btn-primary" value="Registrar"></input>
                                        <input type="hidden" class="btn btn-info" value="formRegisterCompany"
                                            name="MM_formRegisterCompany"></input>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <?php
                if (!empty($_GET["matricula"])) {
                    $matricula = $_GET["matricula"];
                    // Consulta del vehículo por placa
                    $queryCompany = $connection->prepare("SELECT * FROM empresas WHERE matricula = :matricula");
                    $queryCompany->bindParam(":matricula", $matricula);
                    $queryCompany->execute();
                    $selectCompany = $queryCompany->fetch(PDO::FETCH_ASSOC);
                    if ($selectCompany) {
                ?>
                <div class="row">
                    <div class="col-xl">
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Actualizacion datos de la empresa
                                    <?php echo $selectCompany['matricula'] ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <form action="" method="POST" autocomplete="off" name="formUpdateCompany">
                                    <div class="mb-3">
                                        <label class="form-label" for="placa">Matricula</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                            <input readonly type="number" min="0" autofocus class="form-control"
                                                required name="matricula" id="matricula"
                                                placeholder="Actualice la matricula"
                                                value="<?php echo $selectCompany['matricula'] ?>"
                                                aria-describedby="matricula-help" />
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="empresa">Nombre de la Empresa</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i class="fas fa-handshake"></i></span>
                                            <input type="text" minlength="4" maxlength="70" autofocus
                                                class="form-control" required name="empresa" id="empresa"
                                                placeholder="Ingresa el nombre de la empresa"
                                                value="<?php echo $selectCompany['nombre_empresa'] ?>"
                                                aria-describedby="nombre-empresa-help" />
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="frecuencia">Frecuencia</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
                                            <input type="text" minlength="4" maxlength="70" autofocus
                                                class="form-control" required name="frecuencia" id="frecuencia"
                                                placeholder="Ingresa la frecuencia"
                                                value="<?php echo $selectCompany['frecuencia'] ?>"
                                                aria-describedby="frecuencia-help" />
                                        </div>
                                    </div>

                                    <input type="hidden" class="form-control" id="placa" name="matricula"
                                        value="<?php echo $selectCompany['matricula'] ?>" />
                                    <div class="modal-footer">
                                        <a class="btn btn-danger" href="empresas.php">Cancelar</a>
                                        <input type="submit" class="btn btn-primary" value="Actualizar"></input>
                                        <input type="hidden" class="btn btn-info" value="formUpdateCompany"
                                            name="MM_formUpdateCompany"></input>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    } else {
                        showErrorOrSuccessAndRedirect("error", "Registro no encontrado", "El registro que buscas no está registrado.", "empresas.php");
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
                                        <th>Matricula</th>
                                        <th>Empresa</th>
                                        <th>Frecuencia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($companys as $company) {
                                    ?>
                                    <tr>
                                        <td>
                                            <!-- Botón para actualizar -->
                                            <form method="GET" class="mt-2" action="empresas.php">
                                                <input type="hidden" name="matricula"
                                                    value="<?= $company['matricula'] ?>">
                                                <button class="btn btn-success"
                                                    onclick="return confirm('¿Desea actualizar el registro seleccionado?');"
                                                    type="submit">
                                                    <i class="bx bx-refresh" title="Actualizar"></i>
                                                </button>
                                            </form>

                                            <!-- Botón para eliminar -->
                                            <form method="POST" class="mt-2" action="" name="formDeleteCompany"
                                                onsubmit="return confirm('¿Estás seguro de eliminar del');">
                                                <input type="hidden" name="matricula"
                                                    value="<?= $company['matricula'] ?>">
                                                <button class="btn btn-danger" type="submit">
                                                    <i class="bx bx-trash" title="Eliminar"></i>
                                                </button>
                                                <input type="hidden" class="btn btn-info" value="formDeleteCompany"
                                                    name="MM_formDeleteCompany"></input>
                                            </form>
                                        </td>
                                        <td><?php echo $company['matricula'] ?></td>
                                        <td><?php echo $company['nombre_empresa'] ?></td>
                                        <td><?php echo $company['frecuencia'] ?></td>
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