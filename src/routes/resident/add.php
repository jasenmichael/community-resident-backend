<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// add new resident - requires token, name, email, password - in the form-data body 'or' raw json
$app->post('/resident/add', function ($request, $response) {
    // $passedToken = array_values($request->getHeader('token'))[0];
    $bodyREQ = $request->getParsedBody();
    $passedToken = $bodyREQ['token'];
    require '../src/config/auth.php';

    if( ( password_verify($adminToken, $passedToken) ) || ( password_verify($token, $passedToken) ) ){

      $passHash = password_hash($bodyREQ['password'], PASSWORD_DEFAULT);
      $body = array(
        'name' => $bodyREQ['name'],
        'email' => $bodyREQ['email'],
        'password' => $passHash,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      );
  
      // if any fields are null or empty, we reject the post
      if (!in_array(null, $body) || !in_array("", $body) ){
          // post body is valid

          $sql = "INSERT INTO resident 
            (name,email,password,created_at,updated_at) VALUES
            (:name,:email,:password,:created_at,:updated_at)";

          try{
              // Get DB Object
              $db = new db();
              // Connect
              $db = $db->connect();
              $stmt = $db->prepare($sql);
              $stmt->bindParam(':name', $body['name']);
              $stmt->bindParam(':email', $body['email']);
              $stmt->bindParam(':password', $body['password']);
              $stmt->bindParam(':created_at', $body['created_at']);
              $stmt->bindParam(':updated_at', $body['updated_at']);
              $stmt->execute();

              // get the posted id and set body id(replacing the placeholder)
              $body['id'] = $db->lastInsertId();

          } catch(PDOException $e){
              echo '{"error": {"text": '.$e->getMessage().'}';
          }
          return $response->withJson($body);
      } else {
          return $response->withJson(array(
              'error' => 'missing fields'
          ));
      }
    }
    return $response->withJson(array('error' => 'invalid token'));
    $db = null;
    $token = null;
    $passedToken = null;
    $adminToken = null;
    exit();
});
