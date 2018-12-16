<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/resident/login', function ($request, $response) {
    require '../src/config/auth.php';
    $body = $request->getParsedBody();
    $user = $body['user'];
    $pass = $body['pass'];
    //if user admin
    if($user == 'admin'){
        // verify password
        if(!password_verify($pass, $users[$user])){
            return $response->withJson(array('error' => 'invalid password'));
            exit();
        }
        $adminInfo = array(
            'token' => password_hash($adminToken, PASSWORD_DEFAULT),
            'id' => 1000,
            'uuid' => 'admin'
        );
        return $response->withJson($adminInfo);
        exit();
    }
    if($user == 'gbresident'){
        // verify password
        if(!password_verify($pass, $users[$user])){
            return $response->withJson(array('error' => 'invalid password'));
            exit();
        }
        $adminInfo = array(
            'token' => password_hash($token, PASSWORD_DEFAULT),
            'id' => 1111,
            'uuid' => 'resident'
        );
        return $response->withJson($adminInfo);
        exit();
    }
    
    // else get from db
    $sql = "SELECT * FROM resident WHERE uuid = '$user' OR email = '$user'";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->query($sql);
        $resident = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;

        if($resident){
            // check if password is set in db
            if(!$resident->password){
                return $response->withJson(array('error' => 'resident exist but password not set'));
                exit();
            }


            //verify password
            if(!password_verify($pass, $resident->password)){
                return $response->withJson(array('error' => 'invalid password'));
                exit();
            }

            $residentInfo = array(
                'token' => password_hash($token, PASSWORD_DEFAULT),
                'id' => $resident->id,
                'uuid' => $resident->uuid,
                'name' => $resident->name,
                'email' => $resident->email,
                'created_at' => $resident->created_at,
                'updated_at' => $resident->updated_at
            );
            return $response->withJson($residentInfo);
            exit();
        }
        else {
            return $response->withJson(array('error' => 'invalid user'));
            exit();
        }
    } catch(PDOException $e){
        return $response->withJson(array('error' => 'could not access db'));
        exit();
    }
});