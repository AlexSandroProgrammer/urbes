<!DOCTYPE html>
<html lang="es" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-template="urbes-admin-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Urbes || <?php echo $titlePage ?> </title>
    <meta name="description" content="" />
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../../assets/images/urbes.svg" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="../../assets/vendor/fonts/boxicons.css" />
    <!-- Core CSS -->
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../assets/css/demo.css" />
    <link rel="stylesheet" type="text/css" href="../../libraries/datatables/datatables.min.css" />
    <link rel="stylesheet" type="text/css"
        href="../../libraries/datatables/DataTables-1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="../../assets/vendor/libs/apex-charts/apex-charts.css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css"
        integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <!-- Page CSS -->
    <!-- Helpers -->
    <script src="../../assets/vendor/js/helpers.js"></script>
    <script src="../../assets/js/config.js"></script>
    <script src="../../js/functions.js"></script>
    <script src="../../js/sweetalert.js"></script>
    <script src="../../assets/css/sweetalert.css"></script>
</head>

<body>

    <?php
    
    // iniciamos sesion para obtener los datos del usuario autenticado
    session_start();

    // validamos que el usuario este autenticado
    require_once("../../validation/sessionValidation.php");
    // creamos la conexion a la base de datos
    require_once("../../../database/connection.php");
    $db = new Database();
    $connection = $db->conectar();
    // envolvemos nuestra aplicacion el horario de colombia
    date_default_timezone_set('America/Bogota');
    // importacion de funciones
    require_once("../../functions/functions.php");
    require_once("../../admin/auto/automations.php");
    // importacion de controladores
    require_once("../controllers/index.php");
    $documento = $_SESSION['documento'];
    $documentoUserSession = $connection->prepare("SELECT * FROM usuarios WHERE documento = '$documento'");
    $documentoUserSession->execute();
    $documentoSession = $documentoUserSession->fetch(PDO::FETCH_ASSOC);
    if (isset($_GET['logout'])) {
    ?>
    <script>
    localStorage.removeItem('empleados'); // Elimina los empleados del localStorage
    window.location.href = "../../"; // Redirige después de eliminar
    </script>
    <?php
        session_destroy();
        exit();
    }
    ?>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <!-- / Navbar -->
        <!-- Content wrapper -->
        <div class="content-wrapper ">
            <!-- Content -->
            <div class="container-xxl flex-grow-1 p-3">
                <nav
                    class="navbar d-flex navbar-example navbar-expand-lg bg-light align-items-center justify-content-center">
                    <div class="container-fluid">
                        <div class="app-brand">
                            <a href="index.php" class=" mb-2 align-items-center justify-content-center text-center">
                                <span class="demo">
                                    <img src="../../assets/images/urbes.svg" width="100" height="100" alt="">
                                </span>
                            </a>
                        </div>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbar-ex-3">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbar-ex-3">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            </ul>
                            <div class="d-flex" onsubmit="return false">
                                <a href="index.php" class="btn btn-primary mr-2"><i class="bx bx-home"></i> Regresar</a>
                                <a href="index.php?logout" class="btn btn-danger"><i class="bx bx-log-out-circle"></i>
                                    Cerrar Sesion</a>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
            <!--/ Supported content -->
        </div>
        <!-- / Layout page -->
    </div>