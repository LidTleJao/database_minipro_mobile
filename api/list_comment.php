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
    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
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
    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
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

$app->post('/list_comment', function (Request $request, Response $response, $args) {
    $json = $request->getBody();
    $jsonData = json_decode($json, true);

    $conn = $GLOBALS['dbconn'];
    $sql = 'insert into list_comment (id_image,id_account, description) values (?, ?, ?)';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iis',$jsonData['id_image'], $jsonData['id_account'],  $jsonData['description']);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    if ($affected > 0) {

        $data = ["affected_rows" => $affected, "last_idx" => $conn->insert_id];
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
});