<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// get requires token in the header
$app->delete('/forms/work/delete/{id}', function ($request, $response) {
    $passedToken = array_values($request->getHeader('token'))[0];
    require_once '../src/config/auth.php';

    if(($token === $passedToken) || ($adminToken === $passedToken)) {
      $id = $request->getAttribute('id');
      $sql = "DELETE FROM work WHERE id = $id";
      try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $count = $stmt->rowCount();
        $db = null;
        if($count =='0'){
          // echo "Failed !";
          return $response->withJson(array(
              'error' => $id. ' does not exist'
          ));
        }
        else{
          // echo "Success !";
          return $response->withJson(array(
            'deleted' => $id
          ));
        }
        
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }

      return $response->withJson(array('id' => $id));
    }
    return $response->withJson(array('error' => 'invalid token'));
    $token = null;
    $passedToken = null;
    $adminToken = null;
    exit();
});