<?php

use Symfony\Component\HttpFoundation\Response;

$app->error(function (\Exception $exception, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    $page = 404 == $code ? '404.html' : '500.html';

    return new Response($app['twig']->render($page, array('code' => $code)), $code);
});

$app->get('/', function () use ($app) {
     return $app['twig']->render('index.html', array());
})
->bind('homepage')
;

$app->mount('/oauth', new \Octoprogress\Controller\OauthController());