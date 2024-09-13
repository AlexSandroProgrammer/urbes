<?php
$titlePage = "Bienvenido Usuario";
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
                    <div class="col-12 ui-bg-overlay-container p-4">
                        <div class="ui-bg-overlay bg-secondary opacity-75 rounded-end-bottom"></div>
                        <h5 class="text-white fw-semibold mb-3">Formularios Pendientes Vehiculo Compactador</h5>
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
                                    Hola <?php echo $driver['nombres'] ?>
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