<?php
namespace App\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \Firebase\JWT\JWT;
use PDO;

class Seguridad extends BaseBD{

    private function identificarse($usuario, $passw){
        $this->iniciar('Usuario', 'id');
        $datos= $this->buscar($usuario);
        $resultado= (sizeof($datos) > 0 && (password_verify($passw, $datos[0]->passw))) ? $datos : null;
        return $resultado;
    }


    private function generarTokens($id, $rol){
        $payload =[
            'iat' => time(),
            'iss' => $_SERVER['SERVER_NAME'],
            'exp' => time()+(1000),
            'sub' => $id,
            'role'=> $rol
        ];
        $payloadRF =[
            'iat' => time(),
            'iss' => $_SERVER['SERVER_NAME'],
            'sub' => $id,
        ];

        //$token= JWT::encode($payload, getenv('CLAVE'), 'HS256');
        $token= JWT::encode($payload, 'jdfnvojefnvoenfvekjnvkjefnekjfnvjkefnvjefnvenfj', 'HS256');
        $refreshToken= JWT::encode($payloadRF, 'jdfnvojefnvoenfvekjnvkjefnekjfnvjkefnvjefnvenfjk', 'HS256');
        $datos= $this->modificarToken($id,$refreshToken);
        $resultado = [
            'token' => $token,
            'refreshToken'=>$refreshToken
        ];

        return $resultado;
    }

    public function iniciarSesion(Request $request, Response $response, $args) {
        $body= json_decode($request->getBody());
        $datos= $this->identificarse($body->id, $body->passw);
        if($datos){
            $resultado= $this->generarTokens($body->id, $datos[0]->rol);
        }

        if(isset($resultado)){
            $status=200;
            $response->getBody()->write(json_encode($resultado));
        }else{
            $status=401;
        }
        
        return $response
        ->withHeader('Content-Type','application/json')->withStatus($status);
    }

    public function cerrarSesion(Request $request, Response $response, $args) {
        $body= json_decode($request->getBody());
        $datos= $this->modificarToken($body->id);
        $response->getBody()->write(json_encode($body));
        return $response
        ->withHeader('Content-Type','application/json')->withStatus(200);
    }

    public function resetPass(Request $request, Response $response, $args) {
        $body= json_decode($request->getBody());
        //$datos= $this->identificarse($body->id, $body->passwV);
        
            $opciones=[
                'cost'=>11
            ];
            $hash=password_hash($body->id, PASSWORD_BCRYPT, $opciones);
            $sql= 'select cambiarPasswUsuario(:id, :passw);';
            $conexion = $this->container->get('bd');
            $consulta= $conexion->prepare($sql);

            $consulta->bindValue(':id', $body->id, PDO::PARAM_STR);
            $consulta->bindValue(':passw', $hash, PDO::PARAM_STR);

            $status= $consulta->execute() ? 200 : 500;

        return $response->withStatus($status);
    }


    public function cambioContraseña(Request $request, Response $response, $args) {
        $body= json_decode($request->getBody());
        $datos= $this->identificarse($body->id, $body->passwV);
        if($datos){
            $opciones=[
                'cost'=>11
            ];
            $passwN=password_hash($body->passwN, PASSWORD_BCRYPT, $opciones);
            $sql= 'select cambiarPasswUsuario(:id, :passw);';
            $conexion = $this->container->get('bd');
            $consulta= $conexion->prepare($sql);
            $consulta->bindValue(':id', $body->id, PDO::PARAM_STR);
            $consulta->bindValue(':passw', $passwN, PDO::PARAM_STR);
            $status= $consulta->execute() ? 200 : 500;
        }else {
            $status= 401;
        }
        return $response->withStatus($status);
    }



    public function refrescarToken(Request $request, Response $response, $args) {
        $body= json_decode($request->getBody());
        $datos= $this->validarRefresco($body->id, $body->rfT);

        if(sizeof($datos) > 0){
            $resultado= $this->generarTokens($body->id, $datos[0]->rol);
        }

        if(isset($resultado)){
            $status=200;
            $response->getBody()->write(json_encode($resultado));
        }else{
            $status=401;
        }

        return $response
        ->withHeader('Content-Type','application/json')->withStatus($status);
    }

    


}



//KennyWebSite
//!gzqfPx#Ksl(&UfyKWfw