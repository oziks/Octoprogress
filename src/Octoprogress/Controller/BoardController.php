<?php

namespace Octoprogress\Controller;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;

use Symfony\Component\HttpFoundation\Request;

use Github\Client as GithubClient,
    Github\HttpClient\HttpClient as GithubHttpClient;

use Octoprogress\Model\User,
    Octoprogress\Model\ProjectQuery,
    Octoprogress\Model\Job,
    Octoprogress\Model\MilestonePeer;

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

            return $app['twig']->render('board/view.twig', array(
                'selected_menu' => 'board',
                'user'  => $user,
            ));
        })
        ->bind('board')
        ;

        $controllers->post('/refresh', function (Request $request) use ($app) {
            /** @var User $user */
            $user = $app['session']->get('user');
            if (!$user) {
                return $app->redirect($app['url_generator']->generate('homepage'));
            }

            $github = new GithubClient(new GithubHttpClient(array(
                'login'       => $user->getLogin(),
                'token'       => $user->getAccessToken(),
                'auth_method' => GithubClient::AUTH_HTTP_TOKEN
            )));

            $github->getHttpClient()->authenticate();

            MilestonePeer::updateFromGitHub($user, $github);

            $projects = ProjectQuery::create()
                ->rightJoinWithMilestone()
                ->filterByUserId($user->getId())
                ->filterByActive(1)
                ->find()
            ;

            return $app['twig']->render('board/refresh.twig', array(
                'projects'      => $projects
            ));
        })
        ->bind('board_refresh')
        ;

        return $controllers;
    }
}
