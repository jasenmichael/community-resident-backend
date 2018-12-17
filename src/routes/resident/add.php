<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// add new resident - requires token, name, email, password - in the form-data body 'or' raw json
$app->post('/resident/add', function ($request, $response) {
    $bodyREQ = $request->getParsedBody();
    $passedToken = $bodyREQ['token'];
    require '../src/config/auth.php';

    if(!$passedToken){
        return $response->withJson(array('error' => 'token required'));
        exit();
    }

    if( (password_verify($adminToken, $passedToken)) || (password_verify($token, $passedToken)) ){

      $passHash = password_hash($bodyREQ['password'], PASSWORD_DEFAULT);
      $body = array(
        'id' => "placeholder",
        'uuid' => $bodyREQ['uuid'],
        'name' => $bodyREQ['name'],
        'email' => $bodyREQ['email'],
        'password' => $passHash,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      );
  
      // if any fields are null or empty, we reject the post
      if (!in_array(null, $body) || !in_array("", $body) ){
          // post body is valid (no fields are empty or null)...

          // TODO check if uuid && email is unique ----!!!!!
          // check if uuid && email is unique
        $user = $bodyREQ['uuid'];
        $email = $bodyREQ['email'];

        // check uuid taken
        $sqlCheckUUID = "SELECT * FROM resident WHERE uuid = '$user'";
        try{
            $db = new db();
            $db = $db->connect();
            $statement = $db->query($sqlCheckUUID);
            $UUIDlist = $statement->fetchAll(PDO::FETCH_OBJ);
            $db = null;
            if(count($UUIDlist)){
                return $response->withJson(array('error' => "uuid taken"));
                exit();
            }
        }
        catch(PDOException $e){
            return $response->withJson(array('error' => $e->getMessage()));
            exit();
        }
        // uuid not taken... continue
        
        // check email taken
        $sqlCheckEmail = "SELECT * FROM resident WHERE email = '$email'";
        try{
            $db = new db();
            $db = $db->connect();
            $statement = $db->query($sqlCheckEmail);
            $EMAILlist = $statement->fetchAll(PDO::FETCH_OBJ);
            $db = null;
            if(count($EMAILlist)){
                return $response->withJson(array('error' => "email taken"));
                exit();
            }
        }
        catch(PDOException $e){
            return $response->withJson(array('error' => $e->getMessage()));
            exit();
        }
        // email not taken... continue


        $sql = "INSERT INTO resident 
        (uuid,name,email,password,created_at,updated_at) VALUES
        (:uuid,:name,:email,:password,:created_at,:updated_at)";

        try{
            // Get DB Object
            $db = new db();
            // Connect
            $db = $db->connect();
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':uuid', $body['uuid']);
            $stmt->bindParam(':name', $body['name']);
            $stmt->bindParam(':email', $body['email']);
            $stmt->bindParam(':password', $body['password']);
            $stmt->bindParam(':created_at', $body['created_at']);
            $stmt->bindParam(':updated_at', $body['updated_at']);
            $stmt->execute();

            // get the posted id and set body id(replacing the placeholder)
            $body['id'] = $db->lastInsertId();

        } catch(PDOException $e){
            return $response->withJson(array('error' => $e->getMessage()));
        //   echo '{"error": {"text": '.$e->getMessage().'}';
            exit();
        }
        $resBody = array(
            'id' => $body['id'],
            'uuid' => $body['uuid'],
            'name' => $body['name'],
            'email' => $body['email']
        );
        return $response->withJson($resBody);
        exit();
    } else {
        return $response->withJson(array(
            'error' => 'missing fields'
        ));
        exit();
    }
}
    return $response->withJson(array('error' => 'invalid token'));
    exit();
});

