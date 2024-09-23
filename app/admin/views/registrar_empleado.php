<?php
$titlePage = "Registro de Empleado";
require_once("../components/sidebar.php");
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Layout -->
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header justify-content-between align-items-center text-center">
                        <h3 class="fw-bold pb-1">Registro de Empleado</h3>
                        <h6 class="mb-0">Ingresa por favor los siguientes datos.</h6>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST" enctype="multipart/form-data" autocomplete="off"
                            name="formRegisterEmployee">
                            <div class="row">
                                <h6 class="mb-3 fw-bold"> <i class="bx bx-user"></i> DATOS PERSONALES</h6>
                                <!-- tipo de documento -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label for="tipo_documento" class="form-label">Tipo de Documento</label>
                                    <div class="input-group input-group-merge">
                                        <span id="tipo_documento-2" class="input-group-text"><i
                                                class="fas fa-id-card"></i></span>
                                        <select class="form-select" autofocus name="tipo_documento" id="tipo_documento"
                                            required>
                                            <option value="">Seleccionar tipo de documento...</option>
                                            <option value="C.C.">C.C.</option>
                                            <option value="C.E.">C.E.</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- numero de documento -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="documento">Numero de Documento</label>
                                    <div class="input-group input-group-merge">
                                        <span id="documento-icon" class="input-group-text"><i
                                                class="fas fa-address-card"></i></span>
                                        <input type="text" minlength="6" maxlength="10" oninput="maxlengthNumber(this);"
                                            onkeypress="return(multiplenumber(event));" class="form-control" required
                                            id="documento" name="documento" placeholder="Ingresa tu numero de documento"
                                            autofocus />
                                    </div>
                                </div>
                                <!-- nombres -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="nombres">Nombres</label>
                                    <div class="input-group input-group-merge">
                                        <span id="nombres_span" class="input-group-text"><i
                                                class="fas fa-user"></i></span>
                                        <input type="text" required minlength="2" maxlength="100" class="form-control"
                                            name="nombres" id="nombres" placeholder="Ingresar nombres completos" />
                                    </div>
                                </div>
                                <!-- apellidos -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="apellidos">Apellidos</label>
                                    <div class="input-group input-group-merge">
                                        <span id="nombre_area-span" class="input-group-text"><i
                                                class="fas fa-user"></i></span>
                                        <input type="text" required minlength="2" maxlength="100" class="form-control"
                                            name="apellidos" id="apellidos"
                                            placeholder="Ingresar apellidos completos" />
                                    </div>
                                </div>
                                <!-- numero de celular -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="celular">Numero de Celular</label>
                                    <div class="input-group input-group-merge">
                                        <span id="celular_span" class="input-group-text"><i
                                                class="fas fa-mobile-alt"></i></span>
                                        <input type="text" required type="text" minlength="10" maxlength="10"
                                            onkeypress="return(multiplenumber(event));" class="form-control"
                                            name="celular" id="celular" placeholder="Ingresar numero de celular" />
                                    </div>
                                </div>
                                <!-- nombre de la rh -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="rh">RH</label>
                                    <div class="input-group input-group-merge">
                                        <span id="rh_span" class="input-group-text"><i class="fas fa-tint"></i></span>
                                        <input type="text" required minlength="2" maxlength="100" class="form-control"
                                            name="rh" id="rh" placeholder="Ingresar RH del empleado" />
                                    </div>
                                </div>
                                <!-- estado -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label for="estado" class="form-label">Estado Inicial</label>
                                    <div class="input-group input-group-merge">
                                        <span id="estado-2" class="input-group-text"><i
                                                class="fas fa-toggle-on"></i></span>
                                        <select class="form-select" name="estado" required>
                                            <option value="">Seleccionar Estado...</option>
                                            <?php
                                            // CONSUMO DE DATOS DE LOS PROCESOS
                                            $estados_query = $connection->prepare("SELECT * FROM estados WHERE id_estado != 4 AND id_estado != 5");
                                            $estados_query->execute();
                                            $estados_se = $estados_query->fetchAll(PDO::FETCH_ASSOC);
                                            // Verificar si no hay datos
                                            if (empty($estados_se)) {
                                                echo "<option value=''>No hay datos...</option>";
                                            } else {
                                                // Iterar sobre los estados
                                                foreach ($estados_se as $estado_se) {
                                                    echo "<option value='{$estado_se['id_estado']}'>{$estado_se['estado']}</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- ciudad -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label for="ciudad" class="form-label">Ciudad</label>
                                    <div class="input-group input-group-merge">
                                        <span id="ciudad-2" class="input-group-text"><i class="fas fa-city"></i></span>
                                        <select class="form-select" name="ciudad" required>
                                            <option value="">Seleccionar Ciudad...</option>
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
                                                class="fas fa-key"></i></span>
                                        <input type="password" required minlength="5" maxlength="30"
                                            class="form-control" name="password" id="password"
                                            placeholder="Ingresar por favor la contraseña" />

                                    </div>
                                </div>
                                <h6 class="py-3 fw-bold"> <i class="bx bx-group"></i> DATOS DEL FAMILIAR</h6>

                                <!-- numero de celular -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="celular_familiar">Numero de Celular Familiar</label>
                                    <div class="input-group input-group-merge">
                                        <span id="celular_familiar_span" class="input-group-text"><i
                                                class="fas fa-mobile-alt "></i></span>
                                        <input type="text" required type="text"
                                            onkeypress="return(multiplenumber(event));" minlength="10" maxlength="10"
                                            class="form-control" name="celular_familiar" id="celular_familiar"
                                            placeholder="Ingresar numero de celular del acudiente" />
                                    </div>
                                </div>
                                <!-- nombre de familiar -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="nombre_familiar">Nombre Familiar</label>
                                    <div class="input-group input-group-merge">
                                        <span id="nombresfamiliar_span" class="input-group-text"><i
                                                class="fas fa-user-friends"></i></span>
                                        <input type="text" required minlength="2" maxlength="100" class="form-control"
                                            name="nombre_familiar" id="nombre_familiar"
                                            placeholder="Ingresar nombres completos" />
                                    </div>
                                </div>
                                <!-- parentezco de familiar -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="parentezco_familiar">Parentezco Familiar</label>
                                    <div class="input-group input-group-merge">
                                        <span id="parentezco_span" class="input-group-text"><i
                                                class="fas fa-user-friends"></i></span>
                                        <input type="text" required minlength="2" maxlength="100" class="form-control"
                                            name="parentezco_familiar" id="parentezco_familiar"
                                            placeholder="Ingresar parentezco del familiar" />
                                    </div>
                                </div>
                                <h6 class="py-3 fw-bold"> <i class="bx bx-user"></i> DATOS LABORALES</h6>
                                <!-- fecha de inicio-->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="fecha_inicio">Fecha Inicio Contrato</label>
                                    <div class="input-group input-group-merge">
                                        <span id="fecha_inicio_span" class="input-group-text"><i
                                                class="fas fa-calendar"></i></span>
                                        <input type="date" required class="form-control" name="fecha_inicio"
                                            id="fecha_inicio" />
                                    </div>
                                </div>
                                <!-- fecha de inicio-->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="fecha_fin">Fecha Fin Contrato</label>
                                    <div class="input-group input-group-merge">
                                        <span id="fecha_fin_span" class="input-group-text"><i
                                                class="fas fa-calendar"></i></span>
                                        <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" />
                                    </div>
                                </div>
                                <!-- nombre de la eps -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="eps">Nombre Eps</label>
                                    <div class="input-group input-group-merge">
                                        <span id="eps_span" class="input-group-text"><i
                                                class="fas fa-plus-square"></i></span>
                                        <input type="text" required minlength="2" maxlength="100" class="form-control"
                                            name="eps" id="eps" placeholder="Ingresar nombre de la EPS" />
                                    </div>
                                </div>
                                <!-- nombre de la arl -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="arl">Nombre Arl</label>
                                    <div class="input-group input-group-merge">
                                        <span id="arl_span" class="input-group-text"><i
                                                class="fas fa-shield-alt"></i></span>
                                        <input type="text" required minlength="2" maxlength="100" class="form-control"
                                            name="arl" id="arl" placeholder="Ingresar nombre de la ARL" />
                                    </div>
                                </div>
                                <!-- tipo rol -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label for="estado" class="form-label">Tipo de rol</label>
                                    <div class="input-group input-group-merge">
                                        <span id="estado-2" class="input-group-text"><i
                                                class="fas fa-user-shield"></i></span>
                                        <select class="form-select" name="tipo_rol" required>
                                            <option value="">Seleccionar Tipo de Usuario...</option>
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
                                    <input type="submit" class="btn btn-primary" value="Registrar"></input>
                                    <input type="hidden" class="btn btn-info" value="formRegisterEmployee"
                                        name="MM_formRegisterEmployee"></input>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    require_once("../components/footer.php")
    ?>