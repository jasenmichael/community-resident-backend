<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// get requires id in url and token must be json in the body!!!
$app->delete('/forms/work/delete/{id}', function ($request, $response) {
    require '../src/config/auth.php';
    $body = $request->getParsedBody();
    $passedToken = $body['token'];

    //check a token was passed
    if(!$passedToken){
      return $response->withJson(array('error' => 'token required'));
      exit();
    }

    // verify token
    if( !(password_verify($token, $passedToken)) || (password_verify($adminToken, $passedToken)) ){
      return $response->withJson(array('error' => 'invalid token'));
      exit();
    }
    
    // token good letz go yo...
    $id = $request->getAttribute('id');
    $sql = "DELETE FROM work WHERE id = $id";
    try{
      $db = new db();
      $db = $db->connect();
      $stmt = $db->prepare($sql);
      $stmt->execute();
      $count = $stmt->rowCount();
      $db = null;

      // confirm row was deleted from db
      if($count == '0'){
        return $response->withJson(array(
            'error' => $id. ' does not exist'
        ));
        exit();
      }
      else{
        return $response->withJson(array(
          'deleted' => $id
        ));
        exit();
      }
      
    } catch(PDOException $e){
      return $response->withJson(array(
        'error' => 'could not access db',
        'message' => $e->getMessage()
      ));
      exit();
    }

});