<?php
$titlePage = "Panel de Administrador";
require_once("../components/sidebar.php");
// Obtener vehículos desde la base de datos que tengan fechas de vencimiento
$queryVehiculos = $connection->prepare("SELECT placa, vehiculo, fecha_soat, fecha_tecno FROM vehiculos");
$queryVehiculos->execute();
$vehiculos = $queryVehiculos->fetchAll(PDO::FETCH_ASSOC);
// Función para calcular días restantes
function calcularDiasRestantes($fecha_vencimiento)
{
    $fecha_actual = new DateTime();
    $fecha_vencimiento = new DateTime($fecha_vencimiento);
    $diferencia = $fecha_actual->diff($fecha_vencimiento);
    return $diferencia->days * ($fecha_vencimiento > $fecha_actual ? 1 : -1); // Si la fecha ya pasó, devuelve negativo
}
?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4 order-0">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-7">
                            <div class="card-body">
                                <h5 class="card-title text-primary">Bienvenido(a) Administrador!🎉</h5>
                                <p class="mb-4">
                                    En este Panel de Administrador puedes gestionar los diferentes datos de los
                                    empleados de tu empresa...
                                </p>
                                <a href="empleados_activos.php" class="btn btn-sm btn-outline-primary">Ver Empleados
                                    Activos</a>
                            </div>
                        </div>
                        <div class="col-sm-5 text-center text-sm-left">
                            <div class="card-body pb-0 px-0 px-md-4">
                                <img src="../../assets/images/man-with-laptop-light.png " height="140"
                                    alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png"
                                    data-app-light-img="illustrations/man-with-laptop-light.png" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Alertas de vehículos con fechas cercanas de vencimiento -->
            <?php
            foreach ($vehiculos as $vehiculo) {
                  $dias_restantes_soat = calcularDiasRestantes($vehiculo['fecha_soat']);
                  $dias_restantes_tecno = calcularDiasRestantes($vehiculo['fecha_tecno']);
    
                   // Alerta para el SOAT
                if ($dias_restantes_soat < 0) {
                 // SOAT vencido
                    echo "
                   <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                     <strong>Alerta de SOAT:</strong> El vehículo con placa <strong>{$vehiculo['placa']}</strong> está <strong>vencido</strong> desde hace <strong>" . abs($dias_restantes_soat) . " días</strong>.
                     <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                  </div>";
             } elseif ($dias_restantes_soat             <= 15) {
             // SOAT por vencer
                echo "
                <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                   <strong>Alerta de SOAT:</strong> El vehículo con placa <strong>{$vehiculo['placa']}</strong> le quedan <strong>{$dias_restantes_soat} días</strong> para el vencimiento del SOAT.
                  <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
           }

      // Alerta para la Tecnomecánica
        if ($dias_restantes_tecno < 0) {
          // Tecnomecánica vencida
           echo "
             <div class='alert alert-danger alert-dismissible fade show' role='alert'>
               <strong>Alerta de Tecnomecánica:</strong> El vehículo con placa <strong>{$vehiculo['placa']}</strong> está <strong>vencido</strong> desde hace <strong>" . abs($dias_restantes_tecno) . " días</strong>.
               <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
      } elseif ($dias_restantes_tecno <= 15) {
        // Tecnomecánica por vencer
        echo "
        <div class='alert alert-warning alert-dismissible fade show' role='alert'>
            <strong>Alerta de Tecnomecánica:</strong> El vehículo con placa <strong>{$vehiculo['placa']}</strong> le quedan <strong>{$dias_restantes_tecno} días</strong> para el vencimiento de la Tecnomecánica.
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
    }
}
?>

            <!-- Order Statistics -->
            <div class="col-md-6 col-lg-12 col-xl-6 order-0 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                        <div class="card-title mb-0">
                            <h5 class="m-0 me-2">Empleados</h5>
                            <small class="text-muted">Estadísticas Generales</small>
                        </div>
                    </div>
                    <div class="card-body mt-3">
                        <ul class="p-0 m-0">
                            <?php
                            countStatesUsers(
                                "conteoEmpleadosActivos",
                                "usuarios",
                                "Activos",
                                "Empleados Activos",
                                "1",
                                "success",
                                "3"
                            );
                            countStatesUsers(
                                "conteoEmpleadosBloqueados",
                                "usuarios",
                                "Bloqueados",
                                "Empleados Bloqueados",
                                "2",
                                "warning",
                                "3"
                            );
                            countStatesUsers(
                                "conteoEmpleadosEliminados",
                                "usuarios",
                                "Eliminados",
                                "Empleados Eliminados",
                                "3",
                                "danger",
                                "3"
                            );
                            ?>
                        </ul>
                        <div class="text-center"><a href="empleados_activos.php" class="btn btn-outline-primary">Ver
                                Empleados</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas de Administradores -->
            <div class="col-md-6 col-lg-12 col-xl-6 order-0 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                        <div class="card-title mb-0">
                            <h5 class="m-0 me-2">Administradores</h5>
                            <small class="text-muted">Estadísticas Generales</small>
                        </div>
                    </div>
                    <div class="card-body mt-3">
                        <ul class="p-0 m-0">
                            <?php
                            countStatesUsers(
                                "conteoSociosActivos",
                                "usuarios",
                                "Activos",
                                "Socios Activos",
                                "1",
                                "success",
                                "1"
                            );
                            countStatesUsers(
                                "conteoSociosBloqueados",
                                "usuarios",
                                "Bloqueados",
                                "Socios Bloqueados",
                                "2",
                                "warning",
                                "2"
                            );
                            countStatesUsers(
                                "conteoSociosEliminados",
                                "usuarios",
                                "Eliminados",
                                "Socios Eliminados",
                                "3",
                                "danger",
                                "2"
                            );
                            ?>
                        </ul>
                        <div class="text-center"><a href="socios_activos.php" class="btn btn-outline-primary">Ver
                                Administradores</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Más estadísticas y contenido -->
            <?php
            cardStadicts("conteo", "estados", "estados.php", "Estados");
            cardStadicts("conteo", "ciudades", "ciudades.php", "Ciudades");
            ?>
        </div>
    </div>

    <?php
    require_once("../components/footer.php")
    ?>
</div>