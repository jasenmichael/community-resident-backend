<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../src/vendor/autoload.php';
require '../src/config/db.php';

$app = new \Slim\App;

$app->get('/', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    // $response->getBody()->write("Hello");
    return $response->withJson(array(
        '/api/resident/login',

        '/api/files',

        '/api/files/archive',
        '/api/files/archive/work-credit-submission',
        '/api/files/archive/receipt-credit-submissions',
        '/api/files/archive/meeting-minutes',
        '/api/files/forms',

        '/api/files/documents',

        '/api/forms/work/list'
    ));
});

require '../src/routes/resident/login.php';
require '../src/routes/forms/work/list.php';
require '../src/routes/forms/work/post.php';

require '../src/routes/files/archive.php';

$app->run();