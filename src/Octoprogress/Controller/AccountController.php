<?php

namespace Octoprogress\Controller;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;

use Octoprogress\Model\User,
    Octoprogress\Model\ProjectQuery,
    Octoprogress\Model\MilestoneQuery;

use Github\Client as GithubClient,
    Github\HttpClient\HttpClient as GithubHttpClient;

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

            $repositories = ProjectQuery::create()
                ->filterByUserId($user->getId())
                ->filterByActive(true)
                ->orderByUpdatedAt(\Criteria::DESC)
                ->find()
            ;

            $milestones = MilestoneQuery::create()
                ->useProjectQuery()
                    ->orderByUpdatedAt(\Criteria::DESC)
                ->endUse()
                ->find()
            ;

            return $app['twig']->render('account/profile.html', array(
                'user'          => $user,
                'repositories'  => $repositories,
                'milestones'    => $milestones,
            ));
        })
        ->bind('account_profile')
        ;

        return $controllers;
    }
}
