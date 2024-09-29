<?php
$titlePage = "Reporte Mensual";
require_once("../components/sidebar.php");

// CONSULTA PARA EL REPORTE MENSUAL
$queryReporte = $connection->prepare("SELECT rm.*, e.nombre_empresa FROM reporte_mensual AS rm INNER JOIN empresas AS e ON rm.matricula_empresa = e.matricula");
$queryReporte->execute();
$reportes = $queryReporte->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Reporte Mensual -->
        <div class="card mb-4">
            <h2 class="card-header font-bold">Reporte Mensual</h2>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 mt-3">
                        <table id="example"
                            class="table table-striped table-bordered top-table text-center table-responsive"
                            cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th class="custom-table-th">id</th>
                                    <th class="custom-table-th">Empresa</th>
                                    <th class="custom-table-th">Mes</th>
                                    <th class="custom-table-th">Año</th>
                                    <th class="custom-table-th">Peso Total</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reportes as $reporte) { ?>
                                <tr>
                                    <td class="custom-table-th"><?php echo $reporte['id']; ?></td>
                                    <td class="custom-table-th"><?php echo $reporte['nombre_empresa']; ?></td>
                                    <td class="custom-table-th"><?php echo $reporte['mes']; ?></td>
                                    <td class="custom-table-th"><?php echo $reporte['anio']; ?></td>
                                    <td class="custom-table-th"><?php echo $reporte['peso_total']; ?> kg</td>

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
    require_once("../components/footer.php");
    ?>
</div>