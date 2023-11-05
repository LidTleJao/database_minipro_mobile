<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/type_images', function (Request $request, Response $response, $args){
    // $name = '%'.$args['name'].'%';
    $conn = $GLOBALS['dbconn'];

    $sql = "select * from type_image ";
    $result = $conn->query($sql);
    
    $data = array();
    while($row = $result->fetch_assoc()){
        array_push($data,$row);
    }
    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
    $response->getBody()->write($json);
    return $response->withHeader('Content-Type', 'application/json');
});