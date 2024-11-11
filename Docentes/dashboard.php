<?php
session_start();
include("../navegacion/topbar-docente.php");

// Verificar el rol del usuario
if ($_SESSION['role'] !== 'docente') {
    header('Location: ../index.php'); // Redirigir al inicio si no es docente
    exit();
}

// Si llegamos aquí, el usuario es docente
$userEmail = $_SESSION['user_email'];
$userName = $_SESSION['user_name'];

// Mostrar un saludo personalizado
echo "Bienvenido, $userName";

?>
?>