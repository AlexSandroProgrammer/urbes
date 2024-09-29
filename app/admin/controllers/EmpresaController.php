<?php
if ((isset($_POST["MM_formRegisterCompany"])) && ($_POST["MM_formRegisterCompany"] == "formRegisterCompany")) {

    $matricula = $_POST['matricula'];
    $nombre_empresa = $_POST['empresa'];
    $frecuencia = $_POST['frecuencia'];

    // Validación de campos vacíos
    if (empty($matricula) || empty($nombre_empresa) || empty($frecuencia)) {
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Todos los campos son obligatorios, por favor completa el formulario", "empresas.php");
        exit();
    }

    // Validamos que no exista ya una empresa con la misma matricula
    $query = $connection->prepare("SELECT COUNT(*) FROM empresas WHERE matricula = :matricula");
    $query->bindParam(':matricula', $matricula);
    $query->execute();
    
    if ($query->fetchColumn() > 0) {
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Error, la matrícula que quieres registrar ya existe", "empresas.php");
        exit();
    }

    // Inserta la nueva empresa
    $query = $connection->prepare("INSERT INTO empresas (matricula, nombre_empresa, frecuencia) VALUES (:matricula, :nombre_empresa, :frecuencia)");
    $query->bindParam(':matricula', $matricula);
    $query->bindParam(':nombre_empresa', $nombre_empresa);
    $query->bindParam(':frecuencia', $frecuencia);

    if ($query->execute()) {
        showErrorOrSuccessAndRedirect("success", "Registro Exitoso", "Los datos se han registrado correctamente", "empresas.php");
        exit();
    } else {
        showErrorOrSuccessAndRedirect("error", "Error de registro", "Error al registrar los datos, por favor intentalo nuevamente", "empresas.php");
        exit();
    }
}
?>


<?php
if ((isset($_POST["MM_formUpdateCompany"])) && ($_POST["MM_formUpdateCompany"] == "formUpdateCompany")) {
    $matricula = $_POST['matricula'];
    $nombre_empresa = $_POST['empresa'];
    $frecuencia = $_POST['frecuencia'];

    // Validación de campos vacíos
    if (empty($matricula) || empty($nombre_empresa) || empty($frecuencia)) {
        showErrorOrSuccessAndRedirect("error", "Error de actualización", "Todos los campos son obligatorios, por favor completa el formulario", "empresas.php");
        exit();
    }

    // Actualizar la información de la empresa
    $query = $connection->prepare("UPDATE empresas SET nombre_empresa = :nombre_empresa, frecuencia = :frecuencia WHERE matricula = :matricula");
    $query->bindParam(':matricula', $matricula);
    $query->bindParam(':nombre_empresa', $nombre_empresa);
    $query->bindParam(':frecuencia', $frecuencia);

    if ($query->execute()) {
        showErrorOrSuccessAndRedirect("success", "Actualización Exitosa", "Los datos se han actualizado correctamente", "empresas.php");
        exit();
    } else {
        showErrorOrSuccessAndRedirect("error", "Error de Actualización", "Error al actualizar los datos, por favor intentalo nuevamente", "empresas.php");
        exit();
    }
}
?>

<?php
if ((isset($_POST["MM_formDeleteCompany"])) && ($_POST["MM_formDeleteCompany"] == "formDeleteCompany")) {
    $matricula = $_POST['matricula'];

    // Eliminar la empresa con la matrícula proporcionada
    $query = $connection->prepare("DELETE FROM empresas WHERE matricula = :matricula");
    $query->bindParam(':matricula', $matricula);

    if ($query->execute()) {
        showErrorOrSuccessAndRedirect("success", "Eliminado", "Los datos se han eliminado correctamente", "empresas.php");
        exit();
    } else {
        showErrorOrSuccessAndRedirect("error", "Error de Eliminacion", "Error al eliminar los datos, por favor intentalo nuevamente", "empresas.php");
        exit();
    }
}
?>