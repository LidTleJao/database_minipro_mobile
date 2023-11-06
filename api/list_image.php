<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/list_images', function (Request $request, Response $response, $args){
    // $name = '%'.$args['name'].'%';
    $conn = $GLOBALS['dbconn'];

    $sql = "select list_image.id, Account.id as id_account, Account.name, Account.image
            , list_image.url, list_image.detail,type_image.id as id_type, type_image.name_type
            from list_image 
            inner join Account
            on    list_image.id_account = Account.id
            inner join type_image
            on    list_image.type_image = type_image.id
            "
            ;
    // $sql = "SELECT * FROM list_image ";
    $result = $conn->query($sql);
    
    $data = array();
    while($row = $result->fetch_assoc()){
        array_push($data,$row);
    }
    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
    $response->getBody()->write($json);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/list_image/id_img/{id}', function (Request $request, Response $response, $args){
    $iduser = $args['id'];
    $conn = $GLOBALS['dbconn'];

    $sql = "select * from list_image WHERE id = ?";
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

$app->get('/list_image/id_acc/{id_account}', function (Request $request, Response $response, $args){
    $iduser = $args['id_account'];
    $conn = $GLOBALS['dbconn'];

    $sql = "select list_image.id, Account.name, Account.image
            , list_image.url, list_image.detail, type_image.name_type
            from list_image 
            inner join Account
            on    list_image.id_account = Account.id
            inner join type_image
            on    list_image.type_image = type_image.id 
            WHERE id_account = ?";
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

$app->post('/list_image', function (Request $request, Response $response, $args) {
    $json = $request->getBody();
    $jsonData = json_decode($json, true);

    $conn = $GLOBALS['dbconn'];
    $sql = 'insert into list_image (id_account, url, detail, type_image) values (?, ?, ?, ?)';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('issi', $jsonData['id_account'], $jsonData['url'], $jsonData['detail'], $jsonData['type_image']);
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

$app->put('/list_image/{id}', function (Request $request, Response $response, $args) {
    $json = $request->getBody();
    $jsonData = json_decode($json, true);
    $id = $args['id'];
    $conn = $GLOBALS['dbconn'];
    $sql = 'update list_image set detail=? where id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sii', $jsonData['detail'], $id);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    if ($affected > 0) {
        $data = ["affected_rows" => $affected];
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
});