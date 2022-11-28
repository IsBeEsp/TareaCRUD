<?php

use Src\Autores;
use Src\Libros;
use Src\Utilities;

session_start();
require __DIR__."/../../vendor/autoload.php";

if (isset($_POST['btn'])){
    $error = false;
    $isbn = trim($_POST['isbn']);
    $titulo = trim($_POST['titulo']);
    $autor = trim($_POST['autor']);
    $fecha = explode("/", $_POST['fecha']);

    // Valido isbn
    if (strlen($isbn) == 13){
        if (Libros::existeLibro($isbn)){
            $error = true;
            $_SESSION['isbn'] = "El ISBN introducido ya existe.";
        }
    } else {
        $error = true;
        $_SESSION['isbn'] = "El ISBN debe contener 13 dígitos.";
    }
    
    // Valido titulo
    if (strlen($titulo) != 0) {
        if (Libros::existeTituloLibro($titulo)){
            $error = true;
            $_SESSION['titulo'] = "El titulo introducido ya existe.";
        }
    } else {
        $error = true;
        $_SESSION['titulo'] = "Es obligatorio introducir un título.";
    }

    // Valido la fecha
    if (sizeof($fecha) == 3){
        if (!checkdate($fecha[1], $fecha[0], $fecha[2])){
            $error = true;
            $_SESSION['fecha'] = "La fecha introducida no es válida.";
        }
    } else {
        $error = true;
        $_SESSION['fecha'] = "La fecha introducida no tiene un formato válido.";
    }

    $fechaFormatoSQL = $fecha[2]."-".$fecha[1]."-".$fecha[0];
    
    // Valido que el autor exista.
    $idAutor = Autores::existeNombreAutor($autor)->id;
    if ($idAutor == null){
        $error = true;
        $_SESSION['autor'] = "El autor introducido no existe.";
    }
    
    Utilities::hayError($error);

    // Valido imagen subida
    $rutaImagen = "/img/deafult.png";
    $mimeImages = Utilities::getMimeImages();
    if ($_FILES['portada']['error']==0){
        // Se ha subido un archivo.
        if (in_array($_FILES['portada']['type'],$mimeImages)){
            // Se ha subido una imagen.
            $rutaImagen = "/img/".uniqid()."_".$_FILES['portada']['name'];
            if (!move_uploaded_file($_FILES['portada']['tmp_name'], __DIR__."/..".$rutaImagen)){
                $rutaImagen = "/img/deafult.png";
                $_SESSION['mensaje'] = "No se pudo guardar la imagen de portada.";
            }
        } else {
            $error = true;
            $_SESSION['portada'] = "El archivo subido no es una imagen.";
        }
        
    } else {
        $error = true;
        $_SESSION['portada'] = "No se pudo subir el archivo.";
    }
    
    Utilities::hayError($error);

    (new Libros)
    ->setIsbn($isbn)
    ->setTitulo($titulo)
    ->setFecha($fechaFormatoSQL)
    ->setIdAutor($idAutor)
    ->setPortada($rutaImagen)
    ->create();

    header("Location:index.php");
    $_SESSION['mensaje'] = "Libro añadido";
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
    <title>Añadir libro</title>
</head>

<body style="background-color:cadetblue;">
    <h5 class="text-center my-4">Añadir libro</h5>
    <div class="container p-4 my-4 mx-auto rounded bg-secondary">
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" name="a" enctype="multipart/form-data">
            <div class="row">
                <div class="col">
                    <label class="form-label"><b>ISBN</b></label>
                    <input name="isbn" type="text" class="form-control" placeholder="9876542143211">
                    <?php 
                        Utilities::mostrarError("isbn");
                    ?>
                </div>
                <div class="col">
                    <label class="form-label"><b>Título</b></label>
                    <input name="titulo" type="text" class="form-control" placeholder="Título del libro">
                    <?php 
                        Utilities::mostrarError("titulo");
                    ?>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col">
                    <label class="form-label"><b>Fecha de publicación</b></label>
                    <input name="fecha" type="text" class="form-control" placeholder="01/01/2000">
                    <?php 
                        Utilities::mostrarError("fecha");
                    ?>
                </div>
                <div class="col">
                    <label class="form-label"><b>Autor/a</b></label>
                    <input name="autor" type="text" class="form-control" placeholder="Nombre completo">
                    <?php 
                        Utilities::mostrarError("autor");
                    ?>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col">
                    <img id="img" src="./../img/default.png" class="rounded mx-auto d-block img-thumbnail" alt="" style="width:12rem">
                    <input id="portada" name="portada" type="file" class="form-control mt-4 mx-auto" style="width:12rem;">
                    <?php 
                        Utilities::mostrarError("portada");
                    ?>
                </div>
            </div>
            <div class="row mt-4 mx-auto">
                <div class="col">
                    <hr>
                    <a href="index.php" class="btn btn-primary"><i class="fas fa-backward"></i> Volver</a>
                    <button type="reset" class="btn btn-warning"><i class="fa fa-broom"></i> Limpiar</button>
                    <button name="btn" type="submit" class="btn btn-success"><i class="fa fa-book"></i> Añadir libro</button>
                </div>
            </div>
        </form>
    </div>
    <script>
            document.getElementById("portada").addEventListener('change', cambiarImagen);

            function cambiarImagen(event) {
                var file = event.target.files[0];
                var reader = new FileReader();
                reader.onload = (event) => {
                    document.getElementById("img").setAttribute('src', event.target.result)
                };
                reader.readAsDataURL(file);
            }
    </script>
</body>

</html>

<?php } ?>