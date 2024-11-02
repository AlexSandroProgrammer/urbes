<?php
$titlePage = "Editar Recoleccion Veh. Compactador";
require_once("../components/sidebar.php");

if (isNotEmpty([$_GET['id_registro']])) {
    $id_registro = $_GET['id_registro'];
    $query = "SELECT 
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
FROM 
    vehiculo_compactador 
INNER JOIN labores ON vehiculo_compactador.id_labor = labores.id_labor 
INNER JOIN vehiculos ON vehiculo_compactador.id_vehiculo = vehiculos.placa 
INNER JOIN usuarios ON vehiculo_compactador.documento = usuarios.documento 
JOIN ciudades ON vehiculo_compactador.ciudad = ciudades.id_ciudad 
INNER JOIN estados ON vehiculo_compactador.id_estado = estados.id_estado 
WHERE 
    vehiculo_compactador.id_registro_veh_compactador = :id_registro_veh_compact;";

    // Ejecutamos la query
    $execute = $connection->prepare($query);
    $execute->bindParam(":id_registro_veh_compact", $id_registro);
    $execute->execute();
    $data = $execute->fetch(PDO::FETCH_ASSOC);

    if (isEmpty([$data])) {
        showErrorOrSuccessAndRedirect('error', "Lo sentimos...!", "Error al momento de obtener los datos del registro ", "index.php");
        exit();
    }

    // CONSULTA PARA TRAER DETALLE DE TRIPULACION
    $id_registro = $data['id_registro_veh_compactador'];
    $employees = $connection->prepare("SELECT usuarios.documento FROM detalle_tripulacion INNER JOIN usuarios ON detalle_tripulacion.documento = usuarios.documento WHERE id_registro = :id_registro");
    $employees->bindParam(":id_registro", $id_registro);
    $employees->execute();
    $detalle = $employees->fetchAll(PDO::FETCH_ASSOC);

    // Crear un array de documentos seleccionados
    $empleados_seleccionados = array_map(function ($item) {
        return $item['documento'];
    }, $detalle);

    // Obtener empleados disponibles para la ciudad
    $id_ciudad = $data['id_ciudad'];
    $id_tipo_usuario = 3;

    $query = "SELECT documento, nombres, apellidos FROM usuarios WHERE id_ciudad = :id_ciudad AND id_tipo_usuario = :id_tipo_usuario";
    $queryData = $connection->prepare($query);
    $queryData->bindParam(':id_ciudad', $id_ciudad, PDO::PARAM_INT);
    $queryData->bindParam(':id_tipo_usuario', $id_tipo_usuario, PDO::PARAM_STR);
    $queryData->execute();
    $empleados = $queryData->fetchAll(PDO::FETCH_ASSOC);
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
                        <h3 class="fw-bold">EDITAR DATOS VEHICULO COMPACTADOR</h3>
                        <h3 class="fw-bold">ID: <?= $data['id_registro_veh_compactador'] ?> - Labor:
                            <?= $data['labor'] ?> </h3>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST" enctype="multipart/form-data" autocomplete="off"
                            name="formUpdateRecoleccion">
                            <div class="row">
                                <input type="hidden" name="id_registro_veh_compactador"
                                    value="<?= $data['id_registro_veh_compactador'] ?>">
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label for="vehiculo" class="form-label">Estado</label>
                                    <div class="input-group input-group-merge">
                                        <span id="vehiculo-2" class="input-group-text"><i
                                                class="fas fa-truck"></i></span>
                                        <select class="form-select" name="id_estado" autofocus required>
                                            <option class="form-control" value="<?= $data['id_estado'] ?>">
                                                <?= $data['id_estado'] ?> - <?= $data['estado'] ?> </option>
                                            <?php
                                                // CONSUMO DE DATOS DE LOS vehiculos
                                                $stateGet = $connection->prepare("SELECT * FROM estados WHERE id_estado != ? AND id_estado != 1 AND id_estado != 2 AND id_estado != 3");
                                                $stateGet->execute([$data['id_estado']]);
                                                $estados = $stateGet->fetchAll(PDO::FETCH_ASSOC);
                                                // Verificar si no hay datos
                                                if (empty($estados)) {
                                                    echo "<option value=''>No hay datos...</option>";
                                                } else {
                                                    // Iterar sobre los vehiculos
                                                    foreach ($estados as $estado) {
                                                        echo "<option value='{$estado['id_estado']}'>{$estado['id_estado']} - {$estado['estado']}</option>";
                                                    }
                                                }
                                                ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- fecha_inicio -->
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label class="form-label" for="fecha_inicio">Fecha Inicio</label>
                                    <div class="input-group input-group-merge">
                                        <span id="nombre_area-span" class="input-group-text"><i
                                                class="fas fa-calendar-day"></i></span>
                                        <input type="date" name="fecha_inicio" required class="form-control"
                                            value="<?= $data['fecha_inicio'] ?>" id="fecha_inicio" />
                                    </div>
                                </div>
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label class="form-label" for="fecha_fin">Fecha Final</label>
                                    <div class="input-group input-group-merge">
                                        <span id="nombre_area-span" class="input-group-text"><i
                                                class="fas fa-calendar-day"></i></span>
                                        <input type="date" required class="form-control"
                                            value="<?= $data['fecha_fin'] ?>" name="fecha_fin" id="fecha_fin" />
                                    </div>
                                </div>
                                <!-- equipo de transporte -->
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label for="id_vehiculo" class="form-label">Equipo de Transporte</label>
                                    <div class="input-group input-group-merge">
                                        <span id="id_vehiculo-2" class="input-group-text"><i
                                                class="fas fa-truck"></i></span>
                                        <select class="form-select" id="id_vehiculo" name="id_vehiculo" required>

                                            <option class="form-control" value="<?= $data['placa'] ?>">
                                                <?= $data['placa'] ?> - <?= $data['vehiculo'] ?> </option>
                                            <?php
                                                // CONSUMO DE DATOS DE LOS vehiculos
                                                $driversGet = $connection->prepare("SELECT * FROM vehiculos WHERE placa != ?");
                                                $driversGet->execute([$data['placa']]);
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

                                <!-- numero de documento -->
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label class="form-label" for="documento">CONDUCTOR ENCARGADO DE REGISTRO</label>
                                    <div class="input-group input-group-merge">
                                        <span id="documento-icon" class="input-group-text"><i
                                                class="fas fa-id-card"></i></span>
                                        <input type="text" minlength="6" maxlength="10"
                                            value="<?= $data['documento'] ?>" class="form-control" readonly required
                                            id="documento" placeholder="Ingresa tu numero de documento" />
                                    </div>
                                </div>
                                <!-- hora_inicio -->
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label class="form-label" for="hora_inicio">Hora Inicio de Recolección</label>
                                    <div class="input-group input-group-merge">
                                        <span id="hora_inicio_span" class="input-group-text">
                                            <i class="fas fa-clock"></i>
                                        </span>
                                        <input type="time" required value="<?= $data['hora_inicio'] ?>"
                                            class="form-control" name="hora_inicio" id="hora_inicio" />
                                    </div>
                                </div>
                                <!-- hora_finalizacion -->
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label class="form-label" for="hora_finalizacion">Hora Fin de Recolección</label>
                                    <div class="input-group input-group-merge">
                                        <span id="hora_finalizacion_span" class="input-group-text">
                                            <i class="fas fa-clock"></i>
                                        </span>
                                        <input type="time" required class="form-control"
                                            value="<?= $data['hora_finalizacion'] ?>" name="hora_finalizacion"
                                            id="hora_finalizacion" />
                                    </div>
                                </div>
                                <!-- kilometraje inicial -->
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label class="form-label" for="kilometraje">Kilometraje Inicial</label>
                                    <div class="input-group input-group-merge">
                                        <span id="kilometraje_span" class="input-group-text"><i
                                                class="fas fa-road"></i></span>
                                        <input type="number" step="0.001" value="<?= $data['km_inicio'] ?>" required
                                            class="form-control ps-2" name="km_inicio" id="kilometraje"
                                            placeholder="Ingresar kilometraje" />
                                    </div>
                                </div>
                                <?php
                                    // validamos si tiene imagen o no
                                    if (isNotEmpty([$data['foto_kilometraje_inicial']])) {
                                    ?>
                                <input type="hidden" name="foto_kilometraje_inicial_old"
                                    value="<?= $data['foto_kilometraje_inicial'] ?>">
                                <!-- foto_kilometraje inicial -->
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label class="form-label" for="foto_kilometraje">Foto del Kilometraje
                                        Inicial</label>
                                    <div class="input-group input-group-merge text-center">
                                        <img src="../../employee/assets/images/<?= $data['foto_kilometraje_inicial'] ?>"
                                            width="150" alt="No se encontro foto del kilometraje inicial">
                                    </div>
                                </div>
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label class="form-label" for="foto_kilometraje_inicial">Cambiar Foto
                                        Kilometraje Inicial</label>
                                    <div class="input-group input-group-merge">
                                        <span id="nombre_area-span" class="input-group-text"><i
                                                class="fas fa-camera"></i></span>
                                        <input type="file" accept="image/*" class="form-control"
                                            name="foto_kilometraje_inicial" id="foto_kilometraje_inicial"
                                            onchange="validarImagenKmInicial()" />
                                    </div>
                                </div>
                                <?php
                                    } else {
                                        // si no hay imagen, generamos el input para subir una nueva
                                        // para esto necesitamos el id del input que contiene la imagen
                                        // en este caso es "foto_kilometraje_inicial"
                                    ?>

                                <input type="hidden" name="foto_kilometraje_inicial_old"
                                    value="<?= $data['foto_kilometraje_inicial'] ?>">
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">

                                    <label class="form-label" for="foto_kilometraje_inicial">Foto del Kilometraje
                                        Inicial</label>
                                    <div class="input-group input-group-merge">
                                        <span id="nombre_area-span" class="input-group-text"><i
                                                class="fas fa-camera"></i></span>
                                        <input type="file" accept="image/*" class="form-control"
                                            name="foto_kilometraje_inicial" id="foto_kilometraje_inicial"
                                            onchange="validarImagenKmInicial()" />
                                    </div>
                                </div>
                                <?php
                                    }
                                    ?>
                                <!-- kilometraje -->
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label class="form-label" for="km_fin">Kilometraje Final</label>
                                    <div class="input-group input-group-merge">
                                        <span id="kilometraje_span" class="input-group-text"><i
                                                class="fas fa-road"></i></span>
                                        <input type="number" step="0.001" min="1" value="<?= $data['km_fin'] ?>"
                                            class="form-control ps-2" name="km_fin" id="km_fin"
                                            placeholder="Ingresar kilometraje" />
                                    </div>
                                </div>
                                <script>
                                function validarImagenKmInicial() {
                                    const inputFile = document.getElementById('foto_kilometraje_inicial');
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
                                <?php
                                    // validamos si tiene imagen o no
                                    if (isNotEmpty([$data['foto_kilometraje_final']])) {
                                    ?>
                                <input type="hidden" name="foto_kilometraje_final_old"
                                    value="<?= $data['foto_kilometraje_final'] ?>">
                                <!-- foto_kilometraje final -->
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label class="form-label" for="foto_kilometraje">Foto del Kilometraje
                                        Final</label>
                                    <div class="input-group input-group-merge text-center">
                                        <img src="../../employee/assets/images/<?= $data['foto_kilometraje_final'] ?>"
                                            width="150" alt="No se encontro foto del kilometraje final">
                                    </div>
                                </div>
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label class="form-label" for="foto_kilometraje_final">Foto del Kilometraje
                                        Final</label>
                                    <div class="input-group input-group-merge">
                                        <span id="nombre_area-span" class="input-group-text"><i
                                                class="fas fa-camera"></i></span>
                                        <input type="file" accept="image/*" class="form-control"
                                            name="foto_kilometraje_final" id="foto_kilometraje_final"
                                            onchange="validarImagenKmFinal()" />
                                    </div>
                                </div>
                                <?php
                                    } else {
                                        // si no hay imagen, generamos el input para subir una nueva
                                        // para esto necesitamos el id del input que contiene la imagen
                                        // en este caso es "foto_kilometraje_final"
                                    ?>
                                <input type="hidden" name="foto_kilometraje_final_old"
                                    value="<?= $data['foto_kilometraje_final'] ?>">
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label class="form-label" for="foto_kilometraje_final">Foto del Kilometraje
                                        Final</label>
                                    <div class="input-group input-group-merge">
                                        <span id="nombre_area-span" class="input-group-text"><i
                                                class="fas fa-camera"></i></span>
                                        <input type="file" accept="image/*" class="form-control"
                                            name="foto_kilometraje_final" id="foto_kilometraje_final"
                                            onchange="validarImagenKmFinal()" />
                                    </div>
                                </div>

                                <?php
                                    }
                            ?>

                                <script>
                                function validarImagenKmFinal() {
                                    const inputFile = document.getElementById('foto_kilometraje_final');
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
                                <!-- horometro -->
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label class="form-label" for="horometro">Horometro Inicial</label>
                                    <div class="input-group input-group-merge">
                                        <span id="horometro_span" class="input-group-text"><i
                                                class="fas fa-clock"></i></span>
                                        <input type="number" min="0" step="0.001" required
                                            onkeypress="return(multiplenumber(event));"
                                            value="<?= $data['horometro_inicio'] ?>" class="form-control" id="horometro"
                                            name="horometro_inicio" placeholder="Ingresar horometro" />
                                    </div>
                                </div>
                                <!-- horometro final -->
                                <div class="mb-3 col-12 col-lg-6 col-xl-4">
                                    <label class="form-label" for="horometro_final">Horometro Final</label>
                                    <div class="input-group input-group-merge">
                                        <span id="horometro_span" class="input-group-text"><i
                                                class="fas fa-clock"></i></span>
                                        <input type="number" step="0.001" min="0" required
                                            value="<?= $data['horometro_fin'] ?>" class="form-control"
                                            id="horometro_final" name="horometro_fin"
                                            placeholder="Ingresar horometro final" />
                                    </div>
                                </div>
                                <div class="mb-3 col-12">
                                    <label for="observaciones" class="form-label">Observaciones</label>
                                    <div class="input-group input-group-merge">
                                        <span id="observaciones-2" class="input-group-text">
                                            <i class="fas fa-weight-hanging"></i>
                                        </span>
                                        <textarea class="form-control" id="observaciones" rows="5" name="observaciones"
                                            placeholder="Ingresar observación"><?= $data['observaciones'] ?></textarea>
                                    </div>
                                </div>
                                <div class="mb-3 col-12">
                                    <label for="ciudad" class="form-label">Ciudad de Recoleccion</label>
                                    <div class="input-group input-group-merge">
                                        <span id="ciudad-2" class="input-group-text"><i class="fas fa-city"></i></span>
                                        <input type="text" class="form-control" id="ciudad" name="ciudad" readonly
                                            placeholder="Ingresar ciudad" value="<?= $data['ciudad'] ?>" />
                                    </div>
                                </div>
                                <div class="mb-3 col-12">
                                    <label class="form-label">Seleccionar Empleados para tripulación:</label>
                                    <div class="row">
                                        <?php foreach ($empleados as $empleado): ?>
                                        <div class="col-md-6 col-lg-4 mb-2">
                                            <div class="d-flex align-items-center">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="empleados[]"
                                                        value="<?= $empleado['documento'] ?>"
                                                        <?= in_array($empleado['documento'], $empleados_seleccionados) ? 'checked' : '' ?>
                                                        id="empleado_<?= htmlspecialchars($empleado['documento']) ?>">
                                                    <label
                                                        class="form-check-label ms-2"><?= htmlspecialchars($empleado['nombres']) ?>
                                                        - <?= htmlspecialchars($empleado['apellidos']) ?></label>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    // Maneja el evento de cambio en los checkboxes
                                    const checkboxes = document.querySelectorAll("input[name='empleados[]']");
                                    const idRegistro =
                                        <?= json_encode($data['id_registro_veh_compactador']) ?>; // Asegúrate de que este ID se pase correctamente

                                    checkboxes.forEach(checkbox => {
                                        checkbox.addEventListener('change', function() {
                                            const documento = this.value;
                                            const isChecked = this.checked;
                                            // Crear objeto para la solicitud AJAX
                                            const xhr = new XMLHttpRequest();
                                            xhr.open('POST', 'handle_tripulacion.php', true);
                                            xhr.setRequestHeader('Content-Type',
                                                'application/x-www-form-urlencoded');

                                            // Configura la función que se ejecutará al recibir una respuesta
                                            xhr.onload = function() {
                                                if (xhr.status >= 200 && xhr.status < 300) {
                                                    // Parsear la respuesta JSON
                                                    const response = JSON.parse(xhr
                                                        .responseText);
                                                    // Desestructurar el objeto para obtener el mensaje
                                                    const {
                                                        message
                                                    } = response;
                                                    swal.fire({
                                                        title: 'Éxito',
                                                        text: message,
                                                        icon: 'success',
                                                        confirmButtonText: 'Aceptar'
                                                    })
                                                } else {
                                                    swal.fire({
                                                        title: 'Opss...',
                                                        text: 'Error al momento de realizar la peticion',
                                                        icon: 'error',
                                                        confirmButtonText: 'Aceptar'
                                                    })
                                                }
                                            };

                                            // Envía los datos al servidor
                                            xhr.send('documento=' + encodeURIComponent(
                                                    documento) + '&id_registro=' +
                                                encodeURIComponent(idRegistro) +
                                                '&action=' + (isChecked ? 'add' : 'remove')
                                            );
                                        });
                                    });

                                    // Maneja el evento de click en el botón Cancelar
                                    const cancelarBtn = document.getElementById('cancelarBtn');
                                    cancelarBtn.addEventListener('click', function() {
                                        localStorage.removeItem(
                                            'empleados'); // Elimina la propiedad del localStorage
                                        window.location.href = "index.php"; // Redirige a index.php
                                    });
                                });
                                </script>
                                <div class="mt-4">
                                    <!-- Botón de Cancelar -->
                                    <a href="index.php" class="btn btn-danger" id="cancelarBtn">
                                        Cancelar
                                    </a>
                                    <input type="submit" class="btn btn-primary" value="Actualizar"></input>
                                    <input type="hidden" class="btn btn-info" value="formUpdateRecoleccion"
                                        name="MM_formUpdateRecoleccion"></input>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
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
<?php
} else {
    showErrorOrSuccessAndRedirect("error", "Parametros incorrectos", "No puedes ingresar en esta pagina", "index.php");
}
?>