<?php
$titlePage = "Registro De Aforos";
require_once("../components/navbar.php");
$today = date('Y-m-d');

// Asignamos la query a una variable
$documento = $_SESSION['documento'];

// Preparamos la consulta para buscar el tipo de usuario
$queryType = $connection->prepare("SELECT * FROM usuarios INNER JOIN tipo_usuario ON usuarios.id_tipo_usuario = tipo_usuario.id_tipo_usuario WHERE documento = :documento");
$queryType->bindParam(":documento", $documento);
$queryType->execute();
$type = $queryType->fetch(PDO::FETCH_ASSOC);

$tipo_usuario = $type['id_tipo_usuario'];

if ($tipo_usuario != 4) {
    // El usuario no es un conductor, redirige al index con un mensaje de error
    showErrorOrSuccessAndRedirect("error", "Error de usuario", "No eres un conductor por lo tanto no puedes ingresar a este formulario", "index.php");
    exit();
}

// Preparamos la consulta para buscar al usuario
$queryUser = $connection->prepare("SELECT * FROM usuarios INNER JOIN ciudades ON usuarios.id_ciudad = ciudades.id_ciudad WHERE documento = :documento");
$queryUser->bindParam(":documento", $documento);
$queryUser->execute();
$user = $queryUser->fetch(PDO::FETCH_ASSOC);

$nombre_completo = $user['nombres'] . ' ' . $user['apellidos'];
$id_city = $user['id_ciudad'];

?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Layout -->
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header text-center mt-3">
                        <h3 class="fw-bold">REGISTRO DE AFOROS</h3>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST" enctype="multipart/form-data" autocomplete="off"
                            name="formRegisterAforo" onsubmit="disableSubmitButton(this);">
                            <div class="row">
                                <h5 class="text-center mb-5">
                                    <i class="bx bx-user"></i> Bienvenido(a) <?= $nombre_completo ?> al registro del
                                    formulario. Te invitamos a rellenar la siguiente información:
                                </h5>

                                <!-- Fecha de Registro -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label for="fecha_registro" class="form-label">Fecha de Registro</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
                                        <input type="date" required class="form-control" name="fecha_registro"
                                            id="fecha_registro" min="<?php echo $today; ?>" max="<?php echo $today; ?>"
                                            value="<?php echo $today; ?>" readonly />
                                    </div>
                                </div>
                                <!-- numero de documento -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="documento">Número de Documento</label>
                                    <div class="input-group input-group-merge">
                                        <span id="documento-icon" class="input-group-text">
                                            <i class="fas fa-id-card"></i>
                                        </span>
                                        <input type="number" min="0" oninput="maxlengthNumber(this)"
                                            onkeypress="return multiplenumber(event);" class="form-control ps-2 "
                                            readonly required id="documento"
                                            value="<?php echo htmlspecialchars($documento); ?>" name="documento"
                                            placeholder="Ingresa tu número de documento" autofocus />
                                    </div>
                                </div>
                                <!-- nombre -->
                                <div class="mb-3 col-12 col-lg-6">
                                    <label class="form-label" for="documento">Nombre completo</label>
                                    <div class="input-group input-group-merge">
                                        <span id="documento-icon" class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input type="text" minlength="4" maxlength="10" name="nombres"
                                            class="form-control ps-2 " readonly required
                                            value="<?php echo htmlspecialchars($nombre_completo); ?>"
                                            placeholder="Ingresa tu número de documento" autofocus />
                                    </div>
                                </div>

                                <!-- Empresa -->
                                <div class="mb-3 col-12 col-lg-6 col-xl-6">
                                    <label for="matricula" class="form-label">Empresa</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-handshake"></i></span>
                                        <select class="form-select " name="matricula" id="matricula" required>
                                            <option value="">Seleccionar Empresa...</option>
                                            <?php
                                            $empresa_query = $connection->prepare("SELECT * FROM empresas");
                                            $empresa_query->execute();
                                            $empresas = $empresa_query->fetchAll(PDO::FETCH_ASSOC);
                                            if (empty($empresas)) {
                                                echo "<option value=''>No hay datos...</option>";
                                            } else {
                                                foreach ($empresas as $empresa) {
                                                    echo "<option value='{$empresa['matricula']}'>{$empresa['nombre_empresa']}</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row" id="peso-foto-container">
                                    <div class="mb-3 col-12 col-lg-6" id="peso1-container">
                                        <label for="peso1" class="form-label">Peso en KG</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-weight-hanging"></i></span>
                                            <input type="number" step="0.001" min="0" class="form-control" id="peso1"
                                                name="peso1" placeholder="Ingresa el peso" autofocus />
                                        </div>
                                    </div>

                                    <div class="mb-3 col-12 col-lg-6" id="foto1-container">
                                        <label for="foto_aforo1" class="form-label">Foto Aforo</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-camera"></i></span>
                                            <input type="file" accept="image/*" class="form-control" id="foto_aforo1"
                                                name="foto_aforo1" />
                                        </div>
                                    </div>
                                </div>

                                <button type="button"
                                    class="btn btn-outline-success btn-sm rounded-pill d-flex justify-content-center align-items-center mx-auto mb-3"
                                    id="add-more" style="width: 50%; height: 40px;">
                                    <i class="fas fa-plus me-2"></i> Agregar peso y foto
                                </button>

                                <!-- Botones de acción -->
                                <div class="mt-4">
                                    <a href="index.php" class="btn btn-danger">Cancelar</a>
                                    <input type="submit" class="btn btn-primary" value="Registrar" />
                                    <input type="hidden" name="MM_formRegisterAforo" value="formRegisterAforo" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    let pesoCount = 1;
    let fotoCount = 1;

    document.getElementById('add-more').addEventListener('click', function() {
        if (pesoCount < 5 && fotoCount < 5) {
            pesoCount++;
            fotoCount++;

            // Crear nuevo campo de peso
            const pesoFotoContainer = document.getElementById('peso-foto-container');
            const newFields = `
            <div class="mb-3 col-12 col-lg-6" id="peso${pesoCount}-container">
                <label for="peso${pesoCount}" class="form-label">Peso en KG</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-weight-hanging"></i></span>
                    <input type="number" step="0.001" min="0" class="form-control" id="peso${pesoCount}" name="peso${pesoCount}" placeholder="Ingresa el peso" />
                </div>
            </div>

            <div class="mb-3 col-12 col-lg-6" id="foto${fotoCount}-container">
                <label for="foto_aforo${fotoCount}" class="form-label">Foto Aforo</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-camera"></i></span>
                    <input type="file" accept="image/*" class="form-control" id="foto_aforo${fotoCount}" name="foto_aforo${fotoCount}" />
                </div>
            </div>
        `;

            // Insertar los nuevos campos al final del contenedor
            pesoFotoContainer.insertAdjacentHTML('beforeend', newFields);
        }
    });
    </script>

    <?php
    require_once("../components/footer.php");
    ?>