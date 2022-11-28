<?php

namespace Src;

use PDO;
use PDOException;

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__."/../");
$dotenv->load();

class Conexion {
    protected static $conexion;

    public function __construct()
    {
            self::crearConexion();
    }

    public static function crearConexion(){
        if (self::$conexion != null) return;
        
        $user = $_ENV['USER'];
        $pass = $_ENV['PASS'];
        $host = $_ENV['HOST'];
        $datb = $_ENV['DATB'];

        $dsn = "mysql:host=$host;dbname=$datb;charset=utf8mb4";

        try {
            self::$conexion = new PDO($dsn, $user, $pass);
        } catch (PDOException $e) {
            die("Error en crear conexión: ".$e->getMessage());
        }

        return self::$conexion;
    }
}