<?php

namespace Octoprogress\Controller;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;

class AccountController implements ControllerProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/profile', function () use ($app) {
            $user = $app['session']->get('user');
            if (!$user) {
                return $app->redirect($app['url_generator']->generate('homepage'));
            }

            return $app['twig']->render('account/profile.html', array('user' => $user));
        })
        ->bind('account')
        ;

        return $controllers;
    }
}
