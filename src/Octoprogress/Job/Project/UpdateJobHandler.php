<?php

namespace Octoprogress\Job\Project;

use Octoprogress\Job\AbstractJobHandler,
    Octoprogress\Model\JobLogPeer,
    Octoprogress\Model\UserQuery,
    Octoprogress\Model\ProjectQuery,
    Octoprogress\Model\Project;

use Github\Client as GithubClient,
    Github\HttpClient\HttpClient as GithubHttpClient;
 
class UpdateJobHandler extends AbstractJobHandler
{
    public static function getName()
    {
        return "Project_Update";
    }

    /**
     * @throws \Exception
     * @param array $params
     * @return void
     */
    public function run(array $params)
    {
        $this->addLog(sprintf('Run %s', self::getName()), JobLogPeer::LEVEL_INFO);
        $startedAt = time();

        try
        {
            if (empty($params['user_id'])) {
                throw new \Exception('The `user_id` parameter not found.');
            }

            $user = UserQuery::create()
                ->filterById($params['user_id'])
                ->findOne()
            ;
            
            if (!$user) {
                throw new \Exception('User not found.');
            }

            $github = new GithubClient(new GithubHttpClient(array(
                'login'       => $user->getLogin(),
                'token'       => $user->getAccessToken(),
                'auth_method' => GithubClient::AUTH_HTTP_TOKEN
            )));

            $github->getHttpClient()->authenticate();
            $repositories = $github->get('user/repos');

            foreach ($repositories as $repository) {
                $milestones = $github->api('issue')->milestones()->all($user->getLogin(), $repository['name']);
                if (count($milestones) > 0 && empty($milestones['message'])) {
                    $project = ProjectQuery::create()
                        ->filterByGithubId($repository['id'])
                        ->findOne()
                    ;

                    if (!$project) {
                        $project = new Project();
                    }

                    $project
                        ->setUserId($user->getId())
                        ->setGithubId($repository['id'])
                        ->setName($repository['name'])
                        ->setDescription($repository['description'])
                        ->save()
                    ;
                }
            }
        }
        catch (\Exception $exception)
        {
            $message = sprintf('An error occurred: ', $exception->getMessage());
            $this->addLog($message);

            throw new \Exception(sprintf('Failed: %s - %s', $message, $exception->getCode()));
        }

        $duration = time() - $startedAt;
        $this->addLog(sprintf('Success: on %ss.', $duration), JobLogPeer::LEVEL_INFO);

        return true;
    }
}
