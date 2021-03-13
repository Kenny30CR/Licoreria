<?php
namespace App\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Filtro extends BaseBD{

    public function cantRegs(Request $request, Response $response, array $args){
        $params = $request->getQueryParams();
        $tabla=$request->getAttribute('tabla');
        $this->iniciar($tabla,'Codigo');
        $datos= $this->numRegs($params);
        $response->getBody()->write(json_encode($datos));
        return $response->withHeader('Content-Type','application/json')->withStatus(200);
    }

    public function ejecutar(Request $request, Response $response, array $args){
        $params = $request->getQueryParams();
        $tabla=$request->getAttribute('tabla');
        $pag=$request->getAttribute('pag');
        $lim=$request->getAttribute('lim');
        $this->iniciar($tabla,'Codigo');
        $datos= $this->filtrar($pag,$lim,$params);
        $response->getBody()->write(json_encode($datos));
        return $response->withHeader('Content-Type','application/json')->withStatus(200);
    }
}