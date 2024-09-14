<?php
$titlePage = "Registro Vehiculo Compactador";
require_once("../components/navbar.php");
// asignamos la query a una variable
$documento = $_SESSION['documento'];
// Preparamos la consulta para buscar el usuario
$queryUser = $connection->prepare("SELECT * FROM usuarios WHERE documento = :documento");
$queryUser->bindParam(":documento", $documento);
$queryUser->execute();
$user = $queryUser->fetch(PDO::FETCH_ASSOC);
// nombre completo
$nombre_completo = $user['nombres'] . ' ' . $user['apellidos'];
// fecha inicio
$fecha_inicio = date('Y-m-d');
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Layout -->
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header mt-3 justify-content-between align-items-center text-center">
                        <h3 class="fw-bold">REGISTRO DE VEHICULO COMPACTADOR</h3>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST" enctype="multipart/form-data" autocomplete="off"
                            name="formRegisterVehicleCompacter" onsubmit="return validarEmpleados()">
                            <div class="row">
                                <h5 class="mb-5 text-center"> <i class="bx bx-user"></i> Bienvenido(a)
                                    <?= $nombre_completo ?> al registro del
                                    formulario, te
                                    invitamos a rellenar la siguiente informacion </h5>
                                <!-- fecha_inicio -->
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label class="form-label" for="fecha_inicio">Fecha Inicio</label>
                                    <div class="input-group input-group-merge">
                                        <span id="nombre_area-span" class="input-group-text"><i
                                                class="fas fa-truck"></i></span>
                                        <input type="date" required readonly class="form-control"
                                            value="<?= $fecha_inicio ?>" name="fecha_inicio" id="fecha_inicio" />
                                    </div>
                                </div>
                                <!-- equipo de transporte -->
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label for="estado" class="form-label">Equipo de Transporte</label>
                                    <div class="input-group input-group-merge">
                                        <span id="estado-2" class="input-group-text"><i class="fas fa-truck"></i></span>
                                        <select class="form-select" name="vehiculo" autofocus required>
                                            <option value="">Seleccionar Equipo de Transporte...</option>
                                            <?php
                                            // CONSUMO DE DATOS DE LOS vehiculos
                                            $driversGet = $connection->prepare("SELECT * FROM vehiculos");
                                            $driversGet->execute();
                                            $equipos = $driversGet->fetchAll(PDO::FETCH_ASSOC);
                                            // Verificar si no hay datos
                                            if (empty($equipos)) {
                                                echo "<option value=''>No hay datos...</option>";
                                            } else {
                                                // Iterar sobre los vehiculos
                                                foreach ($equipos as $equipo) {
                                                    echo "<option value='{$equipo['placa']}'>{$equipo['vehiculo']} - {$equipo['placa']}</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <?php
                                if ($user['id_tipo_usuario'] == 4) {
                                ?>
                                <!-- numero de documento -->
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label class="form-label" for="documento">CONDUCTOR ENCARGADO DE REGISTRO</label>
                                    <div class="input-group input-group-merge">
                                        <span id="documento-icon" class="input-group-text"><i
                                                class="fas fa-truck"></i></span>
                                        <input type="text" minlength="6" maxlength="10" readonly
                                            value="<?= $documento ?>" class="form-control" required id="documento"
                                            name="documento" placeholder="Ingresa tu numero de documento" />
                                    </div>
                                </div>
                                <?php
                                } else {
                                ?>
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label for="estado" class="form-label">Conductor Asignado</label>
                                    <div class="input-group input-group-merge">
                                        <span id="estado-2" class="input-group-text"><i class="fas fa-truck"></i></span>
                                        <select class="form-select" name="conductor" required>
                                            <option value="">Seleccionar Conductor...</option>
                                            <?php
                                                // tipo de usuario conductor
                                                $confirmacion = 4;
                                                // CONSUMO DE DATOS DE LOS PROCESOS
                                                $driversGet = $connection->prepare("SELECT * FROM usuarios WHERE id_tipo_usuario = :confirmacion");
                                                $driversGet->bindParam(':confirmacion', $confirmacion);
                                                $driversGet->execute();
                                                $drivers = $driversGet->fetchAll(PDO::FETCH_ASSOC);
                                                // Verificar si no hay datos
                                                if (empty($drivers)) {
                                                    echo "<option value=''>No hay datos...</option>";
                                                } else {
                                                    // Iterar sobre los documentos de los conductores
                                                    foreach ($drivers as $driver) {
                                                        echo "<option value='{$driver['documento']}'>{$driver['documento']} - {$driver['nombres']} {$driver['apellidos']}</option>";
                                                    }
                                                }
                                                ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- numero de documento -->
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label class="form-label" for="documento">PERSONA ENCARGADA DE REGISTRO</label>
                                    <div class="input-group input-group-merge">
                                        <span id="documento-icon" class="input-group-text"><i
                                                class="fas fa-truck"></i></span>
                                        <input type="text" minlength="6" maxlength="10" readonly
                                            value="<?= $documento ?>" class="form-control" required id="documento"
                                            name="documento" placeholder="Ingresa tu numero de documento" />
                                    </div>
                                </div>
                                <?php
                                }
                                ?>
                                <!-- hora_inicio -->
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label class="form-label" for="hora_inicio">Hora Inicio de Recolección</label>
                                    <div class="input-group input-group-merge">
                                        <span id="hora_inicio_span" class="input-group-text">
                                            <i class="fas fa-truck"></i>
                                        </span>
                                        <input type="time" readonly required class="form-control" name="hora_inicio"
                                            id="hora_inicio" />
                                    </div>
                                </div>
                                <script>
                                // Función para actualizar la hora en el campo de hora_inicio
                                function actualizarHora() {
                                    // Obtener la hora actual
                                    const ahora = new Date();
                                    // Formatear la hora en formato HH:MM (24 horas)
                                    const horas = String(ahora.getHours()).padStart(2, '0');
                                    const minutos = String(ahora.getMinutes()).padStart(2, '0');
                                    // Actualizar el valor del input con la hora formateada
                                    document.getElementById('hora_inicio').value = `${horas}:${minutos}`;
                                }
                                // Actualizar la hora cada segundo
                                setInterval(actualizarHora, 1000);
                                // Llamar a la función inmediatamente para establecer la hora inicial
                                actualizarHora();
                                </script>
                                <!-- foto_kilometraje -->
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label class="form-label" for="foto_kilometraje">Foto del Kilometraje
                                        Inicial</label>
                                    <div class="input-group input-group-merge">
                                        <span id="nombre_area-span" class="input-group-text"><i
                                                class="fas fa-truck"></i></span>
                                        <input type="file" accept="image/*" required class="form-control"
                                            name="foto_kilometraje" id="foto_kilometraje" onchange="validarImagen()" />

                                    </div>
                                </div>
                                <script>
                                function validarImagen() {
                                    const inputFile = document.getElementById('foto_kilometraje');
                                    const file = inputFile.files[0];

                                    if (file) {
                                        const fileType = file.type;
                                        const fileSize = file.size / 1024 / 1024; // Convertir el tamaño de bytes a MB
                                        const validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
                                        const maxSize = 5; // Tamaño máximo en MB

                                        // Validar el tipo de archivo
                                        if (!validImageTypes.includes(fileType)) {
                                            swal.fire({
                                                title: 'Error',
                                                text: 'Solo se permiten archivos de imagen (JPEG, PNG o JPG).',
                                                icon: 'error',
                                                confirmButtonText: 'Aceptar'
                                            });
                                            inputFile.value = ''; // Limpiar el input si el archivo no es válido
                                            return;
                                        }

                                        // Validar el tamaño del archivo
                                        if (fileSize > maxSize) {
                                            swal.fire({
                                                title: 'Error',
                                                text: 'El tamaño de la imagen no debe exceder los 5 MB.',
                                                icon: 'error',
                                                confirmButtonText: 'Aceptar'
                                            });
                                            inputFile.value = ''; // Limpiar el input si el archivo es muy grande
                                            return;
                                        }
                                    }
                                }
                                </script>
                                <!-- kilometraje -->
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label class="form-label" for="kilometraje">Kilometraje Inicial</label>
                                    <div class="input-group input-group-merge">
                                        <span id="kilometraje_span" class="input-group-text"><i
                                                class="fas fa-truck"></i></span>
                                        <input type="text" minlength="1" maxlength="10" required
                                            onkeypress="return(multiplenumber(event));" class="form-control"
                                            name="kilometraje" id="kilometraje" placeholder="Ingresar kilometraje" />
                                    </div>
                                </div>
                                <!-- horometro -->
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label class="form-label" for="horometro">Horometro</label>
                                    <div class="input-group input-group-merge">
                                        <span id="horometro_span" class="input-group-text"><i
                                                class="fas fa-truck"></i></span>
                                        <input type="text" minlength="1" maxlength="10" required
                                            onkeypress="return(multiplenumber(event));" class="form-control"
                                            name="horometro" id="horometro" placeholder="Ingresar horometro" />
                                    </div>
                                </div>
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label for="ciudad" class="form-label">Ciudad de Recoleccion</label>
                                    <div class="input-group input-group-merge">
                                        <span id="ciudad-2" class="input-group-text"><i class="fas fa-truck"></i></span>
                                        <select class="form-select" name="ciudad" id="ciudad" required>
                                            <option value="">Seleccionar Ciudad de Recoleccion...</option>
                                            <?php
                                            // CONSUMO DE DATOS DE LAS CIUDADES
                                            $ciudades_query = $connection->prepare("SELECT * FROM ciudades");
                                            $ciudades_query->execute();
                                            $ciudades = $ciudades_query->fetchAll(PDO::FETCH_ASSOC);
                                            if (empty($ciudades)) {
                                                echo "<option value=''>No hay datos...</option>";
                                            } else {
                                                foreach ($ciudades as $ciudad) {
                                                    echo "<option value='{$ciudad['id_ciudad']}'>{$ciudad['ciudad']}</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- Contenedor de los empleados -->
                                <div class="row mb-3 col-12" id="empleados" style="display: none;">
                                    <div class="col-12 mb-3">
                                        <h5 class="text-center">Selecciona los Empleados</h5>
                                    </div>
                                    <div class="row" id="empleado-list">
                                        <!-- Checkboxes de empleados aparecerán aquí -->
                                    </div>
                                </div>
                                <input type="hidden" id="empleadosInput" name="empleados">
                                <div class="mt-4">
                                    <!-- Botón de Cancelar -->
                                    <a href="index.php" class="btn btn-danger" id="cancelarBtn">
                                        Cancelar
                                    </a>
                                    <input type="submit" class="btn btn-primary" value="Registrar"></input>
                                    <input type="hidden" class="btn btn-info" value="formRegisterVehicleCompacter"
                                        name="MM_formRegisterVehicleCompacter"></input>
                                </div>
                            </div>
                        </form>
                        <script>
                        function validarEmpleados() {
                            // Obtenemos los datos de empleados registrados en el localStorage
                            const empleados = JSON.parse(localStorage.getItem("empleados"));

                            // Validamos que existan datos en el localStorage
                            if (!empleados || empleados.length === 0) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Debes seleccionar empleados en tu tripulación',
                                });
                                // Prevenir el envío del formulario
                                return false;
                            }
                            // Si existen empleados, los colocamos en el campo oculto para enviarlos a PHP
                            const campoOculto = document.getElementById('empleadosInput');
                            campoOculto.value = JSON.stringify(empleados);
                            // Verificamos que los empleados se hayan pasado correctamente al campo oculto
                            if (campoOculto.value === JSON.stringify(empleados)) {
                                // Permitimos el envío del formulario si el campo oculto contiene los datos correctos
                                return true;
                            } else {
                                // Si por alguna razón los datos no se transfirieron correctamente, mostramos un error
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Hubo un problema al registrar los empleados. Inténtalo de nuevo.',
                                });
                                return false;
                            }
                        }

                        document.addEventListener('DOMContentLoaded', function() {
                            const cancelarBtn = document.getElementById('cancelarBtn');
                            // Escucha el evento click en el botón Cancelar
                            cancelarBtn.addEventListener('click', function(event) {
                                // Elimina la propiedad que desees del localStorage
                                localStorage.removeItem(
                                    'empleados'
                                ); // Ajusta según el nombre de la propiedad a eliminar

                                window.location.href("index.php");
                            });
                        });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    require_once("../components/footer.php");

    ?>