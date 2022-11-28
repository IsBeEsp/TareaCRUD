<?php
session_start();
use Src\Autores;

require __DIR__."/../../vendor/autoload.php";

if (!isset($_POST['id'])){
    header("Location:index.php");
    die();
}

$id = $_POST['id'];

if (!Autores::existeAutor($id)){
    header("Location:index.php");
    die();
}

Autores::delete($id);
$_SESSION['mensaje'] = "Autor borrado";
header("Location:index.php");
die();