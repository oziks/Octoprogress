<?php

namespace Octoprogress\Model;

use Octoprogress\Model\om\BaseUser;


/**
 * Skeleton subclass for representing a row from the 'user' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.Octoprogress.Model
 */
class User extends BaseUser
{
    public function __toString()
    {
        return $this->login();
    }
}
