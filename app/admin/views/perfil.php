<?php
$titlePage = "Mis Datos";
require_once("../components/sidebar.php");

if ($documentoSession) {
    $password_global = bcrypt_password($documentoSession['password']);
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-pills flex-column flex-md-row mb-3">
                        <li class="nav-item">
                            <a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i>
                                Cuenta</a>
                        </li>
                    </ul>
                    <div class="card mb-4">
                        <h4 class=" card-header fw-bold"><strong>Editar datos de mi cuenta</h4>
                        <hr class="my-0" />
                        <div class="card-body">
                            <form id="formAccountSettings" action="" method="POST" autocomplete="off"
                                name="formUpdateMyDates">
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label for="documento" class="form-label">Numero de Documento</label>
                                        <div class="input-group input-group-merge">
                                            <span id="documento-2" class="input-group-text"><i
                                                    class="fas fa-id-card"></i></span>
                                            <input class="form-control" type="number" min="6" max="10"
                                                placeholder="Ingresa tus nombres" readonly minlength="6" maxlength="10"
                                                id="documento" name="documento"
                                                value="<?php echo $documentoSession['documento'] ?>" />
                                        </div>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="names" class="form-label">Nombres</label>
                                        <div class="input-group input-group-merge">
                                            <span id="names-2" class="input-group-text"><i
                                                    class="fas fa-user"></i></span>
                                            <input class="form-control" type="text" min="2" max="200"
                                                placeholder="Ingresa tus nombres" minlength="2" maxlength="200"
                                                id="names" name="names"
                                                value="<?php echo $documentoSession['nombres'] ?>" autofocus />
                                        </div>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="surnames" class="form-label">Apellidos</label>
                                        <div class="input-group input-group-merge">
                                            <span id="surnames-2" class="input-group-text"><i
                                                    class="fas fa-user"></i></span>
                                            <input class="form-control" id="surnames" type="text"
                                                placeholder="Ingresa tus apellidos" min="2" max="200" minlength="2"
                                                maxlength="200" name="surnames"
                                                value="<?php echo $documentoSession['apellidos'] ?>" autofocus />
                                        </div>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="celular" class="form-label">Celular</label>
                                        <div class="input-group input-group-merge">
                                            <span id="celular-2" class="input-group-text"><i
                                                    class="fas fa-phone"></i></span>
                                            <input class="form-control" type="text" minlength="10" maxlength="10"
                                                onkeypress="return(multiplenumber(event));"
                                                placeholder=" Ingresa tu numero de celular" id="celular" name="celular"
                                                value="<?php echo $documentoSession['celular'] ?>" autofocus />
                                        </div>
                                    </div>
                                    <!-- tipo de documento -->
                                    <div class="mb-3 col-md-6">
                                        <label for="tipo_documento" class="form-label">Tipo de Documento</label>
                                        <div class="input-group input-group-merge">
                                            <span id="tipo_documento-2" class="input-group-text"><i
                                                    class="fas fa-id-card"></i></span>
                                            <select class="form-select" autofocus name="tipo_documento"
                                                id="tipo_documento" required>
                                                <option value="<?php echo $documentoSession['tipo_documento'] ?>">
                                                    <?php echo $documentoSession['tipo_documento'] ?></option>
                                                <option value="">Seleccionar tipo de documento...</option>
                                                <option value="C.C.">C.C.</option>
                                                <option value="C.E.">C.E.</option>
                                                <option value="T.I.">T.I.</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="password" class="form-label">Contraseña</label>
                                        <div class="input-group input-group-merge">
                                            <span id="password-2" class="input-group-text"><i
                                                    class="fas fa-lock"></i></span>
                                            <input class="form-control" type="text" min="5" max="50"
                                                placeholder="Ingresa tu nueva contraseña"
                                                value="<?php echo $password_global ?>" minlength="5" maxlength="50"
                                                id="firstName" name="password" />
                                        </div>
                                    </div>
                                </div>
                                <div class="m-2">
                                    <input type="submit" class="btn btn-primary" value="Actualizar"></input>
                                    <input type="hidden" class="btn btn-info" value="formUpdateMyDates"
                                        name="MM_formUpdateMyDates"></input>
                                    <a href="index.php" class="btn btn-danger">Cancelar</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Content wrapper -->
</div>

</div>
<!-- / Layout page -->
</div>


<?php

} else {
    showErrorOrSuccessAndRedirect("Error", "¡Oopsss!", "Error al momento de obtener los datos", "index.php");
}
require_once("../components/footer.php")
?>