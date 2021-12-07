<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';
require_once './db/AccesoDatos.php';
require_once './controllers/MesaController.php';
require_once './controllers/UsuarioController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/EstadisticasController.php';
require_once './controllers/EncuestaController.php';
require_once './controllers/ManejoArchivos.php';

require_once './middlewares/AuthJWT.php';
require_once './middlewares/MWAccesos.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$app = AppFactory::create();
$app ->addBodyParsingMiddleware();
$errorMW = $app->addErrorMiddleware(true, true, true);

//USUARIOS
$app->group('/usuarios', function (RouteCollectorProxy $group)
{
    $group->get('/traer', \UsuarioController::class . ':TraerTodos');
    $group->get('/obtenerUsuario/{idUser}', \UsuarioController::class . ':TraerUno');
    $group->post('/login', \UsuarioController::class . ':LogIn');
    $group->post('/alta', \UsuarioController::class . ':CargarUno')->add(MWAccesos::class . ':EsSocio');
    $group->put('/modificar', \UsuarioController::class . ':ModificarUno')->add(MWAccesos::class . ':EsSocio');
    $group->delete('/borrar', \UsuarioController::class . ':BorrarUno')->add(MWAccesos::class . ':EsSocio');
  });

//MESAS
$app->group('/mesas', function (RouteCollectorProxy $group)
{
    $group->get('/traer', \MesaController::class . ':TraerTodos');
    $group->get('/estadoMesas', \MesaController::class . ':MesaEstado')->add(MWAccesos::class . ':EsSocio');
    $group->get('/{numero}', \MesaController::class . ':TraerUno');
    $group->post('/alta', \MesaController::class . ':CargarUno')->add(MWAccesos::class . ':EsMozoYSocio');
    $group->put('/actualizarMesa', \MesaController::class. ':ActualizarMesa')->add(MWAccesos::class. ':EsMozo');
    $group->post('/actualizarEstado', \MesaController::class. ':ActualizarEstado')->add(MWAccesos::class. ':EsMozo');
    $group->post('/obtenerCuenta', \MesaController::class . ':obtenerCuenta')->add(MWAccesos::class . ':EsMozoYSocio');
    $group->post('/cerrarMesa', \MesaController::class. ':ActualizarEstado')->add(MWAccesos::class. ':EsSocio');
    $group->delete('/borrar', \MesaController::class . ':BorrarUno')->add(MWAccesos::class . ':EsSocio');
  });

//PRODUCTOS
$app->group('/productos', function (RouteCollectorProxy $group)
{
    $group->get('/traer', \ProductoController::class . ':TraerTodos');
    $group->get('/{idProduc}', \ProductoController::class . ':TraerUno');
    $group->post('/alta', \ProductoController::class . ':CargarUno')->add(MWAccesos::class . ':EsSocio');
    $group->put('/modificar', \ProductoController::class . ':ModificarUno')->add(MWAccesos::class . ':EsSocio');
    $group->delete('/borrar', \ProductoController::class . ':BorrarUno')->add(MWAccesos::class . ':EsSocio');
  });

//PEDIDOS
$app->group('/pedidos', function (RouteCollectorProxy $group)
{    
    $group->get('/traer', \PedidoController::class . ':TraerTodos');
    $group->get('/traerListos/{tipo}', \PedidoController::class . ':TraerListos')->add(MWAccesos::class . ':EsMozoYSocio');
    $group->get('/pedidosDemora', \PedidoController::class . ':PedidosDemora')->add(MWAccesos::class . ':EsSocio');
    $group->get('/traerPorPuesto/{tipo}', \PedidoController::class . ':TraerPorPuesto');
    $group->get('/traerPendiente/{tipo}', \PedidoController::class . ':TraerPendiente');
    $group->get('/{codigo}', \PedidoController::class . ':TraerUno');
    $group->post('/pendientes', \PedidoController::class . ':TraerPedidoPendiente');
    $group->post('/alta', \PedidoController::class . ':CargarUno')->add(MWAccesos::class . ':EsMozoYSocio');
    $group->post('/agregar', \PedidoController::class . ':AgregarProducto')->add(MWAccesos::class . ':EsMozoYSocio');
    $group->post('/foto', \PedidoController::class . ':SacarFoto')->add(MWAccesos::class . ':EsMozoYSocio');
    $group->delete('/borrar', \PedidoController::class . ':BorrarUno')->add(MWAccesos::class . ':EsSocio');
    $group->post('/actualizar', \PedidoController::class . ':ActualizarEstado');
    $group->post('/actualizarMozo', \PedidoController::class . ':ActualizarEstadoMozo');
    $group->post('/demora', \PedidoController::class . ':VerDemora');
  });

//ENCUESTA
$app->group('/encuesta', function (RouteCollectorProxy $group)
{
    $group->get('/traer', \EncuestaController::class . ':TraerTodos');
    $group->get('/mejoresComentarios', \EncuestaController::class . ':MejoresComentarios')->add(MWAccesos::class . ':EsSocio');
    $group->get('/{id}', \EncuestaController::class . ':TraerUno');
    $group->post('/alta', \EncuestaController::class . ':CargarUno');
  });

//GUARDAR ARCHIVOS
$app->group('/pdf', function (RouteCollectorProxy $group)
{
    $group->get('/guardarUsuario', \ManejoArchivos::class . ':DescargaUsuarios');
    $group->get('/guardarProducto', \ManejoArchivos::class . ':DescargaProductos');
    $group->get('/guardarEncuesta', \ManejoArchivos::class . ':DescargaEncuesta');
    $group->get('/guardarCSV', \ManejoArchivos::class . ':GuardarCSV');
    $group->get('/guardarCSVEnc', \ManejoArchivos::class . ':GuardarCSVEncuesta');
    $group->get('/leerEncuesta', \ManejoArchivos::class . ':LeerEncuesta');
    $group->get('/guardarCSVBD', \ManejoArchivos::class . ':GuardarEnBd');

  });

//ESTADISTICAS
$app->group('/estadisticas', function (RouteCollectorProxy $group)
{
    $group->get('/tarde', \ChangelogController::class . ':TraerTarde')->add(MWAccesos::class . ':EsSocio');
    $group->get('/temprano', \ChangelogController::class . ':TraerATiempo')->add(MWAccesos::class . ':EsSocio');
    $group->get('/{tipo}', \EstadisticasController::class . ':Estadisticas')->add(MWAccesos::class . ':EsSocio');
  });

$app->run();
