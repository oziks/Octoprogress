<?php

namespace Octoprogress\Controller;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;

use Octoprogress\Model\User,
    Octoprogress\Model\Job;

class JobController implements ControllerProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/project/update', function () use ($app) {
            /** @var User $user */
            $user = $app['session']->get('user');
            if (!$user) {
                return $app->redirect($app['url_generator']->generate('homepage'));
            }

            $job = new Job();
            $job
                ->setName('Project update')
                ->setType('Project_Update')
                ->setParams(serialize(array('user_id' => $user->getId())))
                ->save()
            ;

            return $app->redirect($app['url_generator']->generate('account_profile'));
        })
        ->bind('job_project_update')
        ;

        $controllers->get('/project/update', function () use ($app) {
            /** @var User $user */
            $user = $app['session']->get('user');
            if (!$user) {
                return $app->redirect($app['url_generator']->generate('homepage'));
            }

            $job = new Job();
            $job
                ->setName('Project update')
                ->setType('Project_Update')
                ->setParams(serialize(array('user_id' => $user->getId())))
                ->save()
            ;

            return $app->redirect($app['url_generator']->generate('account_profile'));
        })
        ->bind('job_project_update')
        ;

        return $controllers;
    }
}
