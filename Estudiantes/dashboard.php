<?php
session_start();
include("../navegacion/topbar-student.php") ;

// Verificar el rol del usuario
if ($_SESSION['role'] !== 'estudiante') {
    header('Location: ../index.php'); // Redirigir al inicio si no es estudiante
    exit();
}

// Si llegamos aquí, el usuario es estudiante
$userEmail = $_SESSION['user_email'];
$userName = $_SESSION['user_name'];

// Mostrar un saludo personalizado
echo "Bienvenido, $userName";

?>
<!DOCTYPE html>
<html lang="es">
<head>
<link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="../assets/js/DataTables/datatables.min.css" />
    <script src="../assets/js/DataTables/jquery.min.js"></script>
    <script src="../assets/js/DataTables/datatables.min.js"></script>
</head>
<body>
    
</body>
</html>

