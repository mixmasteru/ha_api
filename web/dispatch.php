<?php
// base folder
$base = __DIR__ . '/../';
define('BASE',$base);

// autoloader
require_once $base.'/vendor/autoload.php';

use ha\Exception\Base as BaseException;

$config = array(
    'load' => array($base.'/src/*.php') // load resources
);

$app = new Tonic\Application($config);

// set up the container
$container = new Pimple();
$container['db_config'] = array(
    'dsn' => 'mysql:host=#host#;dbname=#dbname#',
    'username' => '#dbuser#',
    'password' => '#dbpwd#'
);

//mime types
$arr_options = array('mimetypes' => array('hal' => 'application/hal+json'));

//routing on live
if(isset($_SERVER['QUERY_STRING']))
{
    $uri = '/'.$_SERVER['QUERY_STRING'];
    $uri = explode("/",$uri);
    array_pop($uri);
    $uri = implode("/",$uri).'/';
    $arr_options['uri'] = $uri;
}

$request = new Tonic\Request($arr_options);

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
catch (\Exception $e) {
    $msg = 'Server error: '.get_class($e).' '.$e->getMessage();
    $response = new Tonic\Response(Tonic\Response::INTERNALSERVERERROR, $msg);
    $response->contentType = 'text/plain';
}
$response->output();
