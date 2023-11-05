<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/accounts', function (Request $request, Response $response, $args){
    // $name = '%'.$args['name'].'%';
    $conn = $GLOBALS['dbconn'];

    $sql = "select * from Account ";
    $result = $conn->query($sql);
    
    $data = array();
    while($row = $result->fetch_assoc()){
        array_push($data,$row);
    }
    $json = json_encode($data);
    $response->getBody()->write($json);
    return $response->withHeader('Content-Type', 'application/json');
    
});

$app->get('/account/id/{iduser}', function (Request $request, Response $response, $args){
    $iduser = $args['iduser'];
    $conn = $GLOBALS['dbconn'];

    $sql = "select * from Account WHERE id = ?";
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

$app->post('/account', function (Request $request, Response $response, $args) {
    $json = $request->getBody();
    $jsonData = json_decode($json, true);
    $hash =password_hash($jsonData['password'],PASSWORD_DEFAULT);

    $conn = $GLOBALS['dbconn'];
    $sql = 'insert into Account (image,name,phone,address, gmail, password) values (?, ?, ?, ?, ?, ?)';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssss', $jsonData['image'],$jsonData['name'],$jsonData['phone'],$jsonData['address'], $jsonData['gmail'], $hash);
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

$app->put('/account/{id}', function (Request $request, Response $response, $args) {
    $json = $request->getBody();
    $jsonData = json_decode($json, true);
    $id = $args['id'];
    $conn = $GLOBALS['dbconn'];
    $sql = 'update Account set name=? where id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $jsonData['name'], $id);
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

$app->post('/account/login', function (Request $request, Response $response, $args) {
    $json = $request->getBody();
    $jsonData = json_decode($json, true);

    $conn = $GLOBALS['dbconn'];
    $sql = 'select * from Account where gmail=? ';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s',$jsonData['gmail']);
    $stmt->execute();
    $result =$stmt->get_result();
    if($result->num_rows==1){
        //กระบวนการแปลง รหัสปกติ ให้เป็นการเข้ารหัส
        $row=$result->fetch_assoc();
        $pwdInDb = $row["password"];
       if(password_verify($jsonData['password'],$pwdInDb)){
        // echo "Login Success";
            $response->getBody()->write(json_encode($row, JSON_UNESCAPED_UNICODE));
            return $response
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withStatus(201);
        }
       else{
        $response->getBody()->write(json_encode("รหัสผ่านผิด", JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
            return $response
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withStatus(200);
       } 
    }
    else{
        $response->getBody()->write(json_encode("ชื่อผู้ใช้ผิด", JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
            return $response
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withStatus(200);
    }
});