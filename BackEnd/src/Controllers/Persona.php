<?php
namespace App\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class Persona extends BaseBD{

    public function consultarTodos(Request $request, Response $response, array $args){
        $indice= $args ['indice'];
        $limite= $args ['limite'];
        $this->iniciar('Persona','id');
        $datos= $this->todos($indice,$limite);
        $status= sizeof($datos) > 0 ? 200 : 204;
        $response->getBody()->write(json_encode($datos));
        return $response->withHeader('Content-Type','application/json')->withStatus($status);
    }

    public function buscarCodigo(Request $request, Response $response, $args) {
        $codigo = $args['codigo'];
        $this->iniciar('Persona','id');
        $datos= $this->buscar($codigo);
        $status= sizeof($datos) > 0 ? 200 : 404;
        $response->getBody()->write(json_encode($datos));
        //$response->getBody()->write("Obteniendo curso con el codigo $codigo");
        return $response->withHeader('Content-Type','application/json')->withStatus($status);
    }

    
    public function nuevo(Request $request, Response $response, $args) {
        $this->iniciar('Persona','id');
        $body= json_decode($request->getBody());
        $datos= $this->guardar($body);
        $status= $datos[0] > 0 ? 409 : 201;
        $response->getBody()->write(json_encode($body));
        return $response
        ->withHeader('Content-Type','application/json')->withStatus($status);
    }
    
    public function filtro(Request $request, Response $response, $args) {
        $this->iniciar('Persona','id');
        $campos = explode('&',$args['campos']);
        $valores = explode('&',$args['valores']);
        $datos= $this->filtrar($campos,$valores);
        $status= sizeof($datos) > 0 ? 200 : 404;
        $response->getBody()->write(json_encode($datos));
        //$response->getBody()->write("Obteniendo curso con el codigo $codigo");
        return $response->withHeader('Content-Type','application/json')->withStatus($status);
    
    }
    
    public function modificar(Request $request, Response $response, $args) {
        $this->iniciar('Persona','id');
        $body= json_decode($request->getBody());
        $codigo= $args['codigo'];
        $datos= $this->guardar($body, $codigo);
        $status= $datos[0] == 0 ? 404 : 200;
        $response->getBody()->write(json_encode($body));
        return $response
        ->withHeader('Content-Type','application/json')->withStatus($status);
    }
    
    public function eliminar(Request $request, Response $response, $args) {
        $this->iniciar('Persona','id');
        $codigo=$args['codigo'];
        $datos= $this->eliminarbd($codigo);
        $status= $datos[0] == 0 ? 404 : 200;
        $response->getBody()->write(json_encode($body));
        return $response
        ->withHeader('Content-Type','application/json')->withStatus($status);
    }

    



    
/*
    public function consultarTodos(Request $request, Response $response, $args) {
        $indice= $args ['indice'];
        $limite= $args ['limite'];
        $response->getBody()->write("Obteniendo datos de persona desde $indice hasta $limite");
        return $response;
    }

    public function buscarCodigo(Request $request, Response $response, $args) {
        $codigo = $args['codigo'];
        $response->getBody()->write("Obteniendo persona con el codigo $codigo");
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
        $response->getBody()->write("Se elimino la persona con el codigo: $codigo");
        return $response->withStatus(200);
    }*/

}