<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$srcDir = '../../gb-resident/src/backend';
require $srcDir.'/vendor/autoload.php';
require $srcDir.'/config/db.php';


$app = new \Slim\App;

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});
$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

$app->get('/', function (Request $request, Response $response, array $args) {
    $name = $args['name'];

    require '../../gb-resident/src/backend/config/config.php';
    return $response->withJson(array(
        '/api/residents',
        '/api/resident/login',
        '/api/resident/add',

        '/api/files',

        '/api/files/archive',
        '/api/files/archive/work-credit-submission',
        '/api/files/archive/receipt-credit-submissions',
        '/api/files/archive/meeting-minutes',
        '/api/files/forms',

        '/api/files/documents',

        '/api/forms/work/list',
        '/api/forms/work/post',
        '/api/forms/work/update',
        '/api/forms/work/delete/:id'
    ));
});

require $srcDir.'/routes/resident/login.php';
require $srcDir.'/routes/resident/list.php';
require $srcDir.'/routes/resident/add.php';

require $srcDir.'/routes/forms/work/list.php';
require $srcDir.'/routes/forms/work/post.php';
require $srcDir.'/routes/forms/work/update.php';
require $srcDir.'/routes/forms/work/delete.php';

require $srcDir.'/routes/files/archive.php';

$app->run();