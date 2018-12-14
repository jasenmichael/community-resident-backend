<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/resident/login', function ($request, $response) {
    require '../src/config/auth.php';

    // from body
    $body = json_decode($request->getBody(), true);
    $user = $body['user'];
    $pass = $body['pass'];

    // or from header
    // $user = array_values($request->getHeader('usr'))[0];
    // $password = array_values($request->getHeader('pass'))[0];
    if($users[$user] === $pass){
        if($user === 'admin'){
            return $response->withJson(array(
                'token' => $adminToken,
                'admin' => true
            ));
            $user = null;
            $password = null;
            exit();
        }
        // if($user === 'gbresident') {
        if($user && $pass) {
            return $response->withJson(array('token' => $token));
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