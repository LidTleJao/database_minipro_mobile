<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();
$app->setBasePath('/database_minipro_mobile');
require __DIR__ . '/dbconnect.php';
///
require __DIR__ . '/api/account.php';
///
require __DIR__ . '/api/list_image.php';
///
require __DIR__ . '/api/type_image.php';
///
require __DIR__ . '/api/list_comment.php';
///
require __DIR__ . '/api/list_like.php';
///

$app->get('/hello', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

$app->get('/hello/{name}', function (Request $request, Response $response,array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello!!!,$name");
    return $response;
});

$app->post('/hello', function (Request $request, Response $response, $args) {
    $body =$request->getParsedBody();
    $usename = $body['usename'];
    $pwd = $body['password'];
    $response->getBody()->write("Hello World from Post,$usename,$pwd");
    return $response;
});

$app->delete('/hello', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello World from delete");
    return $response;
});

$app->run();