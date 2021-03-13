<?php
use Slim\Factory\AppFactory;

require __DIR__ . '/../../vendor/autoload.php';
//require '../env.php';

$auxiliar = new \DI\Container;
AppFactory::setContainer($auxiliar);

$app = AppFactory::create();
$app->addErrorMiddleware(true,true,true);
$app->add(new Tuupola\Middleware\JwtAuthentication([
    "secure" => false,
    //"secret" => getenv('CLAVE'),
    "secret" => 'jdfnvojefnvoenfvekjnvkjefnekjfnvjkefnvjefnvenfj',
    //"path" => ["/curso", "/usuario","/seguridad"],
    //"ignore" => ["/auth","/curso", "/codigo","/filtrar", "/usuario","/producto", "/cliente", "/venta"]
    "ignore" => ["/auth"]
]));

$container = $app->getContainer();

require 'Routes.php';
require 'Config.php';
require 'Conexion.php';

$app->run();

