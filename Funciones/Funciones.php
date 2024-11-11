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

function obtenerEstudiantePorId($stid, $type)
{
    global $con;
    if (strtolower($type) === "estudiante") {
        try {

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

            $results = $query->fetchAll(PDO::FETCH_OBJ);

            return $results;
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    } else if (strtolower($type) === "docente") {
        try {

            $sql = "SELECT 
                    p.cedula,  p.telefono, p.id_profesor, 
                    u.id_usuario,  u.nombre,  u.apellido,  u.correo, 
                    rm.id_curso, rm.id_materia, rm.id_profesor, 
                    c.id_curso, c.nivel, c.paralelo, c.id_carrera, 
                    ca.id_carrera, ca.nombre_carrera, 
                    m.id_materia, m.nombre
                FROM tbl_profesores p
                JOIN tbl_usuarios u ON p.id_profesor = u.id_usuario
                JOIN tbl_relacionmaterias rm ON p.id_profesor = rm.id_profesor
                JOIN tbl_cursos c ON rm.id_curso = c.id_curso
                JOIN tbl_carreras ca ON c.id_carrera = ca.id_carrera
                JOIN tbl_materias m ON rm.id_materia = m.id_materia;

                WHERE tbl_profesores.id_profesor = :stid";

            $query = $con->prepare($sql);
            $query->bindParam(':stid', $stid, PDO::PARAM_STR);
            $query->execute();

            $sql_options = "SELECT * FROM tbl_materias";
            $stmt_options = $con->prepare($sql_options);
            $stmt_options->execute();
            $materias = $stmt_options->fetchAll(PDO::FETCH_ASSOC);
            
            $sql_carreras = "SELECT * FROM tbl_carreras";
            $stmt_carreras = $con->prepare($sql_carreras);
            $stmt_carreras->execute();
            $carreras = $stmt_carreras->fetchAll(PDO::FETCH_ASSOC);
            
            $sql_cursos = "SELECT * FROM tbl_cursos";
            $stmt_cursos = $con->prepare($sql_cursos);
            $stmt_cursos->execute();
            $cursos = $stmt_cursos->fetchAll(PDO::FETCH_ASSOC);

            
            return $query->fetchAll(PDO::FETCH_OBJ),$materias;
        } catch (PDOException $e) {

            return "Error: " . $e->getMessage();
        }
    }
}
