<?php
namespace App\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class Preseleccion{

    public function consultarTodos(Request $request, Response $response, $args) {
        $indice= $args ['indice'];
        $limite= $args ['limite'];
        $response->getBody()->write("Obteniendo datos de preseleccion desde $indice hasta $limite");
        return $response;
    }

    public function buscarCodigo(Request $request, Response $response, $args) {
        $codigo = $args['codigo'];
        $response->getBody()->write("Obteniendo preseleccion con el codigo $codigo");
        return $response;
    }
    
    public function guardar(Request $request, Response $response, $args) {
        $body= json_decode($request->getBody());
        $body->Estado="Guardado";
        //aca se manipulan los datos, se pueden enviar a guardar
        
        $response->getBody()->write(json_encode($body));
        return $response
        ->withHeader('Content-Type','application/json')->withStatus(200);
    }
    
    public function filtrar(Request $request, Response $response, $args) {
        $body= json_decode($request->getBody());
        //aca se manipulan los datos, se pueden enviar a guardar
        
        $body->Estado="Filtrado";
        $response->getBody()->write(json_encode($body));
        return $response
        ->withHeader('Content-Type','application/json')->withStatus(201);
    }
    
    public function modificar(Request $request, Response $response, $args) {
        $body= json_decode($request->getBody());
        $body->Estado="Modificado";
        //aca se manipulan los datos, se pueden enviar a guardar
        
        $response->getBody()->write(json_encode($body));
        return $response
        ->withHeader('Content-Type','application/json')->withStatus(201);
    }
    
    public function eliminar(Request $request, Response $response, $args) {
        $codigo=$args['codigo'];
        $response->getBody()->write("Se elimino la preseleccion $codigo");
        return $response->withStatus(200);
    }
}