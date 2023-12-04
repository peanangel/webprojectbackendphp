<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


$app->get('/order', function (Request $request, Response $response) {
    $conn = $GLOBALS['dbconnect'];
    
    $sql = 'select iorder.id ,customer.name as name ,iorder.date,iorder.address,status.status,orderamount.amount,food.name as list,sum(orderamount.amount*food.price) as total
    from customer ,iorder,status,orderamount,food
    where	customer.cid = iorder.cusid
    and		iorder.id = orderamount.orderid
    and		orderamount.foodid = food.foodid
    and		iorder.status = status.ids
    group by iorder.id ,orderamount.foodid
    order by date';
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
//search name
$app->get('/order/search/{keyword}', function (Request $request, Response $response, $args) {
    $conn = $GLOBALS['dbconnect'];

    
    $sql = 'select iorder.id ,customer.name as name ,iorder.date,iorder.address,status.status,orderamount.amount,food.name as list,sum(orderamount.amount*food.price) as total
    from customer ,iorder,status,orderamount,food
    where	customer.cid = iorder.cusid
    and		iorder.id = orderamount.orderid
    and		orderamount.foodid = food.foodid
    and		iorder.status = status.ids
    and     CONCAT(customer.name,food.name,status.status) LIKE ?
    group by iorder.id ,orderamount.foodid
    order by date';
    $stmt = $conn->prepare($sql);
    $name = '%' . $args['keyword'] . '%';
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
//serch id
$app->get('/order/{idx}', function (Request $request, Response $response, $args) {
    $idx = $args['idx'];
    $conn = $GLOBALS['dbconnect'];
    $sql = 'select iorder.id ,customer.name as name ,iorder.date,iorder.address,status.status,orderamount.amount,food.name as list,sum(orderamount.amount*food.price) as total
    from customer ,iorder,status,orderamount,food
    where	customer.cid = iorder.cusid
    and		iorder.id = orderamount.orderid
    and		orderamount.foodid = food.foodid
    and		iorder.status = status.ids
    and     iorder.id = ?
    group by iorder.id ,orderamount.foodid
    order by date';
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

//insert order and amount in shop page
$app->post('/order', function (Request $request, Response $response, $args) {
    $json = $request->getBody();
    $jsonData = json_decode($json, true);

    $conn = $GLOBALS['dbconnect'];
    $sql = "INSERT INTO `iorder`( `cusid`,  `address`, `status`) VALUES (?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('isi', $jsonData['cusid'], $jsonData['address'],$jsonData['status']);
    $stmt->execute();
    
    ///insert amount
    $oid = getOrderid($conn,$jsonData["cusid"]);
    $sql = "INSERT INTO `orderamount`(`orderid`, `foodid`, `amount`) VALUES (?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iii', $oid, $jsonData['foodid'], $jsonData['amount']);
    $stmt->execute();

    $total =getTotal($conn,$oid);

    $sql = "UPDATE `iorder` SET `total`=? where id=? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $total,$oid);
    $stmt->execute();

    $affected = $stmt->affected_rows;
   
        $data = ["affected_rows" => $affected];
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    
});

function getTotal($conn,$oid){
    $stmt = $conn->prepare("select sum(amount*food.price) as total from orderamount,food where orderamount.foodid = food.foodid and orderid = ?");
    $stmt->bind_param("i",$oid);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        return $row['total'];
    }else{
        return "cannnot";
    }
}
//get orderid from cusid where status =3
function getOrderid($conn,$cusid){
    $stmt = $conn->prepare("select id from iorder where cusid = ? and status =3");
    $stmt->bind_param("i",$cusid);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        return $row['id'];
    }else{
        return "cannnot";
    }
}
//insert orderamount
$app->post('/orderamount', function (Request $request, Response $response, $args) {
    $json = $request->getBody();
    $jsonData = json_decode($json, true);

    $conn = $GLOBALS['dbconnect'];
    
    $oid = getOrderid($conn,$jsonData['cusid']);
   
    $sql = "INSERT INTO `orderamount`(`orderid`, `foodid`, `amount`) VALUES (?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iii', $oid,$jsonData['foodid'], $jsonData['amount']);
    $stmt->execute();
    $affected = $stmt->affected_rows;

    $total =getTotal($conn,$oid);

    $sql = "UPDATE `iorder` SET `total`=? where id=? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $total,$oid);
    $stmt->execute();
   
        $data = ["affected_rows" => $affected, "last_idx" => $conn->insert_id];
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    
});
// not test pai test 
// update iorder 
$app->put('/order/{id}', function (Request $request, Response $response, $args) {
    $json = $request->getBody();
    $jsonData = json_decode($json, true);
    $id = $args['id'];
    $conn = $GLOBALS['dbconnect'];
    $sql = "UPDATE `iorder` SET `address`=?,`status`=?,name = ? , phone = ? where id=? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sissi',  $jsonData['address'],$jsonData['status'],$jsonData['name'],$jsonData['phone'], $id);
    $stmt->execute();
    
    $affected = $stmt->affected_rows;
    if ($affected > 0) {
        $data = true;
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
});
//update orderamount
$app->put('/orderamount', function (Request $request, Response $response, $args) {
    $json = $request->getBody();
    $jsonData = json_decode($json, true);
    // $id = $args['orderid'];
    $conn = $GLOBALS['dbconnect'];
    $oid = getOrderid($conn,$jsonData['cusid']);
    $sql = "UPDATE `orderamount` SET `amount`=? WHERE orderid=? and foodid =? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iii',$jsonData['amount'],$oid,$jsonData['foodid'] );
    $stmt->execute();
    $total =getTotal($conn,$oid);

    $sql = "UPDATE `iorder` SET `total`=? where id=? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $total,$oid);
    $stmt->execute();

    $affected = $stmt->affected_rows;
    if($affected>0){
    $data = true;
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
        }
});
//delete order
$app->delete('/order/{id}', function (Request $request, Response $response, $args) {
    $id = $args['id'];
    $conn = $GLOBALS['dbconnect'];

    $sql = 'delete from iorder where id = ?';
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


$app->delete('/orderamount/{oid}/{foodid}', function (Request $request, Response $response, $args) {
    $id = $args['oid'];
    $fid = $args['foodid'];
    $conn = $GLOBALS['dbconnect'];

    $sql = 'delete from orderamount where orderid = ? and foodid=?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $id,$fid);
    $stmt->execute();
    $total =getTotal($conn,$args['oid']);

    $sql = "UPDATE `iorder` SET `total`=? where id=? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $total,$args['oid']);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    if ($affected > 0) {
        $data = true;
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
});

//***************************************************************************** */
//when check status = 3 in cart == null
$app->get('/iorderCustomer/{id}', function (Request $request, Response $response, $args) {
    $conn = $GLOBALS['dbconnect'];
    $sql = 'select * from iorder where cusid = ? and status = 3';
    $stmt = $conn->prepare($sql);

    $stmt->bind_param('i', $args['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;
    $data = $num;
    
    $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    return $response
        ->withHeader('Content-Type', 'application/json; charset=utf-8')
        ->withStatus(200);
});

$app->get('/order/amount/{id}', function (Request $request, Response $response, $args) {
    $conn = $GLOBALS['dbconnect'];
    $sql = 'select * from iorder where cusid = ? and status = 3';
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
//**************************************************** */
//when order show in cart page customer
$app->get('/order/listfood/{id}', function (Request $request, Response $response, $args) {
    $conn = $GLOBALS['dbconnect'];
    $oid = getOrderid($conn,$args['id']);
    $sql = 'select	food.foodid,orderamount.orderid,food.image as image,food.name as name,food.price as price,orderamount.amount as amount,total
    from food,iorder,orderamount
    where iorder.id = orderamount.orderid
    and 	orderamount.foodid = food.foodid
    and orderamount.orderid =?';

    $stmt = $conn->prepare($sql);

    $stmt->bind_param('i', $oid);
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



$app->get('/iorderamount/{id}', function (Request $request, Response $response, $args) {
    $conn = $GLOBALS['dbconnect'];
    $orderid = getOrderid($conn,$args['id']);
    $sql = 'select * from orderamount where  orderid = ?';
    $stmt = $conn->prepare($sql);

    $stmt->bind_param('i', $orderid );
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





// $app->get('/orderList/{id}', function (Request $request, Response $response, $args) {
//     $conn = $GLOBALS['dbconnect'];
//     $orderid = getOrderid($conn,$args['id']);
//     $sql = 'select  year(odate),cusid,sum(amount*price)
//     from	iorder,orderamount,goods
//     where	iorder.oid = orderamount.oid
//     and		OrderAmount.gid = goods.gid
//     and		iorder.id = ?';
//     $stmt = $conn->prepare($sql);

//     $stmt->bind_param('i', $orderid );
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

$app->put('/ordertotal/{id}', function (Request $request, Response $response, $args) {
    $json = $request->getBody();
    $jsonData = json_decode($json, true);
    $id = $args['id'];
    $conn = $GLOBALS['dbconnect'];
    $sql = "UPDATE `iorder` SET `total`=? where id=? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $jsonData['total'], $id);
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


