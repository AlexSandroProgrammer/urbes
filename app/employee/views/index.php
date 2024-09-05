<?php
$titlePage = "Bienvenido Usuario";
require_once("../components/sidebar.php");
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 order-0">
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
            <!-- Grid Card -->
            <div class="row row-cols-1 row-cols-md-2 g-4 mb-5">
                <div class="col">
                    <div class="card h-100">
                        <img class="card-img-top" src="../assets/img/elements/2.jpg" alt="Card image cap" />
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">
                                This is a longer card with supporting text below as a natural lead-in to additional
                                content.
                                This content is a little bit longer.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <img class="card-img-top" src="../assets/img/elements/13.jpg" alt="Card image cap" />
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">
                                This is a longer card with supporting text below as a natural lead-in to additional
                                content.
                                This content is a little bit longer.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <img class="card-img-top" src="../assets/img/elements/4.jpg" alt="Card image cap" />
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">
                                This is a longer card with supporting text below as a natural lead-in to additional
                                content.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>


    </div>

    <?php
    require_once("../components/footer.php")
    ?>