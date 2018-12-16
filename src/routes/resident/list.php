<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// list all residents - requires token - in the form-data body 'or' raw json
$app->post('/residents', function ($request, $response) {
    // $passedToken = array_values($request->getHeader('token'))[0];
    $body = $request->getParsedBody();
    $passedToken = $body['token'];
    require '../src/config/auth.php';

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

