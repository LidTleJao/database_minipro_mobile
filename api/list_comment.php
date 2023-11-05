<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/list_comments', function (Request $request, Response $response, $args){
    // $name = '%'.$args['name'].'%';
    $conn = $GLOBALS['dbconn'];

    $sql = "select list_comment.id_image, list_comment.id_account, Account.name, Account.image, list_comment.description 
            from list_comment 
            inner join Account
            on    list_comment.id_account = Account.id ";
    $result = $conn->query($sql);
    
    $data = array();
    while($row = $result->fetch_assoc()){
        array_push($data,$row);
    }
    $json = json_encode($data);
    $response->getBody()->write($json);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/list_comment/id_image/{id_image}', function (Request $request, Response $response, $args){
    $iduser = $args['id_image'];
    $conn = $GLOBALS['dbconn'];

    $sql = "select list_comment.id_image, list_comment.id_account, Account.name, Account.image, list_comment.description
            from list_comment 
            inner join Account
            on    list_comment.id_account = Account.id
            WHERE id_image = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i",$iduser);

    $stmt->execute();
    $result = $stmt->get_result();
    $data = array();
    while($row = $result->fetch_assoc()){
        array_push($data,$row);
    }
    $json = json_encode($data);
    $response->getBody()->write($json);
    return $response->withHeader('Content-Type', 'application/json');
    
});

$app->get('/list_comment/id_image/count/{id_image}', function (Request $request, Response $response, $args){
    $iduser = $args['id_image'];
    $conn = $GLOBALS['dbconn'];

    $sql = "select count(id_account)
            from list_comment 
            inner join Account
            on    list_comment.id_account = Account.id
            WHERE id_image = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i",$iduser);

    $stmt->execute();
    $result = $stmt->get_result();
    $data = array();
    while($row = $result->fetch_assoc()){
        array_push($data,$row);
    }
    $json = json_encode($data);
    $response->getBody()->write($json);
    return $response->withHeader('Content-Type', 'application/json');
    
});