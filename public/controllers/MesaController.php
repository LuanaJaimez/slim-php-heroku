<?php
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';
require_once './controllers/ChangelogController.php';

class MesaController extends Mesa implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $numero = Mesa::CrearNumero();
        $nombre = $parametros['nombre'];
        $estadoMesa = "Esperando";

        // Creamos la mesa
        $mesa = new Mesa();
        $mesa->numero = $numero;
        $mesa->estadoMesa = $estadoMesa;
        if(Mesa::ValidarUser($nombre))
        {
          $mesa->nombre = $nombre;
          $mesa->crearMesa();

          ChangelogController::CargarUno("mesas",$mesa->numero,$mesa->nombre,"Cargar datos","Datos de una mesa");
  
          $payload = json_encode(array("mensaje" => "Mesa creada con exito"));
          $mesa->Mostrar();
        }
        else
        {
          $payload = json_encode(array("mensaje" => "Error al crear la mesa"));
        }


        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function MesaEstado($request, $response, $args)
    {
      $estado = Mesa::obtenerEstados();
      $payload = json_encode($estado);

      ChangelogController::CargarUno("mesas",$estado,0,"Obtener datos","Estado de una mesa");

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos mesa por numero
        $mesa = $args['numero'];
        $unaMesa = Mesa::obtenerMesa($mesa);
        $payload = json_encode($unaMesa);

        ChangelogController::CargarUno("mesas",$unaMesa->id,$mesa,"Obtener datos","Datos de una mesa");

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Mesa::obtenerTodos();
        $payload = json_encode(array("listaMesa" => $lista));

        ChangelogController::CargarUno("mesas",0,0,"Obtener datos","Datos de todas las mesas");

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $numero = $parametros['numero'];
        Mesa::modificarMesa($numero);

        ChangelogController::CargarUno("mesas",$numero,0,"Modificar datos","Modificacion de una mesa");

        $payload = json_encode(array("mensaje" => "Mesa modificada con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $mesaId = $parametros['numero'];
        Mesa::borrarMesa($mesaId);

        ChangelogController::CargarUno("mesas",$mesaId,0,"Obtener datos","Baja de una mesa");

        $payload = json_encode(array("mensaje" => "Mesa borrada con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public static function ActualizarEstado($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $id = $parametros['id'];
        $numero = $parametros['numero'];
        $estadoMesa = $parametros['estado'];
        $e = new Mesa();
        
        $m = Mesa::obtenerMesa($id);
        $e = Mesa::ValidarEstado($estadoMesa);
        if($estadoMesa != NULL && $id != NULL)
        { 
          if($e > 0 && $e < 5)
          {
              $m->actualizarEstado($numero, $estadoMesa, $id);
              $payload = json_encode(array("mensaje" => "Mesa actualizada"));
              ChangelogController::CargarUno("mesas",$numero,$estadoMesa,"Actualizar datos","Actualizacion de una mesa");
          }
          else
          {
            $payload = json_encode(array("mensaje" => "No se pudo actualizar"));
          }
        }
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
      }

    public static function ActualizarMesa($estadoMesa, $idMesa)
    {
        $estadoMesa = $estadoMesa; 
        $idMesa = $idMesa;
        $e = new Mesa();
        
        $m = Mesa::obtenerMesa($idMesa);
        $e = Mesa::ValidarEstado($estadoMesa);
        if($estadoMesa != NULL && $idMesa != NULL)
        { 
          if($e > 0 && $e < 5)
          {
              $m->estadoMesa = $estadoMesa;
              $m->modificarMesa($idMesa);
              echo 'Mesa actualizada';
              ChangelogController::CargarUno("mesas",$idMesa,$estadoMesa,"Actualizar datos","Actualizacion de una mesa");
              return TRUE;
          }
          else
          {
              echo 'Error al actualizar';  
          }
        }
        return FALSE;
      }

      public function obtenerCuenta($request, $response, $args)
      {
        $parametros = $request->getParsedBody();
        $id = $parametros['id'];
        $codigo = $parametros['codigo'];
  
        $cuenta = Mesa::obtenerConsumosMesa($codigo);

        Mesa::cargarCuentaMesa($id,$cuenta[0][0]);

        $payload = json_encode(array("mensaje" => "La cuenta de la mesa ".$id." es $".$cuenta[0][0]));

        ChangelogController::CargarUno("mesas",$id,$codigo,"Obtener cuenta","Cuenta de una mesa");

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
      }
}
?>