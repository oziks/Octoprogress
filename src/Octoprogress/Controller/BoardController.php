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

        $controllers->match('/active_form', function (Request $request) use ($app) {
            /** @var User $user */
            $user = $app['session']->get('user');
            if (!$user) {
                return $app->redirect($app['url_generator']->generate('homepage'));
            }

            $repositories = ProjectQuery::create()
                ->select(array('id', 'name'))
                ->filterByUserId($user->getId())
                ->filterByActive(false)
                ->find()
                ->toArray()
            ;

            $choices = array();
            foreach ($repositories as $repository) {
                $choices[$repository['id']] = $repository['name'];
            }

            $form = $app['form.factory']->createBuilder('form')
                ->add('project', 'choice', array(
                    'choices' => $choices,
                    'label' => 'Project'
                ))
                ->getForm()
            ;

            if ($request->getMethod() === 'POST') {
                $form->bindRequest($request);
                if ($form->isValid()) {
                    $project = ProjectQuery::create()
                        ->filterById($form['project']->getData())
                        ->findOne()
                    ;

                    $project->setActive(true)->save();
                    
                    $job = new Job();
                    $job
                        ->setName('Milestone update')
                        ->setType('Milestone_Update')
                        ->setParams(serialize(array(
                            'user_id'    => $user->getId(),
                            'project_id' => $project->getId(),
                        )))
                        ->save()
                    ;

                    return $app->redirect($app['url_generator']->generate('account_profile'));
                }
            }

            return $app['twig']->render('project/active_form.html', array(
                'form'  => $form->createView(),
            ));
        })
        ->bind('board_active_form')
        ;

        $controllers->match('/status', function (Request $request) use ($app) {
            /** @var User $user */
            $user = $app['session']->get('user');
            if (!$user) {
                return $app->redirect($app['url_generator']->generate('homepage'));
            }

            $projects = ProjectQuery::create()
                ->rightJoinWithMilestone()
                ->find()
            ;

            return $app['twig']->render('board/view.html', array(
                'user'  => $user,
                'projects'  => $projects,
            ));
        })
        ->bind('board')
        ;

        return $controllers;
    }
}
