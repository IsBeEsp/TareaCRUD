<?php

use Src\Autores;
use Src\Utilities;

session_start();
require __DIR__ . "/../../vendor/autoload.php";

if (!isset($_GET['id'])){
    header('Location:index.php');
    die();
}

$id = $_GET['id'];

if(!Autores::existeAutor($id)){
    header('Location:index.php');
    die();
}

$autor = Autores::existeAutor($id, 1);

if (isset($_POST['btn'])) {
    $error = false;
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $pais = trim($_POST['pais']);
    $fecha = explode("/", $_POST['fecha']);

    // Valido nombre
    if (strlen($nombre) == 0) {
        $error = true;
        $_SESSION['nombre'] = "Este campo es obligatorio";
    }
    // Valido apellido
    if (strlen($apellidos) == 0) {
        $error = true;
        $_SESSION['apellidos'] = "Este campo es obligatorio";
    }

    $nombreCompleto = $nombre . " " . $apellidos;

    // Valido fecha
    if (sizeof($fecha) == 3) {
        if (!checkdate($fecha[1], $fecha[0], $fecha[2])) {
            $error = true;
            $_SESSION['fecha'] = "La fecha introducida no es válida.";
        }
    } else {
        $error = true;
        $_SESSION['fecha'] = "La fecha introducida no tiene un formato válido.";
    }

    $fechaFormatoSQL = $fecha[2] . "-" . $fecha[1] . "-" . $fecha[0];

    // Mostrar error
    if ($error){
        header("Location: update.php?id=$id");
        die();
    }

    // Enviar datos validados
    (new Autores)
    ->setNombreCompleto($nombreCompleto)
    ->setPais($pais)
    ->setFechaNac($fechaFormatoSQL)
    ->update($id);

    $_SESSION['mensaje'] = "Autor editado.";
    header("Location:index.php");
    die();
} else {
?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- CON BOOTSTRAP -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
        <!-- CON FONTAWESOME -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <!--sweetalert2 (js)-->
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <title>Añadir autor</title>
    </head>

    <body style="background-color:cadetblue;">
        <h5 class="text-center my-4">Añadir autor</h5>
        <div class="container p-4 my-4 mx-auto rounded bg-secondary">
            <form action="update.php?id=<?php echo $id ?>" method="POST" name="a">
                <div class="row">
                    <div class="col">
                        <label class="form-label"><b>Nombre</b></label>
                        <input name="nombre" type="text" class="form-control" placeholder="Introduce el nombre" value="<?php echo explode(" ", $autor->nombre_completo)[0]?>">
                        <?php
                        Utilities::mostrarError("nombre");
                        ?>
                    </div>
                    <div class="col">
                        <label class="form-label"><b>Apellidos</b></label>
                        <input name="apellidos" type="text" class="form-control" placeholder="Introduce los apellidos" value="<?php echo explode(" ", $autor->nombre_completo)[1]." ".explode(" ", $autor->nombre_completo)[2]?>">
                        <?php
                        Utilities::mostrarError("apellidos");
                        ?>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col">
                        <label class="form-label"><b>País de procedencia</b></label>
                        <input name="pais" type="text" class="form-control" placeholder="Introduce el país" value="<?php echo $autor->pais; ?>">
                    </div>
                    <div class="col">
                        <label class="form-label"><b>Fecha de nacimiento</b></label>
                        <input name="fecha" type="text" class="form-control" placeholder="01/01/2000" 
                        value="<?php
                        $fechaSinFormato = explode("-", $autor->fecha);
                        echo $fechaSinFormato[2]."/".$fechaSinFormato[1]."/".$fechaSinFormato[0];
                        ?>">
                        <?php
                        Utilities::mostrarError("fecha");
                        ?>
                    </div>
                </div>
                <div class="row mt-4 mx-auto">
                    <div class="col">
                        <hr>
                        <a href="index.php" class="btn btn-primary"><i class="fas fa-backward"></i> Volver</a>
                        <button name="btn" type="submit" class="btn btn-success"><i class="fa fa-edit"></i> Editar autor</button>
                    </div>
                </div>
            </form>
        </div>

    <?php } ?>