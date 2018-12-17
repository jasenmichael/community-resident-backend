<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// post requires token in content-form or json body
$app->post('/forms/work/list', function ($request, $response) {
    // $passedToken = array_values($request->getHeader('token'))[0];
    require_once '../src/config/auth.php';
    $body = $request->getParsedBody();
    $passedToken = $body['token'];
    require '../src/config/auth.php';

    //check token was passed
    if(!$passedToken){
        return $response->withJson(array('error' => 'token required'));
    }

    // verify token
    if( !(password_verify($token, $passedToken)) || (password_verify($adminToken, $passedToken)) ){
        return $response->withJson(array('error' => 'invalid token'));
        exit();
    }

    // we good, do the thing
    $sql = "SELECT * FROM work";
    try{
        $db = new db();
        $db = $db->connect();

        $statement = $db->query($sql);
        $list = $statement->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        return $response->withJson($list);
        exit();
    }
    
    catch(PDOException $e){
        return $response->withJson(array('error' => 'could not access db'));
        exit();
    }
});

