<?php
$titlePage = "Pendientes";
require_once("../components/navbar.php");
if ($documentoSession) {
    $documento = $documentoSession['documento'];
    $id_estado = 4;
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-4">
            <div class="col-lg-12 order-0">
                <div class="card mb-4">
                    <div class="d-flex align-items-center justify-content-center row">
                        <div class="col-sm-7">
                            <div class="card-body">
                                <h5 class="card-title text-primary">Panel de Formularios Pendientes</h5>
                                <p class="mb-4">
                                    En este panel podras observar los diferentes formularios que tienes pendientes, por
                                    favor tener en cuenta.
                                </p>
                                <a href="index.php" class="btn btn-primary"> <i class="bx bx-home"></i> Regresar</a>
                            </div>
                        </div>
                        <div class="col-sm-5 text-center text-sm-left">
                            <div class="py-3">
                                <img src="../../assets/images/employee.webp " height="160" alt="View Badge User" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-0 mb-4">
                    <!-- INICIO:FORMULARIOS VEHICULO COMPACTADOR -->
                    <div class="col-12 ui-bg-overlay-container p-4">
                        <div class="ui-bg-overlay bg-primary opacity-75 rounded-end-bottom"></div>
                        <h3 class="text-white fw-semibold mb-3">Formularios Pendientes Vehiculo Compactador</h3>
                        <h5 class="text-white fw-semibold mb-3">Recoleccion</h5>
                        <?php
                            $drivers = "SELECT 
                                vehiculo_compactador.*, 
                                labores.id_labor, 
                                labores.labor, 
                                vehiculos.placa, 
                                vehiculos.vehiculo, 
                                usuarios.documento, 
                                usuarios.nombres, 
                                usuarios.apellidos, 
                                ciudades.id_ciudad, 
                                ciudades.ciudad, 
                                estados.id_estado,
                                estados.estado
                            FROM vehiculo_compactador INNER JOIN labores ON vehiculo_compactador.id_labor = labores.id_labor INNER JOIN vehiculos ON vehiculo_compactador.id_vehiculo = vehiculos.placa INNER JOIN usuarios ON vehiculo_compactador.documento = usuarios.documento INNER JOIN ciudades ON vehiculo_compactador.ciudad = ciudades.id_ciudad INNER JOIN estados ON vehiculo_compactador.id_estado = estados.id_estado WHERE vehiculo_compactador.documento = :documento AND vehiculo_compactador.id_estado = :id_estado";
                            $count = $connection->prepare($drivers);
                            $count->bindParam(':documento', $documento, PDO::PARAM_INT);
                            $count->bindParam(':id_estado', $id_estado, PDO::PARAM_INT);
                            $count->execute();
                            // validamos si existen formularios por registrar
                            if ($count->rowCount() > 0) {
                                $driversData = $count->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                        <!-- // Iteramos sobre los datos para mostrar cada registro -->
                        <?php
                                foreach ($driversData as $driver) {
                                    // Convertir la fecha de registro a formato timestamp
                                    $fechaRegistro = strtotime($driver['fecha_registro']);
                                    $fechaActual = time();
                                    // Calcular la diferencia en segundos
                                    $diferenciaSegundos = $fechaActual - $fechaRegistro;
                                    // Calcular días, horas y minutos
                                    $dias = floor($diferenciaSegundos / (60 * 60 * 24));
                                    $horas = floor(($diferenciaSegundos % (60 * 60 * 24)) / (60 * 60));
                                    $minutos = floor(($diferenciaSegundos % (60 * 60)) / 60);
                                ?>
                        <div class="toast-container w-100 mb-3">
                            <div class="bs-toast toast fade show bg-danger w-100" role="alert" aria-live="assertive"
                                aria-atomic="true">
                                <div class="toast-header">
                                    <div class="d-flex w-100 justify-content-between flex-wrap">
                                        <!-- Columna izquierda (Título y icono) -->
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-bell me-2"></i>
                                            <div class="fw-semibold">Formulario Pendiente</div>
                                        </div>
                                        <!-- Columna derecha (Tiempo transcurrido) -->
                                        <small class="text-end mt-2 mt-sm-0">
                                            Hace
                                            <?php echo $dias . " días, " . $horas . " horas, " . $minutos . " minutos"; ?>
                                        </small>
                                    </div>
                                </div>
                                <div class="toast-body">
                                    Hola <?php echo $driver['nombres']; ?> tienes un formulario en estado
                                    <?php echo $driver['estado']; ?> en el tipo de labor
                                    <?php echo $driver['labor']; ?>, te invitamos por favor a que termines de rellenar
                                    toda la informacion presionar clic en el boton para direcionarte al formulario
                                    pendiente.
                                </div>
                                <div class="toast-body">
                                    <a href="terminar_form_veh_compactador.php?stmp=<?php echo $driver['id_registro_veh_compactador'] ?>"
                                        class="btn btn-primary">Finalizar Formulario <i
                                            class='bx bx-right-arrow-alt'></i></a>
                                </div>
                            </div>
                        </div>
                        <?php
                                }
                            } else {
                                ?>
                        <div class="toast-container w-100 mb-3">
                            <div class="bs-toast toast fade show bg-success w-100" role="alert" aria-live="assertive"
                                aria-atomic="true">
                                <div class="toast-header">
                                    <div class="d-flex w-100 justify-content-between flex-wrap">
                                        <!-- Columna izquierda (Título y icono) -->
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-check me-2"></i>
                                            <div class="fw-semibold">Sin formularios</div>
                                        </div>
                                        <!-- Columna derecha (Tiempo transcurrido) -->
                                        <small class="text-end mt-2 mt-sm-0">
                                            Excelente
                                        </small>
                                    </div>
                                </div>
                                <div class="toast-body">
                                    No tienes ningun formulario por finalizar
                                </div>
                            </div>
                        </div>
                        <?php
                            }
                            ?>
                        <h5 class="text-white fw-semibold mb-3">Disposicion al Relleno</h5>
                        <?php
                            $rellenos = "SELECT 
                                recoleccion_relleno.*, 
                                labores.id_labor, 
                                labores.labor, 
                                vehiculos.placa, 
                                vehiculos.vehiculo, 
                                usuarios.documento, 
                                usuarios.nombres, 
                                usuarios.apellidos, 
                                ciudades.id_ciudad, 
                                ciudades.ciudad, 
                                estados.id_estado,
                                estados.estado
                            FROM recoleccion_relleno INNER JOIN labores ON recoleccion_relleno.id_labor = labores.id_labor INNER JOIN vehiculos ON recoleccion_relleno.id_vehiculo = vehiculos.placa INNER JOIN usuarios ON recoleccion_relleno.documento = usuarios.documento INNER JOIN ciudades ON recoleccion_relleno.ciudad = ciudades.id_ciudad INNER JOIN estados ON recoleccion_relleno.id_estado = estados.id_estado WHERE recoleccion_relleno.documento = :documento AND recoleccion_relleno.id_estado = :id_estado";
                            $conteo = $connection->prepare($rellenos);
                            $conteo->bindParam(':documento', $documento, PDO::PARAM_INT);
                            $conteo->bindParam(':id_estado', $id_estado, PDO::PARAM_INT);
                            $conteo->execute();
                            // validamos si existen formularios por registrar
                            if ($conteo->rowCount() > 0) {
                                $rellenosData = $conteo->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                        <!-- // Iteramos sobre los datos para mostrar cada registro -->
                        <?php
                                foreach ($rellenosData as $relleno) {
                                    // Convertir la fecha de registro a formato timestamp
                                    $fechaRegistro = strtotime($relleno['fecha_registro']);
                                    $fechaActual = time();
                                    // Calcular la diferencia en segundos
                                    $diferenciaSegundos = $fechaActual - $fechaRegistro;
                                    // Calcular días, horas y minutos
                                    $dias = floor($diferenciaSegundos / (60 * 60 * 24));
                                    $horas = floor(($diferenciaSegundos % (60 * 60 * 24)) / (60 * 60));
                                    $minutos = floor(($diferenciaSegundos % (60 * 60)) / 60);
                                ?>
                        <div class="toast-container w-100 mb-3">
                            <div class="bs-toast toast fade show bg-danger w-100" role="alert" aria-live="assertive"
                                aria-atomic="true">
                                <div class="toast-header">
                                    <div class="d-flex w-100 justify-content-between flex-wrap">
                                        <!-- Columna izquierda (Título y icono) -->
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-bell me-2"></i>
                                            <div class="fw-semibold">Formulario Pendiente</div>
                                        </div>
                                        <!-- Columna derecha (Tiempo transcurrido) -->
                                        <small class="text-end mt-2 mt-sm-0">
                                            Hace
                                            <?php echo $dias . " días, " . $horas . " horas, " . $minutos . " minutos"; ?>
                                        </small>
                                    </div>
                                </div>
                                <div class="toast-body">
                                    Hola <?php echo $relleno['nombres']; ?> tienes un formulario en estado
                                    <?php echo $relleno['estado']; ?> en el tipo de labor
                                    <?php echo $relleno['labor']; ?>, te invitamos por favor a que termines de rellenar
                                    toda la informacion presionar clic en el boton para direcionarte al formulario
                                    pendiente.
                                </div>
                                <div class="toast-body">
                                    <a href="terminar_form_disposicion.php?stmp=<?php echo $relleno['id_recoleccion'] ?>"
                                        class="btn btn-primary">Finalizar Formulario <i
                                            class='bx bx-right-arrow-alt'></i></a>
                                </div>
                            </div>
                        </div>
                        <?php
                                }
                            } else {
                                ?>
                        <div class="toast-container w-100 mb-3">
                            <div class="bs-toast toast fade show bg-success w-100" role="alert" aria-live="assertive"
                                aria-atomic="true">
                                <div class="toast-header">
                                    <div class="d-flex w-100 justify-content-between flex-wrap">
                                        <!-- Columna izquierda (Título y icono) -->
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-check me-2"></i>
                                            <div class="fw-semibold">Sin formularios</div>
                                        </div>
                                        <!-- Columna derecha (Tiempo transcurrido) -->
                                        <small class="text-end mt-2 mt-sm-0">
                                            Excelente
                                        </small>
                                    </div>
                                </div>
                                <div class="toast-body">
                                    No tienes ningun formulario por finalizar
                                </div>
                            </div>
                        </div>
                        <?php
                            }
                            ?>
                    </div>
                    <!-- FIN: FORMULARIOS VEHICULO COMPACTADOR -->
                    <!-- INICIO: FORMULARIO MECANICA VEHICULO COMPACTADOR -->
                    <div class="col-12 ui-bg-overlay-container p-4">
                        <div class="ui-bg-overlay bg-primary opacity-75 rounded-end-bottom"></div>
                        <h5 class="text-white fw-semibold mb-3">Formularios Pendientes Mecanica Vehiculo Compactador
                        </h5>
                        <?php
                            $mecanica = "SELECT 
                                mecanica.*, 
                                actividades.id_actividad, 
                                actividades.actividad, 
                                vehiculos.placa, 
                                vehiculos.vehiculo, 
                                usuarios.documento, 
                                usuarios.nombres, 
                                usuarios.apellidos, 
                                estados.id_estado,
                                estados.estado
                            FROM mecanica INNER JOIN actividades ON mecanica.id_actividad = actividades.id_actividad INNER JOIN vehiculos ON mecanica.id_vehiculo = vehiculos.placa INNER JOIN usuarios ON mecanica.documento = usuarios.documento INNER JOIN estados ON mecanica.id_estado = estados.id_estado WHERE mecanica.documento = :documento AND mecanica.id_estado = :id_estado";
                            $mecanica = $connection->prepare($mecanica);
                            $mecanica->bindParam(':documento', $documento, PDO::PARAM_INT);
                            $mecanica->bindParam(':id_estado', $id_estado, PDO::PARAM_INT);
                            $mecanica->execute();
                            // validamos si existen formularios por registrar
                            if ($mecanica->rowCount() > 0) {
                                $mecanicData = $mecanica->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                        <!-- // Iteramos sobre los datos para mostrar cada registro -->
                        <?php
                                foreach ($mecanicData as $driver) {
                                    // Convertir la fecha de registro a formato timestamp
                                    $fechaRegistro = strtotime($driver['fecha_registro']);
                                    $fechaActual = time();
                                    // Calcular la diferencia en segundos
                                    $diferenciaSegundos = $fechaActual - $fechaRegistro;
                                    // Calcular días, horas y minutos
                                    $dias = floor($diferenciaSegundos / (60 * 60 * 24));
                                    $horas = floor(($diferenciaSegundos % (60 * 60 * 24)) / (60 * 60));
                                    $minutos = floor(($diferenciaSegundos % (60 * 60)) / 60);
                                ?>
                        <div class="toast-container w-100 mb-3">
                            <div class="bs-toast toast fade show bg-danger w-100" role="alert" aria-live="assertive"
                                aria-atomic="true">
                                <div class="toast-header">
                                    <div class="d-flex w-100 justify-content-between flex-wrap">
                                        <!-- Columna izquierda (Título y icono) -->
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-check me-2"></i>
                                            <div class="fw-semibold">Formulario Pendiente</div>
                                        </div>
                                        <!-- Columna derecha (Tiempo transcurrido) -->
                                        <small class="text-end mt-2 mt-sm-0">
                                            Hace
                                            <?php echo $dias . " días, " . $horas . " horas, " . $minutos . " minutos"; ?>
                                        </small>
                                    </div>
                                </div>
                                <div class="toast-body">
                                    Hola <?php echo $driver['nombres']; ?> tienes un formulario en estado
                                    <?php echo $driver['estado']; ?> en el tipo de actividad
                                    <?php echo $driver['actividad']; ?>, te invitamos por favor a que termines de
                                    rellenar
                                    toda la informacion presionar clic en el boton para direcionarte al formulario
                                    pendiente.
                                </div>
                                <div class="toast-body">
                                    <a href="terminar_mecanica.php?stmp=<?php echo $driver['id_registro'] ?>"
                                        class="btn btn-primary">Finalizar Formulario <i
                                            class='bx bx-right-arrow-alt'></i></a>
                                </div>
                            </div>
                        </div>
                        <?php
                                }
                            } else {
                                ?>
                        <div class="toast-container w-100 mb-3">
                            <div class="bs-toast toast fade show bg-success w-100" role="alert" aria-live="assertive"
                                aria-atomic="true">
                                <div class="toast-header">
                                    <div class="d-flex w-100 justify-content-between flex-wrap">
                                        <!-- Columna izquierda (Título y icono) -->
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-check me-2"></i>
                                            <div class="fw-semibold">Sin formularios</div>
                                        </div>
                                        <!-- Columna derecha (Tiempo transcurrido) -->
                                        <small class="text-end mt-2 mt-sm-0">
                                            Excelente
                                        </small>
                                    </div>
                                </div>
                                <div class="toast-body">
                                    No tienes ningun formulario por finalizar
                                </div>
                            </div>
                        </div>
                        <?php
                            }
                            ?>
                    </div>
                    <!-- FIN: FORMULARIO MECANICA VEHICULO COMPACTADOR -->
                    <!-- INICIO: FORMULARIO AREAS PUBLICAS -->
                    <div class="col-12 ui-bg-overlay-container p-4">
                        <div class="ui-bg-overlay bg-primary opacity-75 rounded-end-bottom"></div>
                        <h5 class="text-white fw-semibold mb-3">Formularios Pendientes Areas Publicas</h5>
                        <?php
                            $areas = "SELECT 
                                areas_publicas.*, 
                                labores.id_labor, 
                                labores.labor, 
                                usuarios.documento, 
                                usuarios.nombres, 
                                usuarios.apellidos, 
                                ciudades.id_ciudad, 
                                ciudades.ciudad, 
                                estados.id_estado,
                                estados.estado
                            FROM areas_publicas INNER JOIN labores ON areas_publicas.id_labor = labores.id_labor INNER JOIN usuarios ON areas_publicas.documento = usuarios.documento INNER JOIN ciudades ON areas_publicas.id_ciudad = ciudades.id_ciudad INNER JOIN estados ON areas_publicas.id_estado = estados.id_estado WHERE areas_publicas.documento = :documento AND areas_publicas.id_estado = :id_estado";
                            $countAreas = $connection->prepare($areas);
                            $countAreas->bindParam(':documento', $documento, PDO::PARAM_INT);
                            $countAreas->bindParam(':id_estado', $id_estado, PDO::PARAM_INT);
                            $countAreas->execute();
                            // validamos si existen formularios por registrar
                            if ($countAreas->rowCount() > 0) {
                                $areasData = $countAreas->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                        <!-- // Iteramos sobre los datos para mostrar cada registro -->
                        <?php
                                foreach ($areasData as $area) {
                                    // Convertir la fecha de registro a formato timestamp
                                    $fechaRegistro = strtotime($area['fecha_registro']);
                                    $fechaActual = time();
                                    // Calcular la diferencia en segundos
                                    $diferenciaSegundos = $fechaActual - $fechaRegistro;
                                    // Calcular días, horas y minutos
                                    $dias = floor($diferenciaSegundos / (60 * 60 * 24));
                                    $horas = floor(($diferenciaSegundos % (60 * 60 * 24)) / (60 * 60));
                                    $minutos = floor(($diferenciaSegundos % (60 * 60)) / 60);
                                ?>
                        <div class="toast-container w-100 mb-3">
                            <div class="bs-toast toast fade show bg-danger w-100" role="alert" aria-live="assertive"
                                aria-atomic="true">
                                <div class="toast-header">
                                    <div class="d-flex w-100 justify-content-between flex-wrap">
                                        <!-- Columna izquierda (Título y icono) -->
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-check me-2"></i>
                                            <div class="fw-semibold">Formulario Pendiente</div>
                                        </div>
                                        <!-- Columna derecha (Tiempo transcurrido) -->
                                        <small class="text-end mt-2 mt-sm-0">
                                            Hace
                                            <?php echo $dias . " días, " . $horas . " horas, " . $minutos . " minutos"; ?>
                                        </small>
                                    </div>
                                </div>
                                <div class="toast-body">
                                    Hola <?php echo $area['nombres']; ?> tienes un formulario en estado
                                    <?php echo $area['estado']; ?> en el tipo de labor
                                    <?php echo $area['labor']; ?>, te invitamos por favor a que termines de rellenar
                                    toda la informacion presionar clic en el boton para direcionarte al formulario
                                    pendiente.
                                </div>
                                <div class="toast-body">
                                    <a href="terminar_form_areas_public.php?stmp=<?php echo $area['id_registro'] ?>"
                                        class="btn btn-primary">Finalizar Formulario <i
                                            class='bx bx-right-arrow-alt'></i></a>
                                </div>
                            </div>
                        </div>
                        <?php
                                }
                            } else {
                                ?>
                        <div class="toast-container w-100 mb-3">
                            <div class="bs-toast toast fade show bg-success w-100" role="alert" aria-live="assertive"
                                aria-atomic="true">
                                <div class="toast-header">
                                    <div class="d-flex w-100 justify-content-between flex-wrap">
                                        <!-- Columna izquierda (Título y icono) -->
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-check me-2"></i>
                                            <div class="fw-semibold">Sin formularios</div>
                                        </div>
                                        <!-- Columna derecha (Tiempo transcurrido) -->
                                        <small class="text-end mt-2 mt-sm-0">
                                            Excelente
                                        </small>
                                    </div>
                                </div>
                                <div class="toast-body">
                                    No tienes ningun formulario por finalizar
                                </div>
                            </div>
                        </div>
                        <?php
                            }
                            ?>
                    </div>
                    <!-- FIN: FORMULARIO AREAS PUBLICAS -->
                    <!-- INICIO: FORMULARIO CARRO DE BARRIDO -->
                    <div class="col-12 ui-bg-overlay-container p-4">
                        <div class="ui-bg-overlay bg-primary opacity-75 rounded-end-bottom"></div>
                        <h5 class="text-white fw-semibold mb-3">Formularios Pendientes Carro de Barrido</h5>
                        <?php
                            $queryBarrido = "SELECT 
                                carro_barrido.*, 
                                actividades.id_actividad, 
                                actividades.actividad, 
                                usuarios.documento, 
                                usuarios.nombres, 
                                usuarios.apellidos,  
                                estados.id_estado,
                                estados.estado
                            FROM carro_barrido INNER JOIN actividades ON carro_barrido.id_actividad = actividades.id_actividad INNER JOIN usuarios ON carro_barrido.documento = usuarios.documento INNER JOIN estados ON carro_barrido.id_estado = estados.id_estado WHERE carro_barrido.documento = :documento AND carro_barrido.id_estado = :id_estado";
                            $countBarrido = $connection->prepare($queryBarrido);
                            $countBarrido->bindParam(':documento', $documento, PDO::PARAM_INT);
                            $countBarrido->bindParam(':id_estado', $id_estado, PDO::PARAM_INT);
                            $countBarrido->execute();
                            // validamos si existen formularios por registrar
                            if ($countBarrido->rowCount() > 0) {
                                $barridoData = $countBarrido->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                        <!-- // Iteramos sobre los datos para mostrar cada registro -->
                        <?php
                                foreach ($barridoData as $barrido) {
                                    // Convertir la fecha de registro a formato timestamp
                                    $fechaRegistro = strtotime($barrido['fecha_registro']);
                                    $fechaActual = time();
                                    // Calcular la diferencia en segundos
                                    $diferenciaSegundos = $fechaActual - $fechaRegistro;
                                    // Calcular días, horas y minutos
                                    $dias = floor($diferenciaSegundos / (60 * 60 * 24));
                                    $horas = floor(($diferenciaSegundos % (60 * 60 * 24)) / (60 * 60));
                                    $minutos = floor(($diferenciaSegundos % (60 * 60)) / 60);
                                ?>
                        <div class="toast-container w-100 mb-3">
                            <div class="bs-toast toast fade show bg-danger w-100" role="alert" aria-live="assertive"
                                aria-atomic="true">
                                <div class="toast-header">
                                    <div class="d-flex w-100 justify-content-between flex-wrap">
                                        <!-- Columna izquierda (Título y icono) -->
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-check me-2"></i>
                                            <div class="fw-semibold">Formulario Pendiente</div>
                                        </div>
                                        <!-- Columna derecha (Tiempo transcurrido) -->
                                        <small class="text-end mt-2 mt-sm-0">
                                            Hace
                                            <?php echo $dias . " días, " . $horas . " horas, " . $minutos . " minutos"; ?>
                                        </small>
                                    </div>
                                </div>
                                <div class="toast-body">
                                    Hola <?php echo $barrido['nombres']; ?> tienes un formulario en estado
                                    <?php echo $barrido['estado']; ?> en el tipo de actividad
                                    <?php echo $barrido['actividad']; ?>, te invitamos por favor a que termines de
                                    rellenar
                                    toda la informacion presionar clic en el boton para direcionarte al formulario
                                    pendiente.
                                </div>
                                <div class="toast-body">
                                    <a href="terminar_carro_barrido.php?stmp=<?php echo $barrido['id_registro_barrido'] ?>"
                                        class="btn btn-primary">Finalizar Formulario <i
                                            class='bx bx-right-arrow-alt'></i></a>
                                </div>
                            </div>
                        </div>
                        <?php
                                }
                            } else {
                                ?>
                        <div class="toast-container w-100 mb-3">
                            <div class="bs-toast toast fade show bg-success w-100" role="alert" aria-live="assertive"
                                aria-atomic="true">
                                <div class="toast-header">
                                    <div class="d-flex w-100 justify-content-between flex-wrap">
                                        <!-- Columna izquierda (Título y icono) -->
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-check me-2"></i>
                                            <div class="fw-semibold">Sin formularios</div>
                                        </div>
                                        <!-- Columna derecha (Tiempo transcurrido) -->
                                        <small class="text-end mt-2 mt-sm-0">
                                            Excelente
                                        </small>
                                    </div>
                                </div>
                                <div class="toast-body">
                                    No tienes ningun formulario por finalizar
                                </div>

                            </div>
                        </div>

                        <?php
                            }
                            ?>
                    </div>
                    <!-- FIN: FORMULARIO CARRO DE BARRIDO -->
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