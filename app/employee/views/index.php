<?php
$titlePage = "Bienvenido Usuario";
require_once("../components/navbar.php");
if ($documentoSession) {
    $documento = encrypt_password($documentoSession['documento']);
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

                            </div>
                        </div>
                        <div class="col-sm-5 text-center text-sm-left">
                            <div class="py-3">
                                <img src="../../assets/images/employee.webp " height="160" alt="View Badge User" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="card h-100 text-center align-items-center">
                    <div class="pt-2">
                        <img width="300" height="200" src="../../assets/images/vehiculo_compactador.png"
                            alt="Card image cap" />
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">1. Formulario Vehiculo Compactador</h5>
                        <p class="card-text">
                            Presiona clic para ingresar en el formulario
                        </p>
                        <a href="vehiculo_compactador.php" class="btn btn-primary"><i class='bx bx-right-arrow-alt'></i>
                            Ingresar</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="card h-100 text-center align-items-center">
                    <div class="pt-2">
                        <img width="300" height="200" src="../../assets/images/mecanica.jpg" alt="Card image cap" />
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">2. Formulario Mecanica Vehiculo Compactador</h5>
                        <p class="card-text">
                            Presiona clic para ingresar en el formulario
                        </p>
                        <a href="mecanica.php" class="btn btn-primary"><i
                                class='bx bx-right-arrow-alt'></i>
                            Ingresar</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="card h-100 text-center align-items-center">
                    <div class="pt-2">
                        <img width="300" height="200" src="../../assets/images/vehiculo_compactador.png"
                            alt="Card image cap" />
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">3. Formulario Carro de Barrido</h5>
                        <p class="card-text">
                            Presiona click para ingresar en el formulario
                        </p>
                        <a href="carro_barrido.php" class="btn btn-primary"><i class='bx bx-right-arrow-alt'></i>
                            Ingresar</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="card h-100 text-center">
                    <div class="pt-2">
                        <img width="300" height="200" src="../../assets/images/vehiculo_compactador.png"
                            alt="Card image cap" />
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">4. Areas Publicas</h5>
                        <p class="card-text">
                            Presiona click para ingresar en el formulario
                        </p>
                        <div class="row g-2">
                            <div class="col-12">
                                <a href="lavado.php" class="btn btn-primary w-50">
                                    <i class='bx bx-right-arrow-alt'></i> Lavado A. Publicas
                                </a>
                            </div>
                            <div class="col-12">
                                <a href="cesped.php" class="btn btn-success w-50">
                                    <i class='bx bx-right-arrow-alt'></i> Poda De Cesped
                                </a>
                            </div>
                            <div class="col-12">
                                <a href="poda.php" class="btn btn-info w-50">
                                    <i class='bx bx-right-arrow-alt'></i> Poda De Arboles
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php
        require_once("../components/footer.php")
        ?>
    <?php
} else {
    header("Location:./index.php");
    exit;
}
    ?>