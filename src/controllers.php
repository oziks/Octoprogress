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

$app->mount('/job', new \Octoprogress\Controller\JobController());
$app->mount('/oauth', new \Octoprogress\Controller\OauthController());
$app->mount('/board', new \Octoprogress\Controller\BoardController());

$app->mount('/account', new \Octoprogress\Controller\AccountController());
$app->get('/account', function () use ($app) { return $app->redirect($app['url_generator']->generate('account_profile')); });
