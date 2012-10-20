<?php

namespace Octoprogress\Model;

use Octoprogress\Model\om\BaseMilestonePeer;


/**
 * Skeleton subclass for performing query and update operations on the 'milestone' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.Octoprogress.Model
 */
class MilestonePeer extends BaseMilestonePeer
{
    static public function updateFromGitHub($user, $client)
    {
        $projects = ProjectQuery::create()
            ->filterByUserId($user->getId())
            ->filterByActive(true)
            ->find()
        ;

        $toDeleteQuery = MilestoneQuery::create();

        foreach ($projects as $project)
        {
            $milestonesFromAPI  = $client->get(sprintf('repos/%s/%s/milestones', $project->getGithubUserName(), $project->getName()));

            foreach ($milestonesFromAPI as $milestoneFromAPI)
            {
                $milestone = MilestoneQuery::create()
                    ->filterByGithubId($milestoneFromAPI['id'])
                    ->findOne()
                ;

                if (!$milestone)
                {
                    $milestone = new Milestone();
                }

                if (($timestamp = strtotime($milestoneFromAPI['due_on'])) !== false)
                {
                    $milestone->setDueDate(date('Y-m-d 00:00:00', $timestamp));
                }

                $milestone
                    ->setProjectId($project->getId())
                    ->setGithubId($milestoneFromAPI['id'])
                    ->setName($milestoneFromAPI['title'])
                    ->setDescription($milestoneFromAPI['description'])
                    ->setNumber($milestoneFromAPI['number'])
                    ->setState($milestoneFromAPI['state'])
                    ->setOpenIssues($milestoneFromAPI['open_issues'])
                    ->setClosedIssues($milestoneFromAPI['closed_issues'])
                    ->save()
                ;

                $toDeleteQuery->prune($milestone);
            }
        }

        if ($toDeleteQuery->find()->count())
        {
            $toDeleteQuery->delete();
        }
    }
}
