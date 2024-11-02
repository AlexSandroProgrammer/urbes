<?php
$titlePage = "Registro de Poda de Arboles";
require_once("../components/navbar.php");
$today = date('Y-m-d');

// asignamos la query a una variable
$documento = $_SESSION['documento'];
// Preparamos la consulta para buscar el usuario
$queryType= $connection->prepare("SELECT * FROM usuarios INNER JOIN tipo_usuario ON usuarios.id_tipo_usuario = tipo_usuario.id_tipo_usuario WHERE documento = :documento");
$queryType->bindParam(":documento", $documento);
$queryType->execute();
$type = $queryType->fetch(PDO::FETCH_ASSOC);
// nombre completo

$tipo_usuario = $type['id_tipo_usuario'];


if ($tipo_usuario != 3) {
    // El usuario no es un conductor, redirige al index con un mensaje de error
    showErrorOrSuccessAndRedirect("error", "Error de usuario", "No eres un empleado por lo tanto no puedes ingresar a este formulario", "index.php");
    exit();
}

// Preparamos la consulta para buscar el usuario
$queryUser = $connection->prepare("SELECT * FROM usuarios INNER JOIN ciudades ON usuarios.id_ciudad = ciudades.id_ciudad WHERE documento = :documento");
$queryUser->bindParam(":documento", $documento);
$queryUser->execute();
$user = $queryUser->fetch(PDO::FETCH_ASSOC);
// nombre completo
$nombre_completo = $user['nombres'] . ' ' . $user['apellidos'];
$id_city = $user['id_ciudad'];

$queryZona = $connection->prepare("SELECT * FROM zonas INNER JOIN ciudades ON zonas.id_ciudad = ciudades.id_ciudad WHERE zonas.id_ciudad  = :id_ciudad");
$queryZona->bindParam(":id_ciudad", $id_city);
$queryZona->execute();
$zonas = $queryZona->fetchAll(PDO::FETCH_ASSOC);
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
                        <h3 class="fw-bold">REGISTRO DE PODA DE ARBOLES</h3>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST" enctype="multipart/form-data" autocomplete="off"
                            name="formRegisterTreePruning" onsubmit="disableSubmitButton(this);">
                            <div class="row">
                                <h5 class="mb-5 text-center"> <i class="bx bx-user"></i> Bienvenido(a)
                                    <?= $nombre_completo ?> al registro del
                                    formulario, te
                                    invitamos a rellenar la siguiente informacion </h5>
                                <!-- fecha_inicio -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="fecha_inicio">Fecha Inicio</label>
                                    <div class="input-group input-group-merge">
                                        <span id="nombre_area-span" class="input-group-text"><i
                                                class="fas fa-calendar-day"></i></span>
                                        <input type="date" required class="form-control ps-2" name="fecha_inicio"
                                            id="fecha_inicio" min="<?php echo $today; ?>" max="<?php echo $today; ?>"
                                            value="<?php echo $today; ?>" />
                                    </div>
                                </div>

                                <!-- hora_inicio -->
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label class="form-label" for="hora_inicio">Hora Inicio de Lavado</label>
                                    <div class="input-group input-group-merge">
                                        <span id="hora_inicio_span" class="input-group-text">
                                            <i class="fas fa-clock"></i>
                                        </span>
                                        <input type="time" readonly required class="form-control ps-2"
                                            name="hora_inicio" id="hora_inicio" />
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


                                <!-- numero de documento -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="documento">Número de Documento</label>
                                    <div class="input-group input-group-merge">
                                        <span id="documento-icon" class="input-group-text">
                                            <i class="fas fa-id-card"></i>
                                        </span>
                                        <input type="text" minlength="6" maxlength="10" oninput="maxlengthNumber(this)"
                                            onkeypress="return multiplenumber(event);" class="form-control ps-2 "
                                            readonly required id="documento" value="<?php echo ($documento); ?>"
                                            name="documento" placeholder="Ingresa tu número de documento" autofocus />
                                    </div>
                                </div>
                                <!-- nombres -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="nombres">Nombre Completo</label>
                                    <div class="input-group input-group-merge">
                                        <span id="nombres_span" class="input-group-text"><i
                                                class="fas fa-user"></i></span>
                                        <input type="text" required minlength="2" maxlength="100"
                                            class="form-control ps-2" name="nombres" id="nombres" disabled
                                            value="<?php echo ($nombre_completo); ?>"
                                            placeholder="Ingresar nombres completos" />
                                    </div>
                                </div>

                                <!-- ciudad -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="nombres">Ciudad</label>
                                    <div class="input-group input-group-merge">
                                        <span id="nombres_span" class="input-group-text"><i
                                                class="fas fa-city"></i></span>
                                        <input type="text" required minlength="2" maxlength="100"
                                            class="form-control ps-2" name="nombres" id="ciudad" disabled
                                            value="<?php echo $user['ciudad']; ?>" placeholder="Ingresa la ciudad" />
                                        <input type="hidden" name="id_ciudad" id="id_ciudad"
                                            value="<?php echo htmlspecialchars($id_city); ?>"
                                            placeholder="Ingresa la ciudad" />
                                    </div>


                                </div>



                                <div class="mt-4">
                                    <a href="index.php" class="btn btn-danger">
                                        Cancelar
                                    </a>
                                    <input type="submit" class="btn btn-primary" value="Registrar"></input>
                                    <input type="hidden" class="btn btn-info" value="formRegisterTreePruning"
                                        name="MM_formRegisterTreePruning"></input>
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

    ?>