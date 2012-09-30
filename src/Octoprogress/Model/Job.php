<?php

namespace Octoprogress\Model;

use Octoprogress\Model\om\BaseJob;


/**
 * Skeleton subclass for representing a row from the 'job' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.Octoprogress.Model
 */
class Job extends BaseJob
{
    const HANDLER_CLASS  = '\Octoprogress\Job\%sJobHandler';
    const STATUS_RUNNING = 0;
    const STATUS_SUCCESS = 1;
    const STATUS_IDLE    = 2;
    const STATUS_ERROR   = 3;

    /**
     * @var \Logger
     */
    protected $logger = null;

    /**
     * @var \Job\Job\Interfaces\JobHandler
     */
    protected $jobHandler;

    /**
     * @var array
     */
    protected static $labelTypeJob = array();

    /**
     * restores the previous error handler function
     */
    public function __destruct()
    {
        restore_error_handler();
    }

    /**
     * returns all register type job
     *
     * @static
     * @return array
     */
    public static function getRegisterTypeJob()
    {
        return array_keys(self::$labelTypeJob);
    }

    /**
     * job process shutdown
     *
     * @return void
     */
    public function shutdown()
    {
        if ($this->getStatus() === self::STATUS_RUNNING) {
            // retrieves the last error occurred
            $error = error_get_last();

            $message = '{Job} Process died during execution.';
            if ($error) {
                $message = sprintf('%s Last error was %s on line %s of file %s', $message, $error['message'], $error['line'], $error['file']);
            }

            $this->log($message);

            $this
                ->setStatus(self::STATUS_IDLE)
                ->setMessage($message)
                ->save()
            ;
        }

        restore_error_handler();
    }

    /**
     * returns the status of the job in text format
     *
     * @throws \OutOfRangeException
     * @return string
     */
    public function getStatusText()
    {
        switch($this->getStatus()) {
            case self::STATUS_RUNNING:
                return 'running';

            case self::STATUS_SUCCESS:
                return 'success';

            case self::STATUS_IDLE   :
                return 'idle';

            case self::STATUS_ERROR  :
                return 'error';

            default:
                throw new \OutOfRangeException('Statut \'' . $this->getStatus() . '\'');
        }
    }

    /**
     * run the job
     *
     * @throws \Exception|null
     * @param array $params
     * @return void
     */
    public function run($params)
    {
        // register a function for execution on shutdown
        register_shutdown_function(array($this, 'shutdown'));

        // sets a user-defined error handler function
        set_error_handler(array($this, 'handleRuntimeError'));

        $this->saveStatus(self::STATUS_RUNNING);

        $parameters = $this->getParams();
        $parameters = $parameters !== null ? unserialize($parameters) : array();
        $parameters = array_merge($parameters, $params);

        $jobClass   = sprintf(self::HANDLER_CLASS, str_replace('_', '\\', $this->getType()));
        if (!class_exists($jobClass)) {
            throw new \Exception(sprintf('%s - Could not find any job of type %s', get_class($this), $jobClass));
        }

        $this->jobHandler = new $jobClass($this);
        $runningException = null;

        try
        {
            $this->saveStatus(self::STATUS_RUNNING);
            $this->jobHandler->setJob($this);

            $status = $this->jobHandler->run($parameters);
            if ($status) {
                $status = self::STATUS_SUCCESS;
            }
            else {
                $status = self::STATUS_ERROR;
            }
        }
        catch (\Exception $runningException)
        {
            $status = self::STATUS_ERROR;

            $this->setMessage($runningException->getMessage());
            $this->logException($runningException);
        }

        $this->saveStatus($status, true);

        // restores the previous error handler function
        restore_error_handler();

        if ($runningException !== null) {
            throw $runningException;
        }
    }

    /**
     * returns the label of a job
     * if it is defined
     *
     * @return string
     */
    public function getTypeJob()
    {
        if (array_key_exists($this->getType(), self::$labelTypeJob)) {
            return self::$labelTypeJob[$this->getType()];
        }

        return $this->getType();
    }

    /**
     * returns the array of parameters of this job
     *
     * @throws \Exception
     * @return array|mixed
     */
    public function getArrayOfParams()
    {
        $params        = $this->getParams();
        $arrayOfParams = array();

        if ($params !== null) {
            $arrayOfParams = unserialize(parent::getParams());

            if ($arrayOfParams !== false && ! is_array($arrayOfParams)) {
                throw new \Exception('can not retrieve the array of parameters of this job');
            }
        }

        return $arrayOfParams;
    }

    /**
     * log
     *
     * @param string $message
     * @param int $level
     * @return void
     */
    public function log($message, $level = JobLogPeer::LEVEL_INFO)
    {
        $this->jobHandler->addLog($message, $level);
    }

    /**
     * log exception
     *
     * @param \Exception $e
     * @return void
     */
    public function logException(\Exception $e)
    {
        $this->jobHandler->addLogException($e);
    }

    /**
     * overrides the default save() method. When a job is saved
     * use its id as name if the name is empty
     *
     * @param null|\PropelPDO $con
     * @return void
     */
    public function save(\PropelPDO $con = null)
    {
        if ($this->getName() == '') {
            $this->setName($this->getId());
        }

        parent::save($con);
    }

    /**
     * set and save a status
     *
     * @param int $status
     * @param bool $completed
     * @param null|\PropelPDO $con
     * @return int
     */
    public function saveStatus($status, $completed = false, \PropelPDO $con = null)
    {
        if ($completed) {
            $this->setCompletedAt(time());
        }

        return $this
            ->setStatus($status)
            ->save($con)
            ;
    }

    /**
     * Handles PHP runtime error. Since jobs are executed in the background, we
     * need to capture all error cases.
     *
     * @author  Tristan Rivoallan <trivoallan@clever-age.com>
     * @author  Xavier Lacot <xavier@lacot.org>
     * @see     http://php.net/set_error_handler
     * @param int $errno
     * @param string $errstr
     * @param string $errfile
     * @param int $errline
     * @return void
     */
    public function handleRuntimeError($errno, $errstr, $errfile = null, $errline = null)
    {
        switch ($errno) {
            case E_WARNING:
                $errtype = 'Warning';
                break;

            case E_NOTICE:
                $errtype = 'Notice';
                break;

            case E_USER_ERROR:
                $errtype = 'User Error';
                break;

            case E_USER_WARNING:
                $errtype = 'User Warning';
                break;

            case E_USER_NOTICE:
                $errtype = 'User Notice';
                break;

            case E_STRICT:
                $errtype = 'Strict compliance warning';
                break;

            case E_RECOVERABLE_ERROR:
                $errtype = 'Catchable Fatal Error';
                break;

            default:
                $errtype = 'Unknown(' . $errno . ')';
        }

        $msg = sprintf('%s : "%s" occured in %s on line %d',
            $errtype, $errstr, $errfile, $errline);

        switch ($errno) {
            case E_NOTICE:
            case E_WARNING:
                $this->setMessage($msg);
                break;

            default:
                $this->setStatus(self::STATUS_IDLE);
                $this->setMessage($msg);
        }

        $this->save();
    }
}
