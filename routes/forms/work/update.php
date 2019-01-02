<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// requires token, id, resident_id, service, date, hours, description in form-date or json body
$app->put('/forms/work/update', function ($request, $response) {
    $passedToken = $request->getParam('token');
    // $passedToken = array_values($request->getHeader('token'))[0];
    require '../../gb-resident/src/backend/config/auth.php';

    if(!$passedToken){
        return $response->withJson(array('error' => 'token required'));
        exit();
    }

    if( (password_verify($adminToken, $passedToken)) || (password_verify($token, $passedToken)) ){
      $body = array(
          'id' => $request->getParam('id'),
          'resident_id' => $request->getParam('resident_id'),
          'service' => $request->getParam('service'),
          'date' => $request->getParam('date'),
          'hours' => $request->getParam('hours'),
          'description' => $request->getParam('description'),
          'updated_at' => date('Y-m-d H:i:s')
      );
  
      // if any fields are null or empty, we reject the post
      if (!in_array(null, $body) || !in_array("", $body) ){
          // post body is valid
          $id = $request->getParam('id');
          $sql = "UPDATE work SET 
            resident_id = :resident_id,
            service = :service,
            date = :date,
            hours = :hours,
            description = :description,
            updated_at = :updated_at
            WHERE id = $id";

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
              $stmt->bindParam(':updated_at', $body['updated_at']);
              $stmt->execute();
              $count = $stmt->rowCount();

              if($count =='0'){
                // echo "Failed !";
                return $response->withJson(array(
                    'error' => 'not updated'
                ));
              }
              else{
                // echo "Success !";
                return $response->withJson($body);
              }

          } catch(PDOException $e){
              echo '{"error": '.$e->getMessage().'}';
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

$app->get('/forms/work/update', function ($request, $response) {
    require_once '../src/config/config.php';
    return $response->withStatus(302)->withHeader("Location", $host);
});