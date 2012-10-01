<?php

namespace Octoprogress\Controller;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;

use Symfony\Component\HttpFoundation\Request;

use Octoprogress\Model\User,
    Octoprogress\Model\ProjectQuery;

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

                    return $app->redirect($app['url_generator']->generate('account_profile'));
                }
            }

            return $app['twig']->render('project/active_form.html', array(
                'form'  => $form->createView(),
            ));
        })
        ->bind('board_active_form')
        ;

        $controllers->match('/{id}/remove', function (Request $request, $id) use ($app) {
            /** @var User $user */
            $user = $app['session']->get('user');
            if (!$user) {
                return $app->redirect($app['url_generator']->generate('homepage'));
            }

            $project = ProjectQuery::create()
                ->filterById($id)
                ->findOne()
            ;

            $project->setActive(false)->save();

            return $app->redirect($app['url_generator']->generate('account_profile'));
        })
        ->bind('board_remove')
        ;

        return $controllers;
    }
}
