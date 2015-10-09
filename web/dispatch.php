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
    'dsn' => 'mysql:host=localhost;dbname=%s',
    'username' => 'root',
    'password' => ''
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
}
catch (Tonic\NotFoundException $e) {
    $response = new Tonic\Response(404, 'Not found');
}
catch (Tonic\UnauthorizedException $e) {
    $response = new Tonic\Response(401, 'Unauthorized');
    $response->wwwAuthenticate = 'Basic realm="My Realm"';
}
catch (Tonic\Exception $e) {
    $response = new Tonic\Response(Tonic\Response::INTERNALSERVERERROR, 'Server error');
} 
catch (BaseException $e) {
	$response = new Tonic\Response(Tonic\Response::INTERNALSERVERERROR,$e->getMessage().":".PHP_EOL.$e->getTraceAsString());
}
#$response->contentType = 'text/plain';

$response->output();
