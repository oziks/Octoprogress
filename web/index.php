<?php

ini_set('display_errors', 0);

require_once __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../app/bootstrap.php';
require __DIR__.'/../app/prod.php';
require __DIR__.'/../src/controllers.php';
$app->run();