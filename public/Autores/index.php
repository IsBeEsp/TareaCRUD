<?php
session_start();
use Src\Autores;

require __DIR__ . "/../../vendor/autoload.php";

Autores::crearAutores(20);
$autores = (new Autores)->read();
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
    <title>Autores</title>
</head>

<body style="background-color:cadetblue;">
    <h5 class="text-center my-4">Listado de autores</h5>
    <div class="container mx-auto mb-3"><a href="create.php" class="btn btn-success"><i class="fas fa-plus"></i> Añadir autor</a></div>
    <div class="container mx-auto text-center">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Nombre completo</th>
                    <th scope="col">País de procedencia</th>
                    <th scope="col">Fecha de nacimiento</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php
                    foreach ($autores as $autor) {
                        $fechaFormatoSQL = explode("-",$autor->fecha);
                        $fecha = $fechaFormatoSQL[2]."/".$fechaFormatoSQL[1]."/".$fechaFormatoSQL[0];
                        echo <<<TXT
                            <tr>
                            <td>$autor->id</td>
                            <td>$autor->nombre_completo</td>
                            <td>$autor->pais</td>
                            <td>$fecha</td>
                            <td>
                                <form name='form' method='POST' action='delete.php'>
                                <input name='id' type='hidden' value='{$autor->id}'>
                                <a href='update.php?id={$autor->id}' class='btn btn-warning'><i class='fas fa-edit'></i></a>
                                <button name='btn' type='submit' class='btn btn-danger'><i class='fas fa-trash'></i></button>
                                </form>
                            </td>
                            </tr>
                            TXT;
                    }
                    ?>
                </tr>
            </tbody>
        </table>
    </div>
    <?php 
    if (isset($_SESSION['mensaje'])){
        echo <<<CODE
        <script>
            Swal.fire({
                icon: 'success',
                title: '{$_SESSION['mensaje']}',
                showConfirmButton: false,
                timer: 1500
            })
        </script>
        CODE;
        unset($_SESSION['mensaje']);
    }
    ?>
    
</body>

</html>