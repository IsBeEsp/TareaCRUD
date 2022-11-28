<?php

namespace Src;

use PDO;
use PDOException;

class Autores extends Conexion {
    private int $id;
    private string $nombreCompleto;
    private string $pais;
    private string $fechaNac;

    public function __construct()
    {
        parent::__construct();
    }

    // CRUD
    public function create() {
        $q = "insert into autores(nombre_completo, pais, fecha) values(:n, :p, :f)";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ':n'=>$this->nombreCompleto,
                ':p'=>$this->pais,
                ':f'=>$this->fechaNac
            ]);
        } catch (PDOException $e){
            die("Error en create: ".$e->getMessage());
        }
        parent::$conexion=null;
    }

    public function read(){
        $q = "select * from autores order by id asc";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute();
        } catch (PDOException $e){
            die("Error en read: ".$e->getMessage());
        }
        parent::$conexion=null;
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function update($id){
        $q = "update autores set nombre_completo=:n, pais=:p, fecha=:f where id=:i";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ':n'=>$this->nombreCompleto,
                ':p'=>$this->pais,
                ':f'=>$this->fechaNac,
                ':i'=>$id
            ]);
        } catch (PDOException $e){
            die("Error en update: ".$e->getMessage());
        }
        parent::$conexion=null;
    }

    public static function delete($id){
        parent::crearConexion();
        $q = "delete from autores where id=:i";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([':i'=>$id]);
        } catch (PDOException $e) {
            die("Error en delete: ".$e->getMessage());
        }
        parent::$conexion=null;
    }

    // OTROS METODOS

    /* Para el create */
    public static function crearAutores($cant){
        if (self::hayAutores()) return;
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < $cant; $i++){
            (new Autores)->setNombreCompleto($faker->name()." ".$faker->lastName())
            ->setPais($faker->countryISOAlpha3())
            ->setFechaNac($faker->numberBetween(1000,2022)."-".$faker->numberBetween(1,12)."-".$faker->numberBetween(1,30))
            ->create();
        }
    }

    public static function hayAutores(?int $modo=null){
        parent::crearConexion();
        $q = "select id from autores";
        $stmt = parent::$conexion->prepare($q);
        try{ 
            $stmt->execute();
        } catch (PDOException $e){
            die("Error en hay autores: ".$e->getMessage());
        }
        parent::$conexion=null;
        if ($modo ==null)
            return $stmt->rowCount();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function existeNombreAutor($nombreCompleto){
        parent::crearConexion();
        $q = "select id from autores where nombre_completo=:n";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([':n'=>$nombreCompleto]);
        } catch (PDOException $e){
            die("Error en existeNombreAutor: ".$e->getMessage());
        }
        parent::$conexion=null;
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /* Para el delete */
    public static function existeAutor($id, ?int $modo=null){
        parent::crearConexion();
        $q = ($modo == null) ? "select id from autores where id=:i" : "select * from autores where id=:i";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([':i'=>$id]);
        } catch (PDOException $e){
            die("Error en existeAutor: ".$e->getMessage());
        }
        parent::$conexion=null;
        if ($modo = null)
            return $stmt->rowCount();
        else
            return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // SETTERS

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set the value of nombreCompleto
     *
     * @return  self
     */ 
    public function setNombreCompleto($nombreCompleto)
    {
        $this->nombreCompleto = $nombreCompleto;

        return $this;
    }

    /**
     * Set the value of pais
     *
     * @return  self
     */ 
    public function setPais($pais)
    {
        $this->pais = $pais;

        return $this;
    }

    /**
     * Set the value of fechaNac
     *
     * @return  self
     */ 
    public function setFechaNac($fechaNac)
    {
        $this->fechaNac = $fechaNac;

        return $this;
    }
}