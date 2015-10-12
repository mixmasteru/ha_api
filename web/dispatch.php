<?php
// autoloader
require_once '../vendor/autoload.php';

use ha\Exception\Base as BaseException;

$config = array(
    'load' => array('../src/*.php') // load resources
);

$app = new Tonic\Application($config);

// set up the container
$container = new Pimple();
$container['db_config'] = array(
    'dsn' => 'mysql:host=host;dbname=dbname',
    'username' => 'user',
    'password' => 'pwd'
);

$request = new Tonic\Request(array(
    'mimetypes' => array(
        'hal' => 'application/hal+json'
    )
));

try {
    $resource = $app->getResource($request);
    $resource->container = $container; // attach container to loaded resource
    $response = $resource->exec();
    $response->contentType = 'application/json';
}
catch (Tonic\NotFoundException $e) {
    $response = new Tonic\Response($e->getCode(), $e->getMessage());
}
catch (Tonic\UnauthorizedException $e) {
    $response = new Tonic\Response(401, 'Unauthorized');
    $response->wwwAuthenticate = 'Basic realm="My Realm"';
}
catch (Tonic\Exception $e) {
    $response = new Tonic\Response(Tonic\Response::INTERNALSERVERERROR, 'Server error');
} 
catch (BaseException $e) {
    $response = new Tonic\Response(Tonic\Response::INTERNALSERVERERROR,$e->getAsJson());
    $response->contentType = 'application/json';
}

$response->output();
