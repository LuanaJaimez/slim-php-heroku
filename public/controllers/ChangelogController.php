<?php
require_once './models/Changelog.php';

class ChangelogController extends Changelog
{
    public static function CargarUno($tabla, $idTabla, $nombreUsr, $accion, $descripcion)
    {
      $fecha = new datetime("now");
     
      //Changelog
      $log = new Changelog();
      $log->tabla = $tabla;
      $log->idTabla = $idTabla;
      $log->nombreUsr = $nombreUsr;
      $log->accion = $accion;
      $log->descripcion = $descripcion;
      $log->fecha = $fecha->format('Y-m-d');

      //Creacion
      $creacion = $log->crearLog();

      if($creacion > 0)
      {
        $retorno = json_encode(array("mensaje" => "Log creado con exito"));
      }
      else
      {
        $retorno = json_encode(array("mensaje" => "Error al crear el log"));
      }

      return $retorno;
    }

    public function TraerTarde($request, $response, $args)
    {
        $tarde = Changelog::obtenerTarde();
        $payload = Changelog::Listar($tarde);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerATiempo($request, $response, $args)
    {
        $at = Changelog::obtenerATiempo();
        $payload = Changelog::Listar($at);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}