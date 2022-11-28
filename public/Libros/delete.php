<?php
session_start();
use Src\Libros;

require __DIR__."/../../vendor/autoload.php";

if(!isset($_POST['isbn'])) {
    header('Location:index.php');
    die();
}

$isbn = $_POST['isbn'];

if (!Libros::existeLibro($isbn)){
    header('Location:index.php');
    die();
}

Libros::delete($isbn);
$_SESSION['mensaje'] = "Libro borrado";
header('Location:index.php');
die();
