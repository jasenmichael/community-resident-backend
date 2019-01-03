<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// get requires token in the header
$app->post('/files', function ($request, $response) {
    // $passedToken = array_values($request->getHeader('token'))[0];
    $body = $request->getParsedBody();
    $passedToken = $body['token'];
    require '../../gb-resident/src/backend/config/auth.php';

    // get local token and compare
    if( ( password_verify($adminToken, $passedToken) ) || ( password_verify($token, $passedToken) ) ){
        $directories = getDirectories('/');
        $onlyExtentions = array(
            "pdf"
        );
        $files = getFiles('/files', $onlyExtentions);
        return $response->withJson(array(
            'path' => 'files',
            'directories' => $directories,
            'files' => $files
        ));
        $token = null;
        $passedToken = null;
        $adminToken = null;
        exit();
    }
    return $response->withJson(array('error' => 'invalid token'));
    $token = null;
    $passedToken = null;
    $adminToken = null;
    exit();
});

$app->post('/files/{path}', function ($request, $response) {
    // $passedToken = array_values($request->getHeader('token'))[0];
    $body = $request->getParsedBody();
    $passedToken = $body['token'];
    require '../../gb-resident/src/backend/config/auth.php';
    $path = $request->getAttribute('path');
    // echo $path;
    // exit();

    // get local token and compare
    if( ( password_verify($adminToken, $passedToken) ) || ( password_verify($token, $passedToken) ) ){
        $directories = getDirectories('/' . $path);
        $onlyExtentions = array(
            "pdf"
        );
        $files = getFiles('/files/' . $path, $onlyExtentions);
        return $response->withJson(array(
            'path' => 'files/' . $path,
            'directories' => $directories,
            'files' => $files
        ));        
        exit();
    }
    return $response->withJson(array('error' => 'invalid token'));
    exit();
});

$app->post('/files/{files}/{subDir}', function ($request, $response) {
    // $passedToken = array_values($request->getHeader('token'))[0];
    // get local token and compare
    $path1 = $request->getAttribute('files');
    $path2 = $request->getAttribute('subDir');
    $path = $path1 . "/" . $path2;

    $body = $request->getParsedBody();
    $passedToken = $body['token'];
    require '../../gb-resident/src/backend/config/auth.php';

    // get local token and compare
    if( ( password_verify($adminToken, $passedToken) ) || ( password_verify($token, $passedToken) ) ){
    // if(($token === $passedToken) || ($adminToken === $passedToken)) {
        $directories = getDirectories('/' . $path);
        $onlyExtentions = array(
            "pdf"
        );
        $files = getFiles('/files/' . $path, $onlyExtentions);
        return $response->withJson(array(
            'path' => 'files/' . $path,
            'directories' => $directories,
            'files' => $files
        ));        
        $token = null;
        $passedToken = null;
        $adminToken = null;
        exit();
    }
    return $response->withJson(array('error' => 'invalid token'));
    $token = null;
    $passedToken = null;
    $adminToken = null;
    exit();
});

$app->post('/files/{files}/{subDir}/{subSubDir}', function ($request, $response) {
    // $passedToken = array_values($request->getHeader('token'))[0];
    // get local token and compare
    $path1 = $request->getAttribute('files');
    $path2 = $request->getAttribute('subDir');
    $path3 = $request->getAttribute('subSubDir');
    $path = $path1 . "/" . $path2 . "/" . $path3;

    $body = $request->getParsedBody();
    $passedToken = $body['token'];
    require '../../gb-resident/src/backend/config/auth.php';

    // get local token and compare
    if( ( password_verify($adminToken, $passedToken) ) || ( password_verify($token, $passedToken) ) ){
    // if(($token === $passedToken) || ($adminToken === $passedToken)) {
        $directories = getDirectories('/' . $path);
        $onlyExtentions = array(
            "pdf"
        );
        $files = getFiles('/files/' . $path, $onlyExtentions);
        return $response->withJson(array(
            'path' => 'files/' . $path,
            'directories' => $directories,
            'files' => $files
        ));        
        $token = null;
        $passedToken = null;
        $adminToken = null;
        exit();
    }
    return $response->withJson(array('error' => 'invalid token'));
    $token = null;
    $passedToken = null;
    $adminToken = null;
    exit();
});

function getDirectories($directory) {
    require_once '../../gb-resident/src/backend/config/config.php';
    $path = $filesDir . $directory;
    // echo $path;
    $dir = new DirectoryIterator('..' . $path);
    $folders = array();
    foreach ($dir as $fileinfo) {
      if ($fileinfo->isDir() && !$fileinfo->isDot()) {
        $folder = $fileinfo->getFilename();
        array_push($folders, $folder);
      }
    }
    return $folders;
}

function getFiles($directory, $onlyExtentions) {
    require_once '../../gb-resident/src/backend/config/config.php';
    $path = $filesDir . "/" . $directory;
    // echo $path;
    $dir = new DirectoryIterator('..' . $path);
    $files = array();
    foreach ($dir as $fileinfo) {
      if ($fileinfo->isFile() && !$fileinfo->isDot()) {
        $file = $fileinfo->getFilename();
        $fileExt = $fileinfo->getExtension();
        if(in_array($fileExt, $onlyExtentions)){
            array_push($files, $file);
        }
      }
    }
    return $files;
}


