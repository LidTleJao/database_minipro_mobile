<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/list_likes', function (Request $request, Response $response, $args){
    // $name = '%'.$args['name'].'%';
    $conn = $GLOBALS['dbconn'];

    $sql = "select * from list_like ";
    $result = $conn->query($sql);
    
    $data = array();
    while($row = $result->fetch_assoc()){
        array_push($data,$row);
    }
    $json = json_encode($data);
    $response->getBody()->write($json);
    return $response->withHeader('Content-Type', 'application/json');
});