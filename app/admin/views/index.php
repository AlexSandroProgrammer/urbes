<?php
$titlePage = "Panel de Administrador";
require_once("../components/sidebar.php");
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4 order-0">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-7">
                            <div class="card-body">
                                <h5 class="card-title text-primary">Bienvenido(a) Administrador!🎉</h5>
                                <p class="mb-4">
                                    En este Panel de Administrador puedes gestionar los diferentes datos de los
                                    empleados de tu empresa...
                                </p>
                                <a href="empleados_activos.php" class="btn btn-sm btn-outline-primary">Ver Empleados
                                    Activos</a>
                            </div>
                        </div>
                        <div class="col-sm-5 text-center text-sm-left">
                            <div class="card-body pb-0 px-0 px-md-4">
                                <img src="../../assets/images/man-with-laptop-light.png " height="140"
                                    alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png"
                                    data-app-light-img="illustrations/man-with-laptop-light.png" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Order Statistics -->
            <div class="col-md-6 col-lg-12 col-xl-6 order-0 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                        <div class="card-title mb-0">
                            <h5 class="m-0 me-2">Empleados</h5>
                            <small class="text-muted">Estadisticas Generales</small>
                        </div>
                    </div>
                    <div class="card-body mt-3">
                        <ul class="p-0 m-0">
                            <?php
                            countStatesUsers(
                                "conteoEmpleadosActivos",
                                "usuarios",
                                "Activos",
                                "Empleados Activos",
                                "1",
                                "success",
                                "3"
                            );
                            countStatesUsers(
                                "conteoEmpleadosBloqueados",
                                "usuarios",
                                "Bloqueados",
                                "Empleados Bloqueados",
                                "2",
                                "warning",
                                "3"
                            );
                            countStatesUsers(
                                "conteoEmpleadosEliminados",
                                "usuarios",
                                "Eliminados",
                                "Empleados Eliminados",
                                "3",
                                "danger",
                                "3"
                            );
                            ?>
                        </ul>
                        <div class="text-center"><a href="empleados_activos.php" class="btn btn-outline-primary">Ver
                                Empleados</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-12 col-xl-6 order-0 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                        <div class="card-title mb-0">
                            <h5 class="m-0 me-2">Socios</h5>
                            <small class="text-muted">Estadisticas Generales</small>
                        </div>
                    </div>
                    <div class="card-body mt-3">
                        <ul class="p-0 m-0">
                            <?php
                            countStatesUsers("conteoSociosActivos", "usuarios", "Activos", "Socios Activos", "1", "success", "2");
                            countStatesUsers("conteoSociosBloqueados", "usuarios", "Bloqueados", "Socios Bloqueados", "2", "warning", "2");
                            countStatesUsers("conteoSociosEliminados", "usuarios", "Eliminados", "Socios Eliminados", "3", "danger", "2");
                            ?>
                        </ul>
                        <div class="text-center"><a href="socios_activos.php" class="btn btn-outline-primary">Ver
                                Socios</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            // card para mostrar cantidad de estados
            // cardStadicts("conteoTiposUsuarios", "tipo_usuario", "tipo_usuario.php", "Tipos Usuarios");
            cardStadicts("conteo", "estados", "estados.php", "Estados");
            // card para mostrar cantidad de unidades

            ?>

        </div>


    </div>

    <?php
    require_once("../components/footer.php")
    ?>