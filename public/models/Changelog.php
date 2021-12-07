<?php
require_once './db/AccesoDatos.php';

class Changelog
{
    public $id;
    public $tabla;
    public $idTabla;
    public $nombreUsr;
    public $accion;
    public $descripcion;
    public $fecha;

    public function crearLog()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO changelogs (tabla, idTabla, nombreUsr, accion, descripcion, fecha) VALUES (:tabla, :idTabla, :nombreUsr, :accion, :descripcion, :fecha)");
        $consulta->bindValue(':tabla', $this->tabla, PDO::PARAM_STR);
        $consulta->bindValue(':idTabla', $this->idTabla, PDO::PARAM_STR);
        $consulta->bindValue(':nombreUsr', $this->nombreUsr, PDO::PARAM_STR);
        $consulta->bindValue(':accion', $this->accion, PDO::PARAM_STR);
        $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':fecha', $this->tabla, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, tabla, idTabla, nombreUsr, accion, descripcion, fecha FROM changelogs");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Changelog');
    }

    public static function obtenerTarde()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, tabla, idTabla, nombreUsr, accion, descripcion, fecha FROM changelogs WHERE descripcion = '(Entregado tarde)'");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Changelog');
    }

    public static function obtenerATiempo()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, tabla, idTabla, nombreUsr, accion, descripcion, fecha FROM changelogs WHERE descripcion = '(Entregado a tiempo)'");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Changelog');
    }

    public function Mostrar()
    {
        echo "---- CHANGELOG ----"."\n";
        echo "Id: ".$this->id."\n";
        echo "Tabla: ".$this->tabla."\n";
        echo "IdTabla: ".$this->idTabla."\n";
        echo "Usuario: ".$this->nombreUsr."\n";
        echo "Accion: ".$this->accion."\n";
        echo "Descripcion: ".$this->accion."\n";
        echo "Fecha: ".$this->fecha."\n";
    }

    public static function Listar($lista)
    {
        foreach ($lista as $obj)
        {
            $obj->Mostrar();
        }
    }
}