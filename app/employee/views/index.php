<?php
$titlePage = "Bienvenido Usuario";
require_once("../components/navbar.php");
require_once("../auto/automations.php");
if ($documentoSession) {
    $documento = $documentoSession['documento'];
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-4">
            <div class="col-lg-12 order-0">
                <div class="card">
                    <div class="d-flex align-items-center justify-content-center row">
                        <div class="col-sm-7">
                            <div class="card-body">
                                <h5 class="card-title text-primary">Bienvenido(a) Usuario
                                    <?= $documentoSession['nombres'] ?> -
                                    <?= $documentoSession['apellidos'] ?> !🎉</h5>
                                <p class="mb-4">
                                    En este perfil de empleado debes gestionar diariamente los siguientes formularios...
                                </p>
                                <a href="pendientes.php" class="btn btn-primary">Ver Formularios Pendientes <i
                                        class='bx bx-right-arrow-alt'></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-5 text-center text-sm-left">
                            <div class="py-3">
                                <img src="../../assets/images/employee.webp" height="160" alt="View Badge User" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 1 -->
            <div class="col-12 col-md-6">
                <div class="card h-100 text-center align-items-center shadow-sm rounded">
                    <div class="pt-3">
                        <img width="400" height="300" src="../../assets/images/vehiculo_compatador.jpg"
                            alt="Vehículo Compactador" class="rounded-top img-fluid" />
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-primary">1. Formulario Vehículo Compactador</h5>
                        <p class="card-text">Presiona clic para ingresar en el formulario</p>
                        <div class="d-flex flex-column flex-lg-row gap-2">
                            <a href="vehiculo_compactador.php" class="btn btn-primary">
                                Recolección <i class='bx bx-right-arrow-alt'></i>
                            </a>
                            <a href="recoleccion_relleno.php" class="btn btn-primary">
                                Disposición relleno <i class='bx bx-right-arrow-alt'></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col-12 col-md-6">
                <div class="card h-100 text-center align-items-center shadow-sm rounded">
                    <div class="pt-3">
                        <img width="400" height="250" src="../../assets/images/aforos.jpg" alt="aforos"
                            class="rounded-top img-fluid" />
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-success">2. Aforos</h5>
                        <p class="card-text">Presiona clic para ingresar en el formulario</p>
                        <a href="aforos.php" class="btn btn-success">
                            Ingresar <i class='bx bx-right-arrow-alt'></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="col-12 col-md-6">
                <div class="card h-100 text-center align-items-center shadow-sm rounded">
                    <div class="pt-3">
                        <img width="400" height="300" src="../../assets/images/mecanica.jpeg"
                            alt="Mecánica Vehículo Compactador" class="rounded-top img-fluid" />
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-info">3. Formulario Mecánica Vehículo Compactador</h5>
                        <p class="card-text">Presiona clic para ingresar en el formulario</p>
                        <a href="mecanica.php" class="btn btn-info">
                            Ingresar <i class='bx bx-right-arrow-alt'></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="col-12 col-md-6">
                <div class="card h-100 text-center align-items-center shadow-sm rounded">
                    <div class="pt-3">
                        <img width="400" height="300" src="../../assets/images/carro_barrido.jpg" alt="Carro de Barrido"
                            class="rounded-top img-fluid" />
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-warning">4. Formulario Carro de Barrido</h5>
                        <p class="card-text">Presiona clic para ingresar en el formulario</p>
                        <a href="carro_barrido.php" class="btn btn-warning">
                            Ingresar <i class='bx bx-right-arrow-alt'></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="col-12 col-md-6">
                <div class="card h-100 text-center shadow-sm rounded">
                    <div class="pt-3">
                        <img width="400" height="300" src="../../assets/images/lavado.jpg" alt="Lavado Áreas Públicas"
                            class="rounded-top img-fluid" />
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-danger">5. Áreas Públicas</h5>
                        <p class="card-text">Presiona clic para ingresar en el formulario</p>
                        <div class="row g-2">
                            <div class="col-12">
                                <a href="lavado.php" class="btn btn-primary w-50">
                                    Lavado A. Públicas <i class='bx bx-right-arrow-alt'></i>
                                </a>
                            </div>
                            <div class="col-12">
                                <a href="cesped.php" class="btn btn-success w-50">
                                    Poda De Césped <i class='bx bx-right-arrow-alt'></i>
                                </a>
                            </div>
                            <div class="col-12">
                                <a href="poda.php" class="btn btn-info w-50">
                                    Poda De Árboles <i class='bx bx-right-arrow-alt'></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php
        require_once("../components/footer.php");
        ?>
    <?php
} else {
    header("Location:index.php?logout");
    exit;
}
    ?>