<?php
ini_set('display_error',1);
error_reporting(E_ALL);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;



//all
$app->get('/typefood', function (Request $request, Response $response,array $args) {
    $conn = $GLOBALS['dbconnect'];
    $sql = 'select * from typefood';
    $result = $conn->query($sql);
    $data = array();
    while($row = $result->fetch_assoc()){
        array_push($data,$row);
    }
    $json = json_encode($data);
    $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    return $response
        ->withHeader('Content-Type', 'application/json; charset=utf-8')
        ->withStatus(200);
});

//search name
$app->get('/typefood/type/{keyword}', function (Request $request, Response $response,array $args) {
    $conn = $GLOBALS['dbconnect'];
    $keyword = $args['keyword'];
    $stmt = $conn->prepare("SELECT * FROM typefood 
    WHERE CONCAT(type) LIKE ?");
    $param = "%" . $keyword . "%";
    $stmt->bind_param('s', $param);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = array();
    while($row = $result->fetch_assoc()){
        array_push($data,$row);
    }
    $json = json_encode($data);

    $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    return $response
        ->withHeader('Content-Type', 'application/json; charset=utf-8')
        ->withStatus(200);
});
//serch id
$app->get('/typefood/{id}', function (Request $request, Response $response, $args) {
    $conn = $GLOBALS['dbconnect'];
    $sql = 'select * from typefood where typeid = ?';
    $stmt = $conn->prepare($sql);

    $stmt->bind_param('i', $args['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = array();
    foreach ($result as $row) {
        array_push($data, $row);
    }

    $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    return $response
        ->withHeader('Content-Type', 'application/json; charset=utf-8')
        ->withStatus(200);
});
//insert
$app->post('/typefood', function (Request $request, Response $response,array $args) {
    $body = $request->getBody();
    $bodyArray = json_decode($body,true);

    $conn = $GLOBALS['dbconnect'];
    
    // $stmt = $conn->prepare("INSERT INTO `typefood`(`typeid`, `type`) VALUES (NULL,?)");
    $sql='insert into typefood (typeid,type) values (null,?)';
    $stmt = $conn->prepare($sql);
   
    $stmt->bind_param('s',$bodyArray['type']);
    $stmt->execute();
    if ($affected > 0) {

        $data = ["affected_rows" => $affected, "last_idx" => $conn->insert_id];
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
});
$app->put('/typefood/{id}', function (Request $request, Response $response, $args) {
    $json = $request->getBody();
    $jsonData = json_decode($json, true);
    $id = $args['id'];
    $conn = $GLOBALS['dbconnect'];
    $sql = 'update typefood set type=? where typeid = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $jsonData['type'], $id);
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
$app->delete('/typefood/{id}', function (Request $request, Response $response, $args) {
    $id = $args['id'];
    $conn = $GLOBALS['dbconnect'];
    $sql = 'delete from typefood where typeid = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
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

