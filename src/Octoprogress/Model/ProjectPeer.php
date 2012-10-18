<?php

namespace Octoprogress\Model;

use Octoprogress\Model\om\BaseProjectPeer;

/**
 * Skeleton subclass for performing query and update operations on the 'project' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.Octoprogress.Model
 */
class ProjectPeer extends BaseProjectPeer
{
    static public function updateFromGitHub($user, $client)
    {
        $toDeleteQuery  = ProjectQuery::create();

        $projectsFromAPI      = $client->get('user/repos');
        $organisationsFromAPI = $client->get('user/orgs');

        foreach ($organisationsFromAPI as $organisationFromAPI)
        {
            $organisationProjectsFromAPI = $client->get(sprintf('orgs/%s/repos', $organisationFromAPI['login']));
            foreach ($organisationProjectsFromAPI as $organisationProjectFromAPI)
            {
                $projectsFromAPI[] = $organisationProjectFromAPI;
            }
        }

        foreach ($projectsFromAPI as $projectFromAPI) {
            $project = ProjectQuery::create()
                ->filterByGithubId($projectFromAPI['id'])
                ->findOne()
            ;

            if (!$project)
            {
                $project = new Project();
                $project->setActive(false);
            }

            $project
                ->setUserId($user->getId())
                ->setGithubId($projectFromAPI['id'])
                ->setGithubUserName($projectFromAPI['owner']['login'])
                ->setName($projectFromAPI['name'])
                ->setDescription($projectFromAPI['description'])
                ->save()
            ;

            $toDeleteQuery->prune($project);
        }

        $toDeleteQuery->delete();
    }
}
