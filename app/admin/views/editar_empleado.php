<?php
$titlePage = "Editar Empleado";
require_once("../components/sidebar.php");
if (isNotEmpty([$_GET['id_employee-edit'], $_GET['ruta']])) {
    $id_employee = $_GET['id_employee-edit'];
    $ruta = $_GET['ruta'];
    $getFindByIdEmployee = $connection->prepare("SELECT * FROM usuarios INNER JOIN tipo_usuario ON usuarios.id_tipo_usuario = tipo_usuario.id_tipo_usuario INNER JOIN estados ON usuarios.id_estado = estados.id_estado INNER JOIN ciudades ON usuarios.id_ciudad = ciudades.id_ciudad WHERE usuarios.documento = :documento");
    $getFindByIdEmployee->bindParam(":documento", $id_employee);
    $getFindByIdEmployee->execute();
    $employeeGetId = $getFindByIdEmployee->fetch(PDO::FETCH_ASSOC);
    if ($employeeGetId) {
        // desencriptacion de contraseña 
        $password = bcrypt_password($employeeGetId['password']);
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
                            <?php echo $employeeGetId['nombres'] ?> - <?php echo $employeeGetId['apellidos'] ?>
                        </h3>
                        <h6 class="mb-0">Edita por favor los siguientes datos necesarios.</h6>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST" enctype="multipart/form-data" autocomplete="off"
                            name="formUpdateEmployee">
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
                                            <option value="<?php echo $employeeGetId['tipo_documento'] ?>">
                                                <?php echo $employeeGetId['tipo_documento'] ?></option>
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
                                            value="<?php echo $employeeGetId['documento'] ?>" readonly minlength="10"
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
                                            value="<?php echo $employeeGetId['nombres'] ?>" maxlength="100"
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
                                            value="<?php echo $employeeGetId['apellidos'] ?>" maxlength="100"
                                            class="form-control" name="apellidos" id="apellidos"
                                            placeholder="Ingresar apellidos completos" />
                                    </div>
                                </div>
                                <!-- numero de celular -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="celular">Numero de Celular</label>
                                    <div class="input-group input-group-merge">
                                        <span id="celular_span" class="input-group-text"><i
                                                class="fas fa-mobile-alt"></i></span>
                                        <input type="text" required onkeypress="return(multiplenumber(event));"
                                            minlength="10" maxlength="10"
                                            value="<?php echo $employeeGetId['celular'] ?>" class="form-control"
                                            name="celular" id="celular" placeholder="Ingresar numero de celular" />
                                    </div>
                                </div>
                                <div class="mb-3 col-12 col-lg-6">
                                    <label for="estado" class="form-label">Estado Usuario</label>
                                    <div class="input-group input-group-merge">
                                        <span id="estado-2" class="input-group-text"><i
                                                class="fas fa-user-check"></i></span>
                                        <select class="form-select" name="estado" required>
                                            <option value="<?php echo $employeeGetId['id_estado'] ?>">
                                                <?php echo $employeeGetId['estado'] ?>
                                            </option>
                                            <?php
                                                    // CONSUMO DE DATOS DE LOS EMPLEADOS
                                                    $listEstados = $connection->prepare("SELECT * FROM estados WHERE id_estado = 1 ||id_estado = 2 || id_estado = 3  ");
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
                                <!-- nombre de la rh -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="rh">RH</label>
                                    <div class="input-group input-group-merge">
                                        <span id="rh_span" class="input-group-text"><i class="fas fa-tint"></i></span>
                                        <input type="text" required minlength="2" maxlength="100"
                                            value="<?php echo $employeeGetId['rh']  ?>" class="form-control" name="rh"
                                            id="rh" placeholder="Ingresar RH del empleado" />
                                    </div>
                                </div>
                                <!-- ciudad -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label for="ciudad" class="form-label">Ciudad</label>
                                    <div class="input-group input-group-merge">
                                        <span id="ciudad-2" class="input-group-text"><i class="fas fa-city"></i></span>
                                        <select class="form-select" name="ciudad" required>
                                            <option value="<?php echo $employeeGetId['id_ciudad'] ?>">
                                                <?php echo $employeeGetId['ciudad'] ?></option>
                                            <?php
                                                    // CONSUMO DE DATOS DE LOS PROCESOS
                                                    $ciudades_query = $connection->prepare("SELECT * FROM ciudades");
                                                    $ciudades_query->execute();
                                                    $ciudades = $ciudades_query->fetchAll(PDO::FETCH_ASSOC);
                                                    // Verificar si no hay datos
                                                    if (empty($ciudades)) {
                                                        echo "<option value=''>No hay datos...</option>";
                                                    } else {
                                                        // Iterar sobre los ciudads
                                                        foreach ($ciudades as $ciudad) {
                                                            echo "<option value='{$ciudad['id_ciudad']}'>{$ciudad['ciudad']}</option>";
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
                                        <input type="text" required minlength="5" value="<?php echo $password  ?>"
                                            maxlength="100" class="form-control" name="password" id="password"
                                            placeholder="Ingresar contraseña" />
                                    </div>
                                </div>
                                <h6 class="py-3 fw-bold"> <i class="bx bx-user"></i> DATOS DEL FAMILIAR</h6>
                                <!-- nombre_familiar -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="nombre_familiar">Nombre Familiar</label>
                                    <div class="input-group input-group-merge">
                                        <span id="nombre_familiar_span" class="input-group-text"><i
                                                class="fas fa-users"></i></span>
                                        <input type="text" required minlength="2"
                                            value="<?php echo $employeeGetId['nombre_familiar'] ?>" maxlength="100"
                                            class="form-control" name="nombre_familiar" id="nombre_familiar"
                                            placeholder="Ingresar nombre del familiar" />
                                    </div>
                                </div>
                                <!-- celular_familiar -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="celular_familiar">Celular Familiar</label>
                                    <div class="input-group input-group-merge">
                                        <span id="celular_familiar_span" class="input-group-text"><i
                                                class="fas fa-phone"></i></span>
                                        <input type="text" required minlength="2"
                                            value="<?php echo $employeeGetId['celular_familiar'] ?>"
                                            onkeypress="return(multiplenumber(event));" minlength="10" maxlength="10"
                                            class="form-control" name="celular_familiar" id="celular_familiar"
                                            placeholder="Ingresar celular del familiar" />
                                    </div>
                                </div>
                                <!-- parentezco_familiar -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="parentezco_familiar">Parentezco Familiar</label>
                                    <div class="input-group input-group-merge">
                                        <span id="parentezco_familiar_span" class="input-group-text"><i
                                                class="fas fa-users"></i></span>
                                        <input type="text" required minlength="2"
                                            value="<?php echo $employeeGetId['parentezco_familiar'] ?>" maxlength="100"
                                            class="form-control" name="parentezco_familiar" id="parentezco_familiar"
                                            placeholder="Ingresar celular del familiar" />
                                    </div>
                                </div>
                                <h6 class="py-3 fw-bold"> <i class="bx bx-user"></i> DATOS LABORALES</h6>
                                <!-- eps -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="eps">EPS</label>
                                    <div class="input-group input-group-merge">
                                        <span id="eps_span" class="input-group-text"><i
                                                class="fas fa-heartbeat"></i></span>
                                        <input type="text" required minlength="2"
                                            value="<?php echo $employeeGetId['eps'] ?>" maxlength="100"
                                            class="form-control" name="eps" id="eps"
                                            placeholder="Ingresar nombre de la eps" />
                                    </div>
                                </div>
                                <!-- arl -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="arl">ARL</label>
                                    <div class="input-group input-group-merge">
                                        <span id="arl_span" class="input-group-text"><i
                                                class="fas fa-shield-alt"></i></span>
                                        <input type="text" required minlength="2"
                                            value="<?php echo $employeeGetId['arl'] ?>" maxlength="100"
                                            class="form-control" name="arl" id="arl"
                                            placeholder="Ingresar nombre de la arl" />
                                    </div>
                                </div>
                                <!-- fecha de inicio-->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="fecha_inicio">Fecha Inicio Contrato</label>
                                    <div class="input-group input-group-merge">
                                        <span id="fecha_inicio_span" class="input-group-text"><i
                                                class="fas fa-calendar"></i></span>
                                        <input type="date" required value="<?php echo $employeeGetId['fecha_inicio'] ?>"
                                            class="form-control" name="fecha_inicio" id="fecha_inicio" />
                                    </div>
                                </div>
                                <!-- fecha de fin-->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="fecha_fin">Fecha Fin Contrato</label>
                                    <div class="input-group input-group-merge">
                                        <span id="fecha_fin_span" class="input-group-text"><i
                                                class="fas fa-calendar"></i></span>
                                        <input type="date" required value="<?php echo $employeeGetId['fecha_fin'] ?>"
                                            class="form-control" name="fecha_fin" id="fecha_fin" />
                                    </div>
                                </div>
                                <!-- tipo rol -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label for="estado" class="form-label">Tipo de rol</label>
                                    <div class="input-group input-group-merge">
                                        <span id="estado-2" class="input-group-text"><i
                                                class="fas fa-user-tag"></i></span>
                                        <select class="form-select" name="tipo_rol" required>
                                            <option value="<?php echo $employeeGetId['id_tipo_usuario'] ?>">
                                                <?php echo $employeeGetId['tipo_usuario'] ?></option>
                                            <?php
                                                    // CONSUMO DE DATOS DE LOS PROCESOS
                                                    $types_query = $connection->prepare("SELECT * FROM tipo_usuario WHERE id_tipo_usuario = 3 || id_tipo_usuario = 4");
                                                    $types_query->execute();
                                                    $types = $types_query->fetchAll(PDO::FETCH_ASSOC);
                                                    // Verificar si no hay datos
                                                    if (empty($types)) {
                                                        echo "<option value=''>No hay datos...</option>";
                                                    } else {
                                                        // Iterar sobre los types
                                                        foreach ($types as $type) {
                                                            echo "<option value='{$type['id_tipo_usuario']}'>{$type['tipo_usuario']}</option>";
                                                        }
                                                    }
                                                    ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <a href="empleados_activos.php" class="btn btn-danger">
                                        Cancelar
                                    </a>
                                    <input type="submit" class="btn btn-primary" value="Actualizar"></input>
                                    <input type="hidden" class="btn btn-info" value="formUpdateEmployee"
                                        name="MM_formUpdateEmployee"></input>
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