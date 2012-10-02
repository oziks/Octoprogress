<?php

namespace Octoprogress\Job\Milestone;

use Octoprogress\Job\AbstractJobHandler,
    Octoprogress\Model\JobLogPeer,
    Octoprogress\Model\UserQuery,
    Octoprogress\Model\ProjectQuery,
    Octoprogress\Model\MilestoneQuery,
    Octoprogress\Model\Milestone;

use Github\Client as GithubClient,
    Github\HttpClient\HttpClient as GithubHttpClient;
 
class UpdateJobHandler extends AbstractJobHandler
{
    public static function getName()
    {
        return "Milestone_Update";
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

            if (empty($params['project_id'])) {
                throw new \Exception('The `project_id` parameter not found.');
            }

            $project = ProjectQuery::create()
                ->filterById($params['project_id'])
                ->findOne()
            ;
            
            if (!$project) {
                throw new \Exception('User not found.');
            }

            $github = new GithubClient(new GithubHttpClient(array(
                'login'       => $user->getLogin(),
                'token'       => $user->getAccessToken(),
                'auth_method' => GithubClient::AUTH_HTTP_TOKEN
            )));

            $github->getHttpClient()->authenticate();
            $milestones = $github->get(sprintf('repos/%s/%s/milestones', $user->getLogin(), 'octoprogress'));

            foreach ($milestones as $milestone) {
                $oMilestone = MilestoneQuery::create()
                    ->filterByGithubId($milestone['id'])
                    ->findOne()
                ;

                if (!$oMilestone) {
                    $oMilestone = new Milestone();
                }

                $oMilestone
                    ->setProjectId($project->getId())
                    ->setGithubId($milestone['id'])
                    ->setName($milestone['title'])
                    ->setDescription($milestone['description'])
                    ->setState($milestone['state'])
                    ->setOpenIssues($milestone['open_issues'])
                    ->setClosedIssues($milestone['closed_issues'])
                    ->save()
                ;
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
