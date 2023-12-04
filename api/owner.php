<?php
ini_set('display_error', 1);
error_reporting(E_ALL);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
// login
// $app->post('/owner/login', function (Request $request, Response $response, array $args) {
//     $json = $request->getBody();
//     $jsonData = json_decode($json, true);
//     $conn = $GLOBALS['dbconnect'];
//     $email = $jsonData['email'];
//     $pwd = $jsonData['password'];

//     $pwdInDB = getPasswordFromDBforOwner($conn, $email);

//     if (password_verify($pwd, $pwdInDB)) {
//         $response->getBody()->write("Login Sucessful");
//     } else {
//         $response->getBody()->write("Login Fail " );
//     }
//     return $response;
// });

$app->post('/owner/login', function (Request $request, Response $response, array $args) {
    // $body = $request->getParsedBody();
    $json = $request->getBody();
    $jsonData = json_decode($json, true);
    $conn = $GLOBALS['dbconnect'];
    $email = $jsonData['email'];
    $pwd = $jsonData['password'];

    $pwdInDB = getPasswordFromDBforOwner($conn, $email);
    if (password_verify($pwd, $pwdInDB)) {
        $stmt = $conn->prepare("select * from owner where email = ?");
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
$app->post('/owner/changepwd', function (Request $request, Response $response, array $args) {
    $json = $request->getBody();
    $jsonData = json_decode($json, true);
    $conn = $GLOBALS['dbconnect'];
    $email = $jsonData['email'];
    $pwd = $jsonData['password'];
    $newpwd = $jsonData['newpwd'];
    
    $pwdInDB = getPasswordFromDBforOwner($conn, $email);

    if (password_verify($pwd, $pwdInDB)) {
        $hashed = password_hash($newpwd,PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE owner SET password=? WHERE email = ?");
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
$app->post('/owner/add', function (Request $request, Response $response, array $args) {
    $body = $request->getParsedBody();

    $conn = $GLOBALS['dbconnect'];
    $email = $body['email'];
    $pwd = $body['password'];

    $hashed = password_hash($pwd,PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE owner SET password=? WHERE email = ?");
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
function getPasswordFromDBforOwner($conn, $email)
{
    $stmt = $conn->prepare("select password from owner where email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        return $row['password'];
    } else {
        return "aaa";
    }
}         

//serch all
$app->get('/owner', function (Request $request, Response $response) {
    $conn = $GLOBALS['dbconnect'];
    $sql = 'select * from owner';
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
$app->get('/owner/{id}', function (Request $request, Response $response, $args) {
    $conn = $GLOBALS['dbconnect'];
    $sql = 'select * from owner where id = ?';
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
//search name
$app->get('/owner/name/{name}', function (Request $request, Response $response, $args) {
    $idx = '%'.$args['name'].'%';
    $conn = $GLOBALS['dbconnect'];
    $sql = 'select * from owner where name like ?';
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
$app->post('/owner', function (Request $request, Response $response,array $args) {
    
    $body = $request->getBody();
    $bodyArray = json_decode($body, true);
    $conn = $GLOBALS['dbconnect'];
    
   
    $hashed = password_hash($bodyArray['password'],PASSWORD_DEFAULT);
    // echo $hashed;
    $sql = 'insert into owner (id,name,email,password,phone) values (null,?,?,?,?)';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssss', $bodyArray['name'], $bodyArray['email'], $hashed, $bodyArray['phone'], );
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
$app->put('/owner/{id}', function (Request $request, Response $response, $args) {
    $json = $request->getBody();
    $jsonData = json_decode($json, true);
    $id = $args['id'];
    $conn = $GLOBALS['dbconnect'];

    // $hashed = password_hash($jsonData['password'],PASSWORD_DEFAULT);

    $sql = "UPDATE `owner` SET `name`=?,`email`=?,`phone`=? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssi', $jsonData['name'], $jsonData['email'], $jsonData['phone'], $id);
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
$app->delete('/owner/{id}', function (Request $request, Response $response, $args) {
    $id = $args['id'];
    $conn = $GLOBALS['dbconnect'];
    $sql = 'delete from owner where id = ?';
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