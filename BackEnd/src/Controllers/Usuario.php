<?php
namespace App\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class Usuario extends BaseBD{

    public function consultarTodos(Request $request, Response $response, $args) {
        $indice= $args ['indice'];
        $limite= $args ['limite'];
        $this->iniciar('Usuario','id');
        $datos= $this->todos($indice,$limite);
        $status= sizeof($datos) > 0 ? 200 : 204;
        $response->getBody()->write(json_encode($datos));
        return $response->withHeader('Content-Type','application/json')->withStatus($status);
    }

    public function buscarCodigo(Request $request, Response $response, $args) {
        $codigo = $args['codigo'];
        $this->iniciar('Usuario','id');
        $datos= $this->buscar($codigo);
        $status= sizeof($datos) > 0 ? 200 : 404;
        $response->getBody()->write(json_encode($datos));
        return $response->withHeader('Content-Type','application/json')->withStatus($status);
    }
    
    public function nuevo(Request $request, Response $response, $args) {
        $this->iniciar('Usuario','id');
        $body= json_decode($request->getBody());
        $opciones=[
            'cost'=>11
            //'salt'=> bin2hex(random_bytes(16))
        ];
        $body->passw=password_hash($body->passw, PASSWORD_BCRYPT, $opciones);
        $datos= $this->guardar($body);
        $status= $datos[0] > 0 ? 409 : 201;
        $response->getBody()->write(json_encode($body));
        return $response
        ->withHeader('Content-Type','application/json')->withStatus($status);
    }
    
    public function filtro(Request $request, Response $response, $args) {
        $campos = explode('&',$args['campos']);
        $valores = explode('&',$args['valores']);
        $this->iniciar('Usuario','id');
        $datos= $this->filtrar($campos,$valores);
        $status= sizeof($datos) > 0 ? 200 : 404;
        $response->getBody()->write(json_encode($datos));
        return $response->withHeader('Content-Type','application/json')->withStatus($status);
    }
    
    public function modificar(Request $request, Response $response, $args) {
        $body= json_decode($request->getBody());
        $codigo= $args['codigo'];
        $this->iniciar('Usuario','id');
        $datos= $this->guardar($body, $codigo);
        $status= $datos[0] == 0 ? 404 : 200;
        $response->getBody()->write(json_encode($body));
        return $response
        ->withHeader('Content-Type','application/json')->withStatus($status);
    }
    
    public function eliminar(Request $request, Response $response, $args) {
        $this->iniciar('Usuario','id');
        $codigo=$args['codigo'];
        $datos= $this->eliminarbd($codigo);
        $status= $datos[0] == 0 ? 404 : 200;
        $response->getBody()->write(json_encode($body));
        return $response
        ->withHeader('Content-Type','application/json')->withStatus($status);
    }
}