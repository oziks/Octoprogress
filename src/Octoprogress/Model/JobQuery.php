<?php

namespace Octoprogress\Model;

use Octoprogress\Model\om\BaseJobQuery;


/**
 * Skeleton subclass for performing query and update operations on the 'job' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.Octoprogress.Model
 */
class JobQuery extends BaseJobQuery
{
    public static function cleanBrokenJobs(\Criteria $criteria = null, \PropelPDO $con = null)
    {
        if ($con === null) {
            $con = \Propel::getConnection();
        }

        $con->beginTransaction();

        try
        {
            $jobs = JobPeer::getRunningJob($criteria, $con);
            foreach ($jobs as $job) {
                $job->setStatus(Job::STATUS_ERROR);
                $job->save();

                $logEntry = new JobLog();
                $logEntry->setMessage("<strong>Error: The job was interrupted before the end of treatment. Contact technical support.</strong>");
                $logEntry->setLevel('info');
                $logEntry->setJob($job);
                $logEntry->save();
            }

            $con->commit();
        }
        catch (\Exception $exception)
        {
            $con->rollBack();

            throw $exception;
        }
    }
}
