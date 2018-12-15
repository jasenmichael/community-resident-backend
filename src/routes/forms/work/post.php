<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// get requires token in the header
$app->post('/forms/work/post', function ($request, $response) {
    $passedToken = array_values($request->getHeader('token'))[0];
    require_once '../src/config/auth.php';

    if(($token === $passedToken) || ($adminToken === $passedToken)) {

      // we could just load the whole body like this,
      // $body = json_decode($request->getBody(), true);
      // but we want to explicitly construct the object
      $body = array(
          'id' => 'placeholder',
          'resident_id' => $request->getParam('resident_id'),
          'service' => $request->getParam('service'),
          'date' => $request->getParam('date'),
          'hours' => $request->getParam('hours'),
          'description' => $request->getParam('description'),
          'created_at' => date('Y-m-d H:i:s'),
          'updated_at' => date('Y-m-d H:i:s')
      );
  
      // if any fields are null or empty, we reject the post
      if (!in_array(null, $body) || !in_array("", $body) ){
          // post body is valid

          $sql = "INSERT INTO work (resident_id,service,date,hours,description,created_at,updated_at) VALUES
          (:resident_id,:service,:date,:hours,:description,:created_at,:updated_at)";

          try{
              // Get DB Object
              $db = new db();
              // Connect
              $db = $db->connect();
              $stmt = $db->prepare($sql);
              $stmt->bindParam(':resident_id', $body['resident_id']);
              $stmt->bindParam(':service', $body['service']);
              $stmt->bindParam(':date', $body['date']);
              $stmt->bindParam(':hours', $body['hours']);
              $stmt->bindParam(':description', $body['description']);
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

$app->get('/forms/work/post', function ($request, $response) {
    require_once '../src/config/config.php';
    return $response->withStatus(302)->withHeader("Location", $host);
});