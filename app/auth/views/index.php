<?php
require_once("../components/header.php");
// importacion de controladores
require_once("../controllers/AuthController.php");
?>

<!-- Content -->
<div class="authentication-wrapper authentication-basic px-3">
    <div class="authentication-inner">
        <!-- Login Form -->
        <div class="card">
            <div class="card-body">
                <!-- Logo -->
                <div class="justify-content-center text-center">
                    <span class="demo">
                        <img src="../../assets/images/urbes.svg" width="100" height="100" alt="">
                    </span>
                </div>
                <!-- titulo del formulario -->
                <div class="text-center">
                    <h4 class="mb-2">Bienvenido Usuario! 👋</h4>
                    <p class="mb-4">Ingresa por favor tus credenciales.</p>
                </div>
                <form id="formAuthentication" class="mb-3" autocomplete="off" method="POST" action="">
                    <div class="mb-3">
                        <label for="documento" class="form-label">Documento</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-id-card"></i></span>
                            <input type="number" minlength="6" maxlength="10" oninput="maxlengthNumber(this);"
                                onkeypress="return(multiplenumber(event));" class="form-control" required id="documento"
                                name="documento" placeholder="Ingresa tu numero de documento" autofocus />
                        </div>
                    </div>
                    <div class="mb-3 form-password-toggle">
                        <div class="d-flex justify-content-between">
                            <label class="form-label" required for="password">Contraseña</label>
                        </div>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-lock-open-alt"></i></span>
                            <input type="password" minlength="3" required maxlength="30" id="password"
                                class="form-control" name="password" placeholder="Ingresa tu contraseña"
                                aria-describedby="password" />
                            <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <input class="btn btn-primary d-grid w-100" type="submit" name="iniciarSesion"
                            value="Iniciar Sesion" />
                    </div>
                </form>
            </div>
        </div>
        <!-- /Register -->
    </div>
</div>

<?php
require_once("../components/footer.php")
?>