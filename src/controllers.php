<?php

use Symfony\Component\HttpFoundation\Response;

$app->error(function (\Exception $exception, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    $page = 404 == $code ? '404.html' : '500.html';

    return new Response($app['twig']->render('errors/'.$page, array('code' => $code)), $code);
});

$app->get('/', function () use ($app) {
    $user = $app['session']->get('user');
    if (!$user) {
        return $app['twig']->render('login.twig', array());
    }

    return $app->redirect($app['url_generator']->generate('board'));
})
->bind('homepage')
;

$app->mount('/job', new \Octoprogress\Controller\JobController());
$app->mount('/oauth', new \Octoprogress\Controller\OauthController());
$app->mount('/board', new \Octoprogress\Controller\BoardController());

$app->mount('/projects', new \Octoprogress\Controller\ProjectsController());
