<?php
ini_set('display_error', 1);
error_reporting(E_ALL);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
// login
$app->post('/customer/login', function (Request $request, Response $response, array $args) {
    // $body = $request->getParsedBody();
    $json = $request->getBody();
    $jsonData = json_decode($json, true);
    $conn = $GLOBALS['dbconnect'];
    $email = $jsonData['email'];
    $pwd = $jsonData['password'];

    $pwdInDB = getPasswordFromDB($conn, $email);

    if (password_verify($pwd, $pwdInDB)) {
        $stmt = $conn->prepare("select * from customer where email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = array();
         foreach ($result as $row) {
        array_push($data, $row);
        }
        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    } else {

        $response->getBody()->write("false");
    }
    return $response
        ->withHeader('Content-Type', 'application/json; charset=utf-8')
        ->withStatus(200);
});


//Change password

$app->post('/customer/changepwd', function (Request $request, Response $response, array $args) {
    $json = $request->getBody();
    $jsonData = json_decode($json, true);
    $conn = $GLOBALS['dbconnect'];
    $email = $jsonData['email'];
    $pwd = $jsonData['password'];
    $newpwd = $jsonData['newpwd'];
    
    $pwdInDB = getPasswordFromDB($conn, $email);

    if (password_verify($pwd, $pwdInDB)) {
        $hashed = password_hash($newpwd,PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE customer SET password=? WHERE email = ?");
        $stmt->bind_param("ss", $hashed, $email);
        $stmt->execute();
        if ($stmt->execute()) {
            $response->getBody()->write("Password Update");
        } else {
            $response->getBody()->write("Failed : " . mysqli_error($conn));
        }
    } else {
        $response->getBody()->write("Your password is wrong");
    }
    return $response->withHeader('Content-Type', 'application/json');
});

//add password
$app->post('/customer/add', function (Request $request, Response $response, array $args) {
    $body = $request->getParsedBody();

    $conn = $GLOBALS['dbconnect'];
    $email = $body['email'];
    $pwd = $body['password'];

    $hashed = password_hash($pwd,PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE customer SET password=? WHERE email = ?");
    $stmt->bind_param("ss", $hashed, $email);
    $stmt->execute();
    
    if ($stmt->execute()) {
        $response->getBody()->write("Add password already ");
    } else {
        $response->getBody()->write("Failed  " );
    }
    
    
    return $response;
});
//get password in db
function getPasswordFromDB($conn, $email)
{
    $stmt = $conn->prepare("select password from customer where email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        return $row['password'];
    } else {
        return "";
    }
}         

//serch all
$app->get('/customer', function (Request $request, Response $response) {
    $conn = $GLOBALS['dbconnect'];
    $sql = 'select * from customer';
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
//serch id
$app->get('/customer/{id}', function (Request $request, Response $response, $args) {
    $conn = $GLOBALS['dbconnect'];
    $sql = 'select * from customer where cid = ?';
    $stmt = $conn->prepare($sql);

    $stmt->bind_param('s', $args['id']);
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
$app->get('/customer/name/{name}', function (Request $request, Response $response, $args) {
    $idx = '%'.$args['name'].'%';
    $conn = $GLOBALS['dbconnect'];
    $sql = 'select * from customer where name like ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $idx);
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

//////insert
$app->post('/customer/register', function (Request $request, Response $response,array $args) {
    
    $body = $request->getBody();
    $bodyArray = json_decode($body, true);
    $conn = $GLOBALS['dbconnect'];
    
   
    $hashed = password_hash($bodyArray['password'],PASSWORD_DEFAULT);
    // echo $hashed;
    $sql = 'insert into customer (cid,name,phone,email,password) values (null,?,?,?,?)';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssss', $bodyArray['name'], $bodyArray['phone'], $bodyArray['email'], $hashed);
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

//update
$app->put('/customer/{id}', function (Request $request, Response $response, $args) {
    $json = $request->getBody();
    $jsonData = json_decode($json, true);
    $id = $args['id'];
    $conn = $GLOBALS['dbconnect'];
    $sql = "UPDATE `customer` SET `name`=?,`phone`=?,`email`=? WHERE cid =?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssi', $jsonData['name'], $jsonData['phone'], $jsonData['email'], $id);
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
//delete
$app->delete('/customer/{id}', function (Request $request, Response $response, $args) {
    $id = $args['id'];
    $conn = $GLOBALS['dbconnect'];
    $sql = 'delete from customer where cid = ?';
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





// $app->post('/customer/q', function (Request $request, Response $response, array $args) {
//     $conn = $GLOBALS['dbconnect'];
//     $json = $request->getBody();
//     // $json = $request->getParsedBody();
//     $jsonData = json_decode($json, true);
//     $email = $jsonData['email'];
//     $pwd = $jsonData['password'];
//     function getPasswordFromDB($conn,$email){
//         $stmt=$conn->prepare("select * from customer where email = ?");
//         $stmt->bind_param("s",$email);  
//         $stmt->execute();
//         $result=$stmt->get_result();
//         if($result->num_rows >= 1 ){
//             $row = $result->fetch_assoc();
//             // echo $row['password'];
//             return $row['password'];
//         }else{
          
//             return "aaa";
//         }
//     }
//     $pwdInDB = getPasswordFromDB($conn,$email);

//     $chkpassword = password_verify($pwd,$pwdInDB);

//     if($chkpassword ){
//         $stmt = $conn->prepare("select cid from customer where email = ?");
//         $stmt->bind_param("s",$email);
//         $stmt->execute();
//         $result = $stmt->get_result();
//         $data = array();
//         foreach ($result as $row) {
//             array_push($data, $row);
//         }
//         $response->getBody()->write(json_encode($data));
//     }
//     else{
//         $response->getBody()->write("login failed");
//     }

//     return $response
//         ->withHeader('Content-Type', 'application/json')
//         ->withStatus(200);
// });



