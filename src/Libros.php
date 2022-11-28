<?php

namespace Src;

use PDO;
use PDOException;

class Libros extends Conexion{
    private string $isbn;
    private string $titulo;
    private string $fecha;
    private ?string $portada;
    private int $idAutor;

    public function __construct()
    {
        parent::__construct();
    }

    // CRUD
    public function create(){
        $q = "insert into libros(isbn, titulo, fecha, portada, autor_id) values(:i,:t,:f,:p,:a)";
        $stmt = parent::$conexion->prepare($q);
        $portada = ($this->portada == null) ? '/img/default.png': $this->portada;
        try {
            $stmt->execute([
                ':i'=>$this->isbn,
                ':t'=>$this->titulo,
                ':f'=>$this->fecha,
                ':p'=> $portada,
                ':a'=>$this->idAutor,
            ]);
        } catch (PDOException $e){
            die("Error en create: ".$e->getMessage());
        }
        parent::$conexion=null;
    }

    public function read(){
        $q = "select * from libros order by isbn asc";
        $stmt = parent::$conexion->prepare($q);
        try{
            $stmt->execute();
        } catch (PDOException $e){
            die("Error en read: ".$e->getMessage());
        }
        parent::$conexion=null;
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function update($isbn){
        $q = "update libros set titulo=:t, fecha=:f, portada=:p, autor_id=:a where isbn=:i";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ':i'=>$this->isbn,
                ':t'=>$this->titulo,
                ':f'=>$this->fecha,
                ':p'=>$this->portada,
                ':a'=>$this->idAutor,
            ]);
        } catch (PDOException $e){
            die("Error en update: ".$e->getMessage());
        }
        parent::$conexion=null;
    }

    public static function delete($isbn) {
        parent::crearConexion();
        $q = "delete from libros where isbn=:i";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([':i'=>$isbn]);
        } catch (PDOException $e) {
            die("Error en delete: ".$e->getMessage());
        }
        parent::$conexion=null;
    }

    // OTROS METODOS

    /* Para el create */
    public static function crearLibros($cant){
        if (self::hayLibros()) return;
        $faker = \Faker\Factory::create();
        $idAutores = Autores::hayAutores(1);
        for ($i = 0; $i<$cant; $i++){
            (new Libros)
            ->setIsbn($faker->isbn13())
            ->setTitulo($faker->words(rand(1,5), true))
            ->setFecha($faker->numberBetween(1000,2022)."-".$faker->numberBetween(1,12)."-".$faker->numberBetween(1,30))
            ->setPortada(null)
            ->setIdAutor($faker->randomElement($idAutores)["id"])
            ->create();
        }
    }

    private static function hayLibros(){
        parent::crearConexion();
        $q = "select isbn from libros";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute();
        } catch (PDOException $e){
            die("Error en hay libros: ".$e->getMessage());
        }
        parent::$conexion=null;
        return $stmt->rowCount();
    }

    public static function existeLibro($isbn, ?int $modo=null) {
        parent::crearConexion();
        $q = ($modo==null) ? "select isbn from libros where isbn=:i" : "select * from libros where isbn=:i";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([':i'=>$isbn]);
        } catch (PDOException $e) {
            die("Error en existe libro: ".$e->getMessage());
        }
        parent::$conexion=null;
        if ($modo == null)
            return $stmt->rowCount();
        else
            return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public static function existeTituloLibro($titulo) {
        parent::crearConexion();
        $q = "select isbn from libros where titulo=:t";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([':t'=>$titulo]);
        } catch (PDOException $e) {
            die("Error en existe titulo libro: ".$e->getMessage());
        }
        parent::$conexion=null;
        return $stmt->rowCount();
    }

    // SETTERS
    
    /**
     * Set the value of isbn
     *
     * @return  self
     */ 
    public function setIsbn($isbn)
    {
        $this->isbn = $isbn;

        return $this;
    }

    /**
     * Set the value of titulo
     *
     * @return  self
     */ 
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Set the value of fecha
     *
     * @return  self
     */ 
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Set the value of portada
     *
     * @return  self
     */ 
    public function setPortada($portada)
    {
        $this->portada = $portada;

        return $this;
    }

    /**
     * Set the value of idAutor
     *
     * @return  self
     */ 
    public function setIdAutor($idAutor)
    {
        $this->idAutor = $idAutor;

        return $this;
    }
}