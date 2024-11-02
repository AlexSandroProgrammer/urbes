<?php
$titlePage = "Lista Aforos";
require_once("../components/sidebar.php");

// CONSULTA PARA LLAMAR TODOS LOS AFOROS
$queryAforos = $connection->prepare("SELECT a.*, e.nombre_empresa FROM aforos AS a INNER JOIN empresas AS e ON a.matricula_empresa = e.matricula");
$queryAforos->execute();
$aforos = $queryAforos->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Bootstrap modals -->
        <div class="card mb-4">
            <h2 class="card-header font-bold">Aforos</h2>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 mt-3">
                        <div class="table-responsive">
                            <table id="example"
                                class="table table-striped table-bordered top-table text-center table-responsive"
                                cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="custom-table-th">Acciones</th>
                                        <th class="custom-table-th">ID</th>
                                        <th class="custom-table-th">Empresa</th>
                                        <th class="custom-table-th">Fecha Registro</th>
                                        <th class="custom-table-th">Peso Total</th>
                                        <th class="custom-table-th">Peso 1</th>
                                        <th class="custom-table-th">Peso 2</th>
                                        <th class="custom-table-th">Peso 3</th>
                                        <th class="custom-table-th">Peso 4</th>
                                        <th class="custom-table-th">Peso 5</th>
                                        <th class="custom-table-th">Foto Aforo1</th>
                                        <th class="custom-table-th">Foto Aforo2</th>
                                        <th class="custom-table-th">Foto Aforo3</th>
                                        <th class="custom-table-th">Foto Aforo4</th>
                                        <th class="custom-table-th">Foto Aforo5</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($aforos) > 0) { ?>
                                    <?php foreach ($aforos as $aforo) { ?>
                                    <tr>
                                        <td class="custom-table-th">
                                            <form method="GET" class="mt-2" action="">
                                                <input type="hidden" name="id_registro"
                                                    value="<?php echo $aforo['id']; ?>">
                                                <button class="btn btn-success"
                                                    onclick="return confirm('¿Desea actualizar el registro seleccionado?');"
                                                    type="submit">
                                                    <i class="bx bx-refresh" title="Actualizar"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td class="custom-table-th"><?php echo $aforo['id']; ?></td>
                                        <td class="custom-table-th"><?php echo $aforo['nombre_empresa']; ?></td>
                                        <td class="custom-table-th"><?php echo $aforo['fecha_registro']; ?></td>
                                        <td class="custom-table-th">
                                            <?php echo !empty($aforo['peso']) ? $aforo['peso'] . ' kg' : 'Sin peso'; ?>
                                        </td>
                                        <td class="custom-table-th">
                                            <?php echo !empty($aforo['peso_1']) ? $aforo['peso_1'] . ' kg' : 'Sin peso'; ?>
                                        </td>
                                        <td class="custom-table-th">
                                            <?php echo !empty($aforo['peso_2']) ? $aforo['peso_2'] . ' kg' : 'Sin peso'; ?>
                                        </td>
                                        <td class="custom-table-th">
                                            <?php echo !empty($aforo['peso_3']) ? $aforo['peso_3'] . ' kg' : 'Sin peso'; ?>
                                        </td>
                                        <td class="custom-table-th">
                                            <?php echo !empty($aforo['peso_4']) ? $aforo['peso_4'] . ' kg' : 'Sin peso'; ?>
                                        </td>
                                        <td class="custom-table-th">
                                            <?php echo !empty($aforo['peso_5']) ? $aforo['peso_5'] . ' kg' : 'Sin peso'; ?>
                                        </td>
                                        <td class="custom-table-th">
                                            <?php if (!empty($aforo['foto_1'])) { ?>
                                            <img src="../../employee/assets/images/<?php echo $aforo['foto_1']; ?>"
                                                alt="Foto Aforo" class="w-px-100 h-px-100 rounded-circle">
                                            <button class="btn btn-primary mt-2 view-photo-btn"
                                                data-photo="../../employee/assets/images/<?php echo $aforo['foto_1']; ?>">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <?php } else { ?>
                                            <p>Sin foto</p>
                                            <?php } ?>
                                        </td>
                                        <td class="custom-table-th">
                                            <?php if (!empty($aforo['foto_2'])) { ?>
                                            <img src="../../employee/assets/images/<?php echo $aforo['foto_2']; ?>"
                                                alt="Foto Aforo" class="w-px-100 h-px-100 rounded-circle">
                                            <button class="btn btn-primary mt-2 view-photo-btn"
                                                data-photo="../../employee/assets/images/<?php echo $aforo['foto_2']; ?>">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <?php } else { ?>
                                            <p>Sin foto</p>
                                            <?php } ?>
                                        </td>
                                        <td class="custom-table-th">
                                            <?php if (!empty($aforo['foto_3'])) { ?>
                                            <img src="../../employee/assets/images/<?php echo $aforo['foto_3']; ?>"
                                                alt="Foto Aforo" class="w-px-100 h-px-100 rounded-circle">
                                            <button class="btn btn-primary mt-2 view-photo-btn"
                                                data-photo="../../employee/assets/images/<?php echo $aforo['foto_3']; ?>">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <?php } else { ?>
                                            <p>Sin foto</p>
                                            <?php } ?>
                                        </td>
                                        <td class="custom-table-th">
                                            <?php if (!empty($aforo['foto_4'])) { ?>
                                            <img src="../../employee/assets/images/<?php echo $aforo['foto_4']; ?>"
                                                alt="Foto Aforo" class="w-px-100 h-px-100 rounded-circle">
                                            <button class="btn btn-primary mt-2 view-photo-btn"
                                                data-photo="../../employee/assets/images/<?php echo $aforo['foto_4']; ?>">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <?php } else { ?>
                                            <p>Sin foto</p>
                                            <?php } ?>
                                        </td>
                                        <td class="custom-table-th">
                                            <?php if (!empty($aforo['foto_5'])) { ?>
                                            <img src="../../employee/assets/images/<?php echo $aforo['foto_5']; ?>"
                                                alt="Foto Aforo" class="w-px-100 h-px-100 rounded-circle">
                                            <button class="btn btn-primary mt-2 view-photo-btn"
                                                data-photo="../../employee/assets/images/<?php echo $aforo['foto_5']; ?>">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <?php } else { ?>
                                            <p>Sin foto</p>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    <?php } else { ?>
                                    <tr>
                                        <td colspan="15" class="text-center">No hay registros disponibles.</td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php require_once("../components/footer.php"); ?>
        </div>
    </div>
</div>