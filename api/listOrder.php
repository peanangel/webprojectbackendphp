<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/listorder', function (Request $request, Response $response) {
    $conn = $GLOBALS['dbconnect'];
    
    $sql = 'select id,cusid,customer.name as username ,date,total,status.status,iorder.name as name,address,iorder.phone,email 
    from iorder ,status,customer 
    where customer.cid = iorder.cusid 
    and iorder.status = status.ids 
    and iorder.status not in (3) 
    order by iorder.id DESC';
    $stmt = $conn->prepare($sql);
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


// $app->get('/listorder/{cusid}', function (Request $request, Response $response) {
//     $conn = $GLOBALS['dbconnect'];
//     $sql = 'select id,cusid,customer.name as username ,date,total,status.status,iorder.name as name,address,iorder.phone from iorder ,status,customer where customer.cid = iorder.cusid and iorder.status = status.ids  and status.ids not in(3) and cusid = ?';
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param('i',$args['cusid']);
//     $stmt->execute();

//     $result = $stmt->get_result();
//     $data = array();
//     while($row = $result->fetch_assoc()){
//         array_push($data,$row);
//     }
//     $json = json_encode($data);

//     $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
//     return $response
//         ->withHeader('Content-Type', 'application/json; charset=utf-8')
//         ->withStatus(200);
// });

$app->get('/listorder/{id}', function (Request $request, Response $response, $args) {
    $conn = $GLOBALS['dbconnect'];
    $sql = 'select id,cusid,customer.name as username ,date,total,status.status,iorder.name as name,address,iorder.phone from iorder ,status,customer where customer.cid = iorder.cusid and iorder.status = status.ids  and status.ids not in(3) and cusid = ? order by date DESC';
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

$app->get('/iorder/select/{idx}', function (Request $request, Response $response, $args) {
    $idx = $args['idx'];
    $conn = $GLOBALS['dbconnect'];
    $sql = 'select id,cusid,customer.name as username ,date,total,status.status,iorder.name,iorder.phone,iorder.address,email
    from customer ,iorder,status,orderamount,food
    where	customer.cid = iorder.cusid
    and		iorder.id = orderamount.orderid
    and		orderamount.foodid = food.foodid
    and		iorder.status = status.ids
    and     iorder.id = ?
    group by iorder.id';
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

$app->get('/iorder/listselect/{idx}', function (Request $request, Response $response, $args) {
    $idx = $args['idx'];
    $conn = $GLOBALS['dbconnect'];
    $sql = 'select	food.foodid,orderamount.orderid,food.image as image,food.name as name,food.price as price,orderamount.amount as amount
    from food,iorder,orderamount
    where iorder.id = orderamount.orderid
    and 	orderamount.foodid = food.foodid
    and orderamount.orderid =?';
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