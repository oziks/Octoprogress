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
    Octoprogress\Model\MilestonePeer,
    Octoprogress\Form\Type\ProjectSelectorType;

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


            $form = $app['form.factory']->create(new ProjectSelectorType($app));

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
                'form'          => $form->createView(),
                'user'          => $user,
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
