<?php
ini_set('display_error',1);
error_reporting(E_ALL);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
//all
$app->get('/food', function (Request $request, Response $response,array $args) {
    $conn = $GLOBALS['dbconnect'];
    $sql = 'select food.foodid,typefood.type, food.name,food.price,food.image,food.description  from food inner join typefood on food.type = typefood.typeid order by food.foodid';
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

//search food name
$app->get('/food/name/{keyword}', function (Request $request, Response $response,array $args) {
    $conn = $GLOBALS['dbconnect'];
    $keyword = $args['keyword'];
    $stmt = $conn->prepare("SELECT  food.foodid,typefood.type, food.name,food.price,food.image,food.description  from food inner join typefood on food.type = typefood.typeid  
    WHERE concat(food.name) LIKE ?");
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
//seach id
$app->get('/food/{keyword}', function (Request $request, Response $response,array $args) {
    $conn = $GLOBALS['dbconnect'];
    $keyword = $args['keyword'];
    $stmt = $conn->prepare("SELECT  food.foodid,typefood.type, food.name,food.price,food.image,food.description  from food inner join typefood on food.type = typefood.typeid  
    WHERE foodid = ?");
    $param = "%" . $keyword . "%";
    $stmt->bind_param('i', $keyword);
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
//insert
$app->post('/food', function (Request $request, Response $response,array $args) {
    $body = $request->getBody();
    $bodyArray = json_decode($body,true);
    
    $conn = $GLOBALS['dbconnect'];
   
    $stmt = $conn->prepare("INSERT INTO food (foodid, type, name, price, image,description) VALUES (NULL,?,?,?,?,?)");
   
    $stmt->bind_param('isiss', $bodyArray['type'],$bodyArray['name'], $bodyArray['price'], $bodyArray['image'],$bodyArray['description']);

    $stmt->execute();
    $affected = $stmt->affected_rows;
    if ($affected > 0) {

        // $data = ["affected_rows" => $affected, "last_idx" => $conn->insert_id];
        $data = true;
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
});
//update
$app->put('/food/[{foodid}]', function (Request $request, Response $response,array $args) {
    $foodid = $args['foodid'];
    $body = $request->getBody();
    $bodyArray = json_decode($body,true);

    $conn = $GLOBALS['dbconnect'];
    $stmt = $conn->prepare("UPDATE `food` SET `type`=?,`name`=?,`price`=?,`image`=?,description =? WHERE foodid =?");
    $stmt->bind_param('isissi',$bodyArray['type'], $bodyArray['name'], $bodyArray['price'], $bodyArray['image'],$bodyArray['description'],$foodid);
    $stmt->execute();
    $result = $stmt->affected_rows;
    $response->getBody()->write($result."");
    $affected = $stmt->affected_rows;
    if ($affected > 0) {
        $data = ["affected_rows" => $affected];
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
});

//delete
$app->delete('/food/[{foodid}]', function (Request $request, Response $response,array $args) {
    $foodid = $args['foodid'];
    $body = $request->getBody();
    

    $conn = $GLOBALS['dbconnect'];
    $stmt = $conn->prepare("DELETE FROM `food` WHERE foodid = ?");
    $stmt->bind_param('i',$foodid);
    $stmt->execute();
    $result = $stmt->affected_rows;
    // $response->getBody()->write($result."");
    $affected = $stmt->affected_rows;
    if ($affected > 0) {
        $data = true;
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }else{
         $data = false;
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
});

//////////////////type
// food type
$app->get('/food/type/[{type}]', function (Request $request, Response $response, $args) {
    $conn = $GLOBALS['dbconnect'];

    
    $sql = 'select food.foodid,typefood.type, food.name,food.price,food.image,food.description  from food inner join typefood on food.type = typefood.typeid where typefood.type like ?';
   
    $stmt = $conn->prepare($sql);
    $name = '%' . $args['type'] . '%';
    $stmt->bind_param('s', $name);
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
//search
// $app->get('/typefood', function (Request $request, Response $response) {
//     $conn = $GLOBALS['dbconnect'];
//     $sql = 'select food.foodid,typefood.type, food.name,food.price,food.image,food.description  from food inner join typefood on food.type = typefood.typeid order by typefood.typeid';
//     $stmt = $conn->prepare($sql);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $data = array();
//     foreach ($result as $row) {
//         array_push($data, $row);
//     }

//     $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
//     return $response
//         ->withHeader('Content-Type', 'application/json; charset=utf-8')
//         ->withStatus(200);
// });
//search name

//search id
$app->get('/food/type/id/{id}', function (Request $request, Response $response, $args) {
    $idx = $args['id'];
    $conn = $GLOBALS['dbconnect'];
    $sql = 'select food.foodid,typefood.type, food.name,food.price,food.image,food.description  from food inner join typefood on food.type = typefood.typeid where food.type = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $idx);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = [];
    while ($row = $result->fetch_assoc()) {
        array_push($data, $row);
    }
    $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    return $response
        ->withHeader('Content-Type', 'application/json; charset=utf-8')
        ->withStatus(200);
});
