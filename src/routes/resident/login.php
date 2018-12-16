<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;




// resident login - requires token requires token - in the form-data body 'or' raw json
$app->post('/resident/login', function ($request, $response) {
    require '../src/config/auth.php';

    // from body
    // from form-data or x-www-form-url-encoded
    $body = $request->getParsedBody();
    // $files = $request->getUploadedFiles();

    // json body
    // $body = json_decode($request->getBody(), true);

    $user = $body['user'];
    $pass = $body['pass'];

    // or from header
    // $user = array_values($request->getHeader('usr'))[0];
    // $password = array_values($request->getHeader('pass'))[0];
    // if($users[$user] === $pass){
    if(password_verify($pass, $users[$user])){

    // if($users[$user] === $pass){

        if($user === 'admin'){
            return $response->withJson(array(
                'token' => $adminTokenHASH,
                'admin' => true
                // 'files' => array($files)
            ));
            $user = null;
            $password = null;
            exit();
        }
        // if($user === 'gbresident') {
        else {
            return $response->withJson(array('token' => $tokenHASH));
            $user = null;
            $password = null;
            exit();
        }
    }
    return $response->withStatus(401)->withJson(array('error' => 'missing or invalid credentials'));
});

$app->get('/resident/login', function ($request, $response) {
  require_once '../src/config/config.php';
  return $response->withStatus(302)->withHeader("Location", $host);
});