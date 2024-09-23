<?php
$titlePage = "Editar socio";
require_once("../components/sidebar.php");
if (isNotEmpty([$_GET['id_partner-edit'], $_GET['ruta']])) {
    $id_partner = $_GET['id_partner-edit'];
    $ruta = $_GET['ruta'];
    $getFindByIdPartner = $connection->prepare("SELECT * FROM usuarios INNER JOIN tipo_usuario ON usuarios.id_tipo_usuario = tipo_usuario.id_tipo_usuario INNER JOIN estados ON usuarios.id_estado = estados.id_estado WHERE usuarios.documento = :documento");
    $getFindByIdPartner->bindParam(":documento", $id_partner);
    $getFindByIdPartner->execute();
    $partnerGetId = $getFindByIdPartner->fetch(PDO::FETCH_ASSOC);
    if ($partnerGetId) {
        // desencriptacion de contraseña 
        $password = bcrypt_password($partnerGetId['password']);
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Layout -->
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header justify-content-between align-items-center">
                        <h3 class="fw-bold py-2">Editar datos de
                            <?php echo $partnerGetId['nombres'] ?> - <?php echo $partnerGetId['apellidos'] ?>
                        </h3>
                        <h6 class="mb-0">Edita por favor los siguientes datos necesarios.</h6>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST" enctype="multipart/form-data" autocomplete="off"
                            name="formUpdatePartner">
                            <div class="row">
                                <input type="hidden" name="ruta" value="<?php echo $ruta ?>">
                                <h6 class="mb-3 fw-bold"> <i class="bx bx-user"></i> DATOS PERSONALES</h6>
                                <!-- tipo de documento -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label for="tipo_documento" class="form-label">Tipo de Documento</label>
                                    <div class="input-group input-group-merge">
                                        <span id="tipo_documento-2" class="input-group-text"><i
                                                class="fas fa-id-card"></i></span>
                                        <select class="form-select" autofocus name="tipo_documento" id="tipo_documento"
                                            required>
                                            <option value="<?php echo $partnerGetId['tipo_documento'] ?>">
                                                <?php echo $partnerGetId['tipo_documento'] ?></option>
                                            <option value="C.C.">C.C.</option>
                                            <option value="C.E.">C.E.</option>
                                            <option value="T.I.">T.I.</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- numero de documento -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="documento">Numero de Documento</label>
                                    <div class="input-group input-group-merge">
                                        <span id="documento-icon" class="input-group-text"><i
                                                class="fas fa-id-card"></i></span>
                                        <input type="text" class="form-control"
                                            onkeypress="return(multiplenumber(event));"
                                            value="<?php echo $partnerGetId['documento'] ?>" readonly minlength="10"
                                            maxlength="10" oninput="maxlengthNumber(this);" id="documento"
                                            name="documento" placeholder="Ingresar numero de documento"
                                            aria-describedby="documento-icon" />
                                    </div>
                                </div>
                                <!-- nombres -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="nombres">Nombres</label>
                                    <div class="input-group input-group-merge">
                                        <span id="nombres_span" class="input-group-text"><i
                                                class="fas fa-user"></i></span>
                                        <input type="text" required minlength="2"
                                            value="<?php echo $partnerGetId['nombres'] ?>" maxlength="100"
                                            class="form-control" name="nombres" id="nombres"
                                            placeholder="Ingresar nombres completos" />
                                    </div>
                                </div>
                                <!-- apellidos -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="apellidos">Apellidos</label>
                                    <div class="input-group input-group-merge">
                                        <span id="nombre_area-span" class="input-group-text"><i
                                                class="fas fa-user"></i></span>
                                        <input type="text" required minlength="2"
                                            value="<?php echo $partnerGetId['apellidos'] ?>" maxlength="100"
                                            class="form-control" name="apellidos" id="apellidos"
                                            placeholder="Ingresar apellidos completos" />
                                    </div>
                                </div>
                                <!-- numero de celular -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="celular">Numero de Celular</label>
                                    <div class="input-group input-group-merge">
                                        <span id="celular_span" class="input-group-text"><i
                                                class="fas fa-phone"></i></span>
                                        <input type="text" required onkeypress="return(multiplenumber(event));"
                                            minlength="10" maxlength="10" value="<?php echo $partnerGetId['celular'] ?>"
                                            class="form-control" name="celular" id="celular"
                                            placeholder="Ingresar numero de celular" />
                                    </div>
                                </div>
                                <div class="mb-3 col-12 col-lg-6">
                                    <label for="estado" class="form-label">Estado Usuario</label>
                                    <div class="input-group input-group-merge">
                                        <span id="estado-2" class="input-group-text"><i
                                                class="fas fa-check-circle"></i></span>
                                        <select class="form-select" name="estado" required>
                                            <option value="<?php echo $partnerGetId['id_estado'] ?>">
                                                <?php echo $partnerGetId['estado'] ?>
                                            </option>
                                            <?php
                                                    // CONSUMO DE DATOS DE LOS EMPLEADOS
                                                    $listEstados = $connection->prepare("SELECT * FROM estados WHERE id_estado = 1 || id_estado = 2 || id_estado = 3");
                                                    $listEstados->execute();
                                                    $estados = $listEstados->fetchAll(PDO::FETCH_ASSOC);
                                                    // Verificar si no hay datos
                                                    if (empty($estados)) {
                                                        echo "<option value=''>No hay datos...</option>";
                                                    } else {
                                                        // Iterar sobre los estados
                                                        foreach ($estados as $estado) {
                                                            echo "<option value='{$estado['id_estado']}'>{$estado['estado']}</option>";
                                                        }
                                                    }
                                                    ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- password -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="password">Contraseña</label>
                                    <div class="input-group input-group-merge">
                                        <span id="nombre_area-span" class="input-group-text"><i
                                                class="fas fa-lock"></i></span>
                                        <input type="text" required minlength="5" value="<?php echo $password ?>"
                                            maxlength="100" class="form-control" name="password" id="password"
                                            placeholder="Ingresar contraseña" />
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <a href="socios_activos.php" class="btn btn-danger">
                                        Cancelar
                                    </a>
                                    <input type="submit" class="btn btn-primary" value="Actualizar"></input>
                                    <input type="hidden" class="btn btn-info" value="formUpdatePartner"
                                        name="MM_formUpdatePartner"></input>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
        require_once("../components/footer.php");
    } else {
        showErrorOrSuccessAndRedirect("error", "Error de ruta", "Los datos del empleado no fueron encontrados", "empleados_activos.php");
    }
} else {
    showErrorOrSuccessAndRedirect("error", "Error de ruta", "Los datos del empleado no fueron encontrados", "empleados_activos.php");
}
    ?>