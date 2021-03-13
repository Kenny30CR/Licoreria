<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use slim\Routing\RouteCollectorProxy;
//require __DIR__.'/../Controllers/Curso.php';
//use App\Controllers\Curso;
$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Licorera Backend");
    return $response;
});
//*********************RUTAS DE FILTROS*****************
$app->get('/codigo/{tabla}',  'App\Controllers\codigo:siguienteCodigo');
$app->group('/filtrar/{tabla}',function(RouteCollectorProxy $filtro){
    $filtro->get('/numregs','App\Controllers\Filtro:cantRegs');
    $filtro->get('/{pag}/{lim}','App\Controllers\Filtro:ejecutar');
});
//*********************RUTAS DE CURSOS*****************
$app->group('/curso',function(RouteCollectorProxy $curso){

    //$curso->get('/{indice}/{limite}', Curso::class.':consultarTodos');
    $curso->get('/{indice}/{limite}', 'App\Controllers\Curso:consultarTodos');
    $curso->get('/{codigo}', 'App\Controllers\Curso:buscarCodigo');
    $curso->get('/filtrado/{campos}/{valores}', 'App\Controllers\Curso:filtro');
    $curso->post('', 'App\Controllers\Curso:nuevo');
    $curso->put('/{codigo}', 'App\Controllers\Curso:modificar');
    $curso->delete('/{codigo}', 'App\Controllers\Curso:eliminar');
});
//*****************************RUTAS DE USUARIOS*******************
$app->group('/usuario',function(RouteCollectorProxy $usuario){

    $usuario->get('/{indice}/{limite}', 'App\Controllers\Usuario:consultarTodos');
    $usuario->get('/{codigo}', 'App\Controllers\Usuario:buscarCodigo');
    $usuario->get('/filtrado/{campos}/{valores}', 'App\Controllers\Usuario:filtro');
    $usuario->post('', 'App\Controllers\Usuario:nuevo');
    $usuario->put('/{codigo}', 'App\Controllers\Usuario:modificar');
    $usuario->delete('/{codigo}', 'App\Controllers\Usuario:eliminar');
});
//*****************************RUTAS DE SEGURIDAD*******************
$app->group('/auth',function(RouteCollectorProxy $seguridad){

    $seguridad->post('/iniciar', 'App\Controllers\Seguridad:iniciarSesion');
    $seguridad->post('/cerrar', 'App\Controllers\Seguridad:cerrarSesion');
    $seguridad->post('/cambioContraseña', 'App\Controllers\Seguridad:cambioContraseña');
    $seguridad->post('/resetpass', 'App\Controllers\Seguridad:resetPass');
    $seguridad->post('/refresh', 'App\Controllers\Seguridad:refrescarToken');
});
//*****************************RUTAS DE PERSONAS*******************
$app->group('/producto',function(RouteCollectorProxy $producto){

    $producto->get('/{indice}/{limite}', 'App\Controllers\Producto:consultarTodos');
    $producto->get('/{codigo}', 'App\Controllers\Producto:buscarCodigo');
    $producto->get('/filtrado/{campos}/{valores}', 'App\Controllers\Producto:filtro');
    $producto->post('', 'App\Controllers\Producto:nuevo');
    $producto->put('/{codigo}', 'App\Controllers\Producto:modificar');
    $producto->delete('/{codigo}', 'App\Controllers\Producto:eliminar');
});
//*****************************RUTAS DE INTERES*******************
$app->group('/cliente',function(RouteCollectorProxy $cliente){

    $cliente->get('/{indice}/{limite}', 'App\Controllers\Cliente:consultarTodos');
    $cliente->get('/{codigo}', 'App\Controllers\Cliente:buscarCodigo');
    $cliente->get('/filtrado/{campos}/{valores}', 'App\Controllers\Cliente:filtro');
    $cliente->post('', 'App\Controllers\Cliente:nuevo');
    $cliente->put('/{codigo}', 'App\Controllers\Cliente:modificar');
    $cliente->delete('/{codigo}', 'App\Controllers\Cliente:eliminar');
});
//*****************************RUTAS DE PRESELECCION*******************
$app->group('/venta',function(RouteCollectorProxy $venta){

    $venta->get('/{indice}/{limite}', 'App\Controllers\Venta:consultarTodos');
    $venta->get('/{codigo}', 'App\Controllers\Venta:buscarCodigo');
    $venta->get('/filtrado/{campos}/{valores}', 'App\Controllers\Venta:filtro');
    $venta->post('', 'App\Controllers\Venta:nuevo');
    $venta->put('/{codigo}', 'App\Controllers\Venta:modificar');
    $venta->delete('/{codigo}', 'App\Controllers\Venta:eliminar');
});

