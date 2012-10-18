<?php

namespace Octoprogress\Controller;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;

use Symfony\Component\HttpFoundation\Request;

use Octoprogress\Model\User,
    Octoprogress\Model\ProjectQuery,
    Octoprogress\Model\Job;

class BoardController implements ControllerProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->match('/', function (Request $request) use ($app) {
            /** @var User $user */
            $user = $app['session']->get('user');
            if (!$user) {
                return $app->redirect($app['url_generator']->generate('homepage'));
            }

            $projects = ProjectQuery::create()
                ->rightJoinWithMilestone()
                ->find()
            ;

            return $app['twig']->render('board/view.twig', array(
                'user'  => $user,
                'projects'  => $projects,
            ));
        })
        ->bind('board')
        ;

        return $controllers;
    }
}
