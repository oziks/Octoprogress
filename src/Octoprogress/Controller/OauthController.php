<?php

namespace Octoprogress\Controller;

use Silex\Application,
    Silex\ControllerCollection,
    Silex\ControllerProviderInterface;

use OAuth2\Client as OAuth2Client;

use Github\Client as GithubClient,
    Github\HttpClient\HttpClient as GithubHttpClient;

use Octoprogress\Model\User,
    Octoprogress\Model\UserQuery,
    Octoprogress\Model\ProjectPeer,
    Octoprogress\Model\MilestonePeer;

class OauthController implements ControllerProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/connect', function () use ($app) {
            $github  = $app['config']->get('github');
            $client  = new OAuth2Client($github['client_id'], $github['client_secret']);
            $authUrl = $client->getAuthenticationUrl($github['authorization_endpoint'], $github['redirect_uri']);

            return $app->redirect($authUrl);
        })
        ->bind('oauth')
        ;

        $controllers->get('/connect-private', function () use ($app) {
            $github  = $app['config']->get('github');
            $client  = new OAuth2Client($github['client_id_private'], $github['client_secret_private']);
            $authUrl = $client->getAuthenticationUrl($github['authorization_endpoint'], $github['redirect_uri_private']);

            return $app->redirect($authUrl . '&' . http_build_query(array('scope' => $github['scope_private'])));
        })
        ->bind('oauth_private')
        ;

        $controllers->get('/callback', function () use ($app) {
            $github = $app['config']->get('github');

            $user = $this->getAuthenticatedUser(
                $app,
                new OAuth2Client($github['client_id'], $github['client_secret']),
                $github['token_endpoint'],
                array('code' => $_GET['code'], 'redirect_uri' =>  $github['redirect_uri']),
                false
            );

            $app['session']->set('isAuthenticated', true);
            $app['session']->set('user', $user);

            return $app->redirect($app['url_generator']->generate('board'));
        });

        $controllers->get('/callback-private', function () use ($app) {
            $github = $app['config']->get('github');

            $user = $this->getAuthenticatedUser(
                $app,
                new OAuth2Client($github['client_id_private'], $github['client_secret_private']),
                $github['token_endpoint'],
                array('code' => $_GET['code'], 'redirect_uri' =>  $github['redirect_uri_private']),
                true
            );

            $app['session']->set('isAuthenticated', true);
            $app['session']->set('user', $user);

            return $app->redirect($app['url_generator']->generate('board'));
        });

        $controllers->get('/logout', function () use ($app) {
            $app['session']->set('isAuthenticated', false);
            $app['session']->set('user', null);

            return $app->redirect($app['url_generator']->generate('homepage'));
        })
        ->bind('logout')
        ;

        return $controllers;
    }

    protected function getAuthenticatedUser(Application $app, OAuth2Client $client, $endpoint, $params, $privateAccess)
    {
        $response = $client->getAccessToken($endpoint, 'authorization_code', $params);

        parse_str($response['result'], $oauthInfo);

        if (isset($oauthInfo['error']))
        {
            throw new \Exception($oauthInfo['error']);
        }

        $user = UserQuery::create()
            ->filterByAccessToken($oauthInfo['access_token'])
            ->findOne()
        ;

        if (!$user)
        {
            $client->setAccessToken($oauthInfo['access_token']);
            $userInfo = $client->fetch('https://api.github.com/user');

            $user = UserQuery::create()
                ->filterByGithubId($userInfo['result']['id'])
                ->findOne()
            ;

            if (!$user)
            {
                $user = new User();
                $user
                    ->setGithubId($userInfo['result']['id'])
                    ->setGithubProfile($userInfo['result']['html_url'])
                    ->setLogin($userInfo['result']['login'])
                    ->setCompany($userInfo['result']['company'])
                    ->setEmail($userInfo['result']['email'])
                    ->setAvatarUrl($userInfo['result']['avatar_url'])
                    ->setName($userInfo['result']['name'])
                    ->setLocation($userInfo['result']['location'])
                ;
            }
        }

        $oldAccess = $user->getPrivateAccess();

        $user
            ->setAccessToken($oauthInfo['access_token'])
            ->setPrivateAccess($privateAccess)
            ->setUpdatedAt('now')
            ->save()
        ;


        if ($user->getPrivateAccess() != $oldAccess)
        {
            $this->updateProjects($user);
        }

        return $user;
    }

    protected function updateProjects($user)
    {
        $github = new GithubClient(new GithubHttpClient(array(
            'login'       => $user->getLogin(),
            'token'       => $user->getAccessToken(),
            'auth_method' => GithubClient::AUTH_HTTP_TOKEN
        )));

        $github->getHttpClient()->authenticate();

        ProjectPeer::updateFromGitHub($user, $github);
    }
}
