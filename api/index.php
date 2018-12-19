<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../src/vendor/autoload.php';
require '../src/config/db.php';


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

    require '../src/config/config.php';
    return $response->withJson(array(
        $baseDir. '/api/residents',
        $baseDir. '/api/resident/login',
        $baseDir. '/api/resident/add',

        $baseDir. '/api/files',

        $baseDir. '/api/files/archive',
        $baseDir. '/api/files/archive/work-credit-submission',
        $baseDir. '/api/files/archive/receipt-credit-submissions',
        $baseDir. '/api/files/archive/meeting-minutes',
        $baseDir. '/api/files/forms',

        $baseDir. '/api/files/documents',

        $baseDir. '/api/forms/work/list',
        $baseDir. '/api/forms/work/post',
        $baseDir. '/api/forms/work/update',
        $baseDir. '/api/forms/work/delete/:id'
    ));
});

require '../src/routes/resident/login.php';
require '../src/routes/resident/list.php';
// require '../src/routes/resident/login--old.php';
require '../src/routes/resident/add.php';

require '../src/routes/forms/work/list.php';
require '../src/routes/forms/work/post.php';
require '../src/routes/forms/work/update.php';
require '../src/routes/forms/work/delete.php';

require '../src/routes/files/archive.php';

$app->run();