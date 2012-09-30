<?php

namespace Octoprogress\Controller;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;

use OAuth2\Client as OAuth2Client;

use Octoprogress\Model\User;
use Octoprogress\Model\UserQuery;

class OauthController implements ControllerProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/connect', function () use ($app) {
            $github = $app['config']->get('github');

            $client    = new OAuth2Client($github['client_id'], $github['client_secret']);
            $authUrl   = $client->getAuthenticationUrl($github['authorization_endpoint'], $github['redirect_uri']);

            // redirect github sign-in
            return $app->redirect($authUrl . '&' . http_build_query(array('scope' => $github['scope'])));
        })
        ->bind('oauth')
        ;

        $controllers->get('/callback', function () use ($app) {
            $github = $app['config']->get('github');

            $client     = new OAuth2Client($github['client_id'], $github['client_secret']);

            $params   = array('code' => $_GET['code'], 'redirect_uri' =>  $github['redirect_uri']);
            $response = $client->getAccessToken($github['token_endpoint'], 'authorization_code', $params);

            parse_str($response['result'], $info);

            if (isset($info['error'])) {
                throw new \Exception($info['error']);
            }

            $client->setAccessToken($info['access_token']);

            $user = UserQuery::create()
                ->filterByAccessToken($info['access_token'])
                ->findOne()
            ;

            if (!$user) {
                $user = new User();
                $user->setAccessToken($info['access_token']);

                $response = $client->fetch('https://api.github.com/user');
                $user
                    ->setGithubId($response['result']['id'])
                    ->setGithubProfile($response['result']['html_url'])
                    ->setLogin($response['result']['login'])
                    ->setCompany($response['result']['company'])
                    ->setEmail($response['result']['email'])
                    ->setAvatarUrl($response['result']['avatar_url'])
                    ->setName($response['result']['name'])
                    ->setLocation($response['result']['location'])
                    ->save()
                ;
            }

            $app['session']->set('isAuthenticated', true);
            $app['session']->set('user', $user);

            return $app->redirect($app['url_generator']->generate('account'));
        });

        $controllers->get('/logout', function () use ($app) {
            $app['session']->set('isAuthenticated', false);
            $app['session']->set('user', null);

            // redirect homepage
            return $app->redirect($app['url_generator']->generate('homepage'));
        })
        ->bind('logout')
        ;

        return $controllers;
    }
}
