<?php

namespace Octoprogress\Controller;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;

use Github\Client as GithubClient;

use Octoprogress\Model\User;

class AccountController implements ControllerProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/profile', function () use ($app) {
            /** @var User $user */
            $user = $app['session']->get('user');
            if (!$user) {
                return $app->redirect($app['url_generator']->generate('homepage'));
            }

            $githubClient = new GithubClient();
            $githubClient->authenticate($user->getLogin(), $user->getAccesToken());

            $repositories = $githubClient->api('user')->repositories($user->getLogin());

            return $app['twig']->render('account/profile.html', array(
                'user'          => $user,
                'repositories'  => $repositories,
            ));
        })
        ->bind('account')
        ;

        return $controllers;
    }
}
