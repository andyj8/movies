<?php

use Slim\Http\Body;
use Slim\Http\Request;
use Slim\Http\Response;

require '../vendor/autoload.php';

$dotEnv = new Dotenv\Dotenv(__DIR__ . '/..');
$dotEnv->load();
$dotEnv->required(['SLAPI_HOST', 'ELASTICSEARCH_HOST']);

$diConfig = require '../config/container.php';
$container = new Pimple\Container($diConfig);

$config = [
    'settings' => [
        'displayErrorDetails' => true
    ]
];

$app = new \Slim\App($config);

foreach ($container['config.services'] as $endpoint => $params) {
    $app->post($params['soap'], function(Request $request, Response $response) use ($endpoint, $container) {
        return $container['controller.soap']->route($endpoint, $request, $response);
    });
    $app->post($params['json'], function(Request $request, Response $response) use ($endpoint, $container) {
        return $container['controller.json']->route($endpoint, $request, $response);
    });
}

$app->get('/healthcheck', function(Request $request, Response $response) {
    return $response;
});

$app->get('/hosts', function(Request $request, Response $response) {
    $response = $response->withHeader('Content-type', 'application/json');
    $response->getBody()->write(file_get_contents(__DIR__ . '/../config/hosts.json'));
    return $response;
});

$app->run();
