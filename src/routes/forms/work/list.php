<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// get requires token in the header
$app->get('/forms/work/list', function ($request, $response) {
    $passedToken = array_values($request->getHeader('token'))[0];
    require_once '../src/config/auth.php';

    // get local token and compare
    if(($token === $passedToken) || ($adminToken === $passedToken)) {
        $sql = "SELECT * FROM work";

        try{
            $db = new db();
            $db = $db->connect();

            $statement = $db->query($sql);
            $list = $statement->fetchAll(PDO::FETCH_OBJ);
            $db = null;

            return $response->withJson(array(
                'valid' => $token,
                'message' => 'connected to db',
                'list' => $list
    
            ));
        }
        
        catch(PDOException $e){
            return $response->withJson(array('error' => 'could not access db'));
        }
    }
    return $response->withJson(array('error' => 'invalid token'));
    $db = null;
    $token = null;
    $passedToken = null;
    $adminToken = null;
    exit();
});

