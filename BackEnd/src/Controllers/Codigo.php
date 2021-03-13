<?php
namespace App\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class Codigo extends BaseBD{

    public function SiguienteCodigo(Request $request, Response $response, array $args){
        $tabla= $args ['tabla'];
        $this->iniciar($tabla,'');
        $datos= $this->sigCodigo($tabla);
        $response->getBody()->write(json_encode($datos));
        return $response->withHeader('Content-Type','application/json')->withStatus(200);
    }
}
