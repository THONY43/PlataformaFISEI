<?php
include("../includes/config.php");
$con = Database::getConnection();

// Funciones.php
function actualizarEstudiante($stid, $studentname, $roolid, $studentemail, $gender, $dob, $status)
{
    global $con;
    try {
        $sql = "UPDATE tbl_profesores SET 
                StudentName = :studentname,
                RollId = :roolid,
                StudentEmail = :studentemail,
                Gender = :gender,
                DOB = :dob,
                Status = :status 
                WHERE StudentId = :stid";

        $query = $con->prepare($sql);
        $query->execute([
            ':studentname' => $studentname,
            ':roolid' => $roolid,
            ':studentemail' => $studentemail,
            ':gender' => $gender,
            ':dob' => $dob,
            ':status' => $status,
            ':stid' => $stid
        ]);

        return "Información de Estudiante Actualizada Correctamente";
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}


// Funciones.php
function verificarRol($rolRequerido)
{

    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $rolRequerido) {
        header('Location: ../index.php');
        exit();
    }
}

function buscarUsuario()
{
    global $con;
    $sql = "SELECT tbl_usuarios.nombre, tbl_usuarios.apellido, tbl_usuarios.id_usuario,
    tbl_usuarios.correo, tbl_usuarios.tipo_usuario, tbl_usuarios.created_at
FROM tbl_usuarios";

    $query = $con->prepare($sql);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    return $results ?: [];
}
function buscarUsuario1($typeUser)
{
    global $con;

    if (isset($typeUser) && $typeUser === "docente") {
        $sql = "SELECT tbl_usuarios.nombre, tbl_usuarios.apellido, tbl_usuarios.id_usuario,
                       tbl_usuarios.correo, tbl_usuarios.tipo_usuario, tbl_usuarios.created_at,
                       tbl_profesores.cedula, tbl_profesores.id_profesor
                FROM tbl_usuarios
                JOIN tbl_profesores ON tbl_usuarios.id_usuario = tbl_profesores.id_profesor";

        $query = $con->prepare($sql);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        return $results ?: [];
    } else if (isset($typeUser) && $typeUser === "estudiante") {
        $sql = "SELECT tbl_usuarios.nombre, tbl_usuarios.apellido, tbl_usuarios.id_usuario,
                       tbl_usuarios.correo, tbl_usuarios.tipo_usuario, tbl_usuarios.created_at,
                       tbl_estudiantes.cedula, tbl_estudiantes.id_estudiante
                FROM tbl_usuarios
                JOIN tbl_estudiantes ON tbl_usuarios.id_usuario = tbl_estudiantes.id_estudiante";

        $query = $con->prepare($sql);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        return $results ?: [];
    }


    return [];
}

function obtenerEstudiantePorId($stid, $type)
{
    global $con;

    try {
        if (strtolower($type) === "estudiante") {
            $sql = "SELECT e.id_estudiante, e.cedula, e.direccion, e.telefono, 
                    i.id_usuario, i.id_curso, i.fecha_inscripcion, 
                    u.id_usuario, u.nombre, u.apellido, u.correo, 
                    c.id_curso, c.nivel, c.paralelo, c.id_carrera, 
                    ca.id_carrera, ca.nombre_carrera 
                    FROM tbl_estudiantes e
                    JOIN tbl_usuarios u ON u.id_usuario = e.id_estudiante
                    JOIN tbl_inscripciones i ON i.id_usuario = e.id_estudiante
                    JOIN tbl_cursos c ON c.id_curso = i.id_curso
                    JOIN tbl_carreras ca ON ca.id_carrera = c.id_carrera
                    WHERE e.id_estudiante = :stid";

            $query = $con->prepare($sql);
            $query->bindParam(':stid', $stid, PDO::PARAM_STR);
            $query->execute();

            return $query->fetchAll(PDO::FETCH_OBJ);
        } else if (strtolower($type) === "docente") {
            $sql = "SELECT 
                    p.cedula,  p.telefono, p.id_profesor, 
                    u.id_usuario, u.nombre, u.apellido, u.correo, 
                    rm.id_curso, rm.id_materia, rm.id_profesor, 
                    c.id_curso, c.nivel, c.paralelo, c.id_carrera, 
                    ca.id_carrera, ca.nombre_carrera, 
                    m.id_materia, m.nombre
                FROM tbl_profesores p
                JOIN tbl_usuarios u ON p.id_profesor = u.id_usuario
                JOIN tbl_relacionmaterias rm ON p.id_profesor = rm.id_profesor
                JOIN tbl_cursos c ON rm.id_curso = c.id_curso
                JOIN tbl_carreras ca ON c.id_carrera = ca.id_carrera
                JOIN tbl_materias m ON rm.id_materia = m.id_materia
                WHERE tbl_profesores.id_profesor = :stid";

            $query = $con->prepare($sql);
            $query->bindParam(':stid', $stid, PDO::PARAM_STR);
            $query->execute();

            $materias = $con->query("SELECT * FROM tbl_materias")->fetchAll(PDO::FETCH_ASSOC);
            $carreras = $con->query("SELECT * FROM tbl_carreras")->fetchAll(PDO::FETCH_ASSOC);
            $cursos = $con->query("SELECT * FROM tbl_cursos")->fetchAll(PDO::FETCH_ASSOC);

            return [
                'query_result' => $query->fetchAll(PDO::FETCH_OBJ),
                'materias' => $materias,
                'carreras' => $carreras,
                'cursos' => $cursos
            ];
        }
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}
