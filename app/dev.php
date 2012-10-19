<?php

use Silex\Provider\MonologServiceProvider;
use FF\ServiceProvider\LessServiceProvider;

// include the prod configuration
require __DIR__.'/prod.php';

// enable the debug mode
$app['debug'] = true;

$app->register(new MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/logs/octoprogress.log',
));

$app->register(new LessServiceProvider(), array(
    'less.sources' => $app['config']->get('root_dir').'/src/Octoprogress/Resources/public/css/less/main.less',
    'less.target'  => $app['config']->get('root_dir').'/src/Octoprogress/Resources/public/css/main.css',
));
