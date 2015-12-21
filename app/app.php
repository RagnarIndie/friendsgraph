<?php
require_once __DIR__.'/bootstrap.php';

use Neoxygen\NeoClient\ClientBuilder;

$app = new Silex\Application();

$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/../logs/graph.log'
));
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Igorw\Silex\ConfigServiceProvider(__DIR__."/../config/".ENV.".json"));

$app['neo'] = $app->share(function() use ($app){
    $client = ClientBuilder::create()
        ->addConnection(
            $app['db']['alias'],
            $app['db']['schema'],
            $app['db']['host'],
            $app['db']['port']
        )
        ->setAutoFormatResponse(true)
        ->build();

    return $client;
});

require_once __DIR__.'/../config/routes/all.php';

return $app;