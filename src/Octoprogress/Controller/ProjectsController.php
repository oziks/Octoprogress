<?php

namespace Octoprogress\Controller;

use Silex\Application,
    Silex\ControllerCollection,
    Silex\ControllerProviderInterface,
    Symfony\Component\HttpFoundation\Request;

use Github\Client as GithubClient,
    Github\HttpClient\HttpClient as GithubHttpClient;

use Octoprogress\Model\Project,
    Octoprogress\Model\ProjectPeer,
    Octoprogress\Model\ProjectQuery,
    Octoprogress\Model\MilestonePeer;

class ProjectsController implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->match('/', function (Request $request) use ($app) {
            $user = $app['session']->get('user');
            if (!$user)
            {
                return $app->redirect($app['url_generator']->generate('homepage'));
            }

            $projects = ProjectQuery::create()
                ->filterByUserId($user->getId())
                ->find()
            ;

            $choices = array();
            foreach ($projects as $project) {
                $choices[$project->getId()] = $project->getName();
            }

            $active = array();
            foreach ($projects as $project) {
                if ($project->getActive())
                {
                   $active[] = $project->getId();
                }
            }

            $form = $app['form.factory']->createBuilder('form')
                ->add('projects', 'choice', array(
                    'choices'   => $choices,
                    'multiple'  => true,
                    'expanded'  => true,
                    'data'      => $active,
                ))
                ->getForm()
            ;

            if ($request->getMethod() === 'POST') {
                $form->bindRequest($request);
                if ($form->isValid()) {
                    foreach ($projects as $project)
                    {
                        $project->setActive(in_array($project->getId(), $form['projects']->getData()))->save();
                    }

                    return $app->redirect($app['url_generator']->generate('projects'));
                }
            }

            return $app['twig']->render('projects/list.twig', array(
                'selected_menu' => 'projects',
                'user'          => $user,
                'form'          => $form->createView(),
            ));
        })->bind('projects');

        $controllers->get('/refresh', function () use ($app) {
            $user = $app['session']->get('user');
            if (!$user)
            {
                return $app->redirect($app['url_generator']->generate('homepage'));
            }

            $github = new GithubClient(new GithubHttpClient(array(
                'login'       => $user->getLogin(),
                'token'       => $user->getAccessToken(),
                'auth_method' => GithubClient::AUTH_HTTP_TOKEN
            )));

            $github->getHttpClient()->authenticate();

            ProjectPeer::updateFromGitHub($user, $github);
            MilestonePeer::updateFromGitHub($user, $github);

            return $app->redirect($app['url_generator']->generate('projects'));
        })->bind('projects_refresh');

        return $controllers;
    }
}
