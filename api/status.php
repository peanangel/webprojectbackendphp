<?php
ini_set('display_error',1);
error_reporting(E_ALL);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


$app->get('/status', function (Request $request, Response $response) {
    $conn = $GLOBALS['dbconnect'];
    $sql = 'select * from status where ids not in (3)';
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
$app->get('/order/status/{status}', function (Request $request, Response $response) {
    $idx = '%'.$args['status'].'%';
    $conn = $GLOBALS['dbconnect'];
    $sql = 'select * from status where status like ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $idx);
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

$app->get('/status/[{id}]', function (Request $request, Response $response,array $args) {
    $idx = $args['id'];
    


    $conn = $GLOBALS['dbconnect'];
    
    $sql = 'select id,cusid,customer.name as username ,date,total,status.status,iorder.name as name,address,iorder.phone,email 
    from iorder ,status,customer 
    where customer.cid = iorder.cusid 
    and iorder.status = status.ids 
    and status.ids = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $idx);
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




// search status name
$app->get('/food/status/{keyword}', function (Request $request, Response $response,array $args) {
    $conn = $GLOBALS['dbconnect'];
    $keyword = $args['keyword'];
    $stmt = $conn->prepare("select customer.name as name ,iorder.date,iorder.note,iorder.address,status.status,orderamount.amount,food.name as list,sum(orderamount.amount*food.price) as total
    from customer ,iorder,status,orderamount,food
    where	customer.cid = iorder.cusid
    and		iorder.id = orderamount.orderid
    and		orderamount.foodid = food.foodid
    and		iorder.status = status.ids
    and     status.status like ?
    group by iorder.id ,orderamount.foodid");
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
//seach id status
// $app->get('/food/{id}', function (Request $request, Response $response,array $args) {
//     $conn = $GLOBALS['dbconnect'];
//     $id = $args['id'];
//     $stmt = $conn->prepare('select customer.name as name ,iorder.date,iorder.note,iorder.address,status.status,orderamount.amount,food.name as list,sum(orderamount.amount*food.price) as total
//     from customer ,iorder,status,orderamount,food
//     where	customer.cid = iorder.cusid
//     and		iorder.id = orderamount.orderid
//     and		orderamount.foodid = food.foodid
//     and		iorder.status = status.ids
//     and     status.ids = ?
//     group by iorder.id ,orderamount.foodid');
    
//     $stmt->bind_param('i', $id);
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

// update status
$app->put('/iorder/status/{id}', function (Request $request, Response $response,array $args) {
    $id = $args['id'];
    $body = $request->getBody();
    $bodyArray = json_decode($body,true);

    $conn = $GLOBALS['dbconnect'];
    $stmt = $conn->prepare("UPDATE `iorder` SET `status`=? where id=?");
    $stmt->bind_param('ii',$bodyArray['id'],$id);
    $stmt->execute();
    $result = $stmt->affected_rows;
    $response->getBody()->write("");
    // return $response->withHeader('Content-Type','application/json');
    $affected = $stmt->affected_rows;
    if ($affected > 0) {
        $data = true;
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
});
//all 
// $app->get('/status', function (Request $request, Response $response,array $args) {
//     $conn = $GLOBALS['dbconnect'];
//     $sql = 'select customer.name as name ,iorder.date,iorder.note,iorder.address,status.status,orderamount.amount,food.name as list,sum(orderamount.amount*food.price) as total
//     from customer ,iorder,status,orderamount,food
//     where	customer.cid = iorder.cusid
//     and		iorder.id = orderamount.orderid
//     and		orderamount.foodid = food.foodid
//     and		iorder.status = status.ids
//     and     status.ids = ?';
//     $result = $conn->query($sql);
//     $data = array();
//     while($row = $result->fetch_assoc()){
//         array_push($data,$row);
//     }
//     $json = json_encode($data);
//     $response->getBody()->write($json);
//     return $response->withHeader('Content-Type','application/json');
// });



$app->post('/status/cus/[{id}]', function (Request $request, Response $response,array $args) {
    $idx = $args['id'];
    $body = $request->getBody();
    $bodyArray = json_decode($body,true);


    $conn = $GLOBALS['dbconnect'];
    
    $sql = 'select id,cusid,customer.name as username ,date,total,status.status,iorder.name as name,address,iorder.phone,email  
    from iorder ,status,customer 
    where customer.cid = iorder.cusid 
    and iorder.status = status.ids 
    and status.status like ?
    and cusid = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $idx,$bodyArray['cusid']);
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


$app->get('/statusNO1/[{id}]', function (Request $request, Response $response,array $args) {
    $idx = $args['id'];



    $conn = $GLOBALS['dbconnect'];
    
    $sql = 'select id,cusid,customer.name as username ,date,total,status.status,iorder.name as name,address,iorder.phone,email 
    from iorder ,status,customer 
    where customer.cid = iorder.cusid 
    and iorder.status = status.ids 
    and status.ids = 1
    and iorder.cusid = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $idx);
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