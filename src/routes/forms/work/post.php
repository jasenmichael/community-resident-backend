<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// get requires token in the header
$app->post('/forms/work/post', function ($request, $response) {
    $passedToken = array_values($request->getHeader('token'))[0];
    require_once '../src/config/auth.php';

    $body = json_decode($request->getBody(), true);

    return $response->withJson(array(
        'token' => $passedToken,
        'body' => $body
    ));
});

$app->get('/forms/work/post', function ($request, $response) {
    require_once '../src/config/config.php';
    return $response->withStatus(302)->withHeader("Location", $host);
});