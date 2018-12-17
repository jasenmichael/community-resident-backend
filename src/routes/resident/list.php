<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/residents', function ($request, $response) {
    $body = $request->getParsedBody();
    $passedToken = $body['token'];
    require '../src/config/auth.php';

    if(!$passedToken){
        return $response->withJson(array('error' => 'token required'));
        exit();
    }
    
    // get local token and compare
    if( ( password_verify($adminToken, $passedToken) ) || ( password_verify($token, $passedToken) ) ){
        $sql = "SELECT * FROM resident";

        try{
            $db = new db();
            $db = $db->connect();

            $statement = $db->query($sql);
            $list = $statement->fetchAll(PDO::FETCH_OBJ);
            $db = null;
            $residents = [];
            // echo array_values($list('id'))[0];
            foreach($list as $resident){
                $residentInfo = array(
                    'id' => $resident->id,
                    'uuid' => $resident->uuid,
                    'name' => $resident->name,
                    'email' => $resident->email,
                    'created_at' => $resident->created_at,
                    'updated_at' => $resident->updated_at
                );
                $residents[] = $residentInfo;
            }
            return $response->withJson($residents);
            exit();
        }
        
        catch(PDOException $e){
            return $response->withJson(array('error' => $e->getMessage()));
            exit();
        }
    }
    return $response->withJson(array('error' => 'invalid token'));
    exit();
});

