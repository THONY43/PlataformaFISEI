<?php
session_start();
include("../navegacion/topbar-admin.php");
include("../includes/modalserver.php");

include("../Funciones/Funciones.php");



$error = "";


$userEmail = $_SESSION['user_email'];
$userName = $_SESSION['user_name'];
$lastInsertId = true; 
if ($lastInsertId) {
    $msg = "Información del usuario agregada correctamente";
} else {
    $error = "Algo salió mal. Inténtalo de nuevo";
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="../assets/js/DataTables/datatables.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <div class="content-container">
        <div class="main-page">
            <div class="container-fluid1">
                <div class="row page-title-div">
                    <div class="col-md-6">
                        <h4 class="title">Agregar Usuario</h4>
                    </div>
                </div>
            </div>

            <!-- Selección del tipo de usuario -->
            <div class="select-container">
                <label for="userType" class="labeluser">Seleccionar tipo de usuario:</label>
                <select id="userType" name="userType">
                    <option value="">Seleccione una opción</option>
                    <option value="docente">Docente</option>
                    <option value="estudiante">Estudiante</option>
                </select>
            </div>

            <!-- Formulario de Docente -->
            <div id="docenteForm" style="display:none;">
                <section class="section1">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel">
                                    <div class="panel-heading">
                                        <h5>Completa la información del docente</h5>
                                    </div>
                                    <div class="panel-body">
                                        <?php if ($msg) { ?>
                                            <div class="alert alert-success" role="alert">
                                                <strong>Bien hecho! </strong><?php echo htmlentities($msg); ?>
                                            </div>
                                        <?php } else if ($error) { ?>
                                            <div class="alert alert-danger" role="alert">
                                                <strong>Algo salió mal!</strong> <?php echo htmlentities($error); ?>
                                            </div>
                                        <?php } ?>
                                        <form class="row" method="post">
                                            <!-- Campos para Docente -->
                                            <div class="form-group col-md-6">
                                                <label for="fullnameDocente" class="control-label">Apellidos</label>
                                                <input type="text" name="fullnameDocente" class="form-control" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="fullnameDocente" class="control-label">Nombres</label>
                                                <input type="text" name="fullnameDocente" class="form-control" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="cedulaDocente" class="control-label">C.I.</label>
                                                <input type="text" name="cedulaDocente" class="form-control" maxlength="10" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="emailDocente" class="control-label">Correo Personal</label>
                                                <input type="email" name="emailDocente" class="form-control" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="telefonoDocente" class="control-label">Teléfono</label>
                                                <input type="text" name="telefonoDocente" class="form-control" required>
                                            </div>
                                
                                            <div class="form-group col-md-6" style="overflow: auto; max-height: 200px;">
                                                <label class="control-label">Materias Impartidas</label>
                                                <?php
                                                $sql = "SELECT * FROM tbl_materias";
                                                $query = $con->prepare($sql);
                                                $query->execute();
                                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                if ($query->rowCount() > 0) {
                                                    foreach ($results as $result) {
                                                ?>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" name="subject[]" value="<?php echo
                                                                                                                htmlentities($result->id); ?>">
                                                                <?php echo htmlentities($result->nombre); ?>
                                                            </label>
                                                        </div>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </div>


                                            <!-- Otros campos necesarios -->
                                            <div class="form-group col-md-12">
                                                <button type="submit" name="submitDocente" class="btn btn-primary">Agregar Docente</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Formulario de Estudiante -->
            <div id="estudianteForm" style="display:none;">
                <section class="section1">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel">
                                    <div class="panel-heading">
                                        <h5>Completa la información del estudiante</h5>
                                    </div>
                                    <div class="panel-body">
                                        <?php if ($msg) { ?>
                                            <div class="alert alert-success" role="alert">
                                                <strong>Bien hecho! </strong><?php echo htmlentities($msg); ?>
                                            </div>
                                        <?php } else if ($error) { ?>
                                            <div class="alert alert-danger" role="alert">
                                                <strong>Algo salió mal!</strong> <?php echo htmlentities($error); ?>
                                            </div>
                                        <?php } ?>
                                        <form class="row" method="post">
                                            <!-- Campos para Estudiante -->
                                            <div class="form-group col-md-6">
                                                <label for="fullnameEstudiante" class="control-label">Nombre Completo</label>
                                                <input type="text" name="fullnameEstudiante" class="form-control" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="cedulaEstudiante" class="control-label">C.I.</label>
                                                <input type="text" name="cedulaEstudiante" class="form-control" maxlength="10" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="emailEstudiante" class="control-label">Correo</label>
                                                <input type="email" name="emailEstudiante" class="form-control" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="telefonoEstudiante" class="control-label">Teléfono</label>
                                                <input type="text" name="telefonoEstudiante" class="form-control" required>
                                            </div>
                                            <!-- Otros campos necesarios -->
                                            <div class="form-group col-md-12">
                                                <button type="submit" name="submitEstudiante" class="btn btn-primary">Agregar Estudiante</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#userType').on('change', function() {
                var userType = $(this).val();
                if (userType === 'docente') {
                    $('#docenteForm').show();
                    $('#estudianteForm').hide();
                } else if (userType === 'estudiante') {
                    $('#estudianteForm').show();
                    $('#docenteForm').hide();
                } else {
                    $('#docenteForm, #estudianteForm').hide();
                }
            });
        });
    </script>
</body>

</html>