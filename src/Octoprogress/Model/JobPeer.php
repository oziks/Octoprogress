<?php

namespace Octoprogress\Model;

use Octoprogress\Model\om\BaseJobPeer;


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
class JobPeer extends BaseJobPeer
{
  /**
   * @static
   * @param \Criteria|null $criteria
   * @param null|\PropelPDO $con
   * @return Job
   */
  public static function getOneIdleJob(\Criteria $criteria = null, \PropelPDO $con = null)
  {
    return JobQuery::create(null, $criteria)
      ->filterByStatus(Job::STATUS_IDLE)
      ->orderById(\Criteria::ASC)
      ->findOne($con)
    ;
  }

  /**
   * @static
   * @param \Criteria|null $criteria
   * @param null|\PropelPDO $con
   * @return Job
   */
  public static function getRunningJob(\Criteria $criteria = null, \PropelPDO $con = null)
  {
    return JobQuery::create(null, $criteria)
      ->filterByStatus(Job::STATUS_RUNNING)
      ->find($con)
    ;
  }

  /**
   * @static
   * @param \Criteria|null $criteria
   * @param null|\PropelPDO $con
   * @return Job
   */
  public static function countJobPatchRunningOrIdle(\Criteria $criteria = null, \PropelPDO $con = null)
  {
    return JobQuery::create(null, $criteria)
      ->filterByStatus(array(Job::STATUS_RUNNING, Job::STATUS_IDLE))
      ->filterByType(Job::getRegisterTypeJob())
      ->count($con)
    ;
  }
}
