<?php

namespace Octoprogress\Job;

use Octoprogress\Model\Job;
use Octoprogress\Model\JobLog;
use Octoprogress\Model\JobLogPeer;

abstract class AbstractJobHandler
{
    /**
     * @var Job
     */
    protected $job;

    /**
     * @var Logger
     */
    protected $logger;

    public function __construct(Job $job)
    {
        $this->job = $job;
    }

    public function setJob(Job $job)
    {
        $this->job = $job;
    }

    /**
     * @return Job
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * @param string $message
     * @param string $level
     * @return void
     */
    public function addLog($message, $level = JobLogPeer::LEVEL_ERROR)
    {
        $this->job
            ->setMessage($message)
            ->save()
        ;

        $entry = new JobLog();
        $entry
            ->setMessage($message)
            ->setLevel($level)
            ->setJobId($this->job->getId())
            ->save()
        ;
    }

    /**
     *
     * @param \Exception $exception
     * @param string $level
     *
     * @return void
     */
    public function addLogException(\Exception $exception, $level = JobLogPeer::LEVEL_ERROR)
    {
        $exceptionLog = sprintf('{Exception} %s in %s(%s)', $exception->getMessage(), $exception->getFile(), $exception->getLine()) . PHP_EOL;

        foreach ($exception->getTrace() as $trace) {
            //backtrace de l'exception
            $class = '';
            if (!empty($trace['class'])) {
                $class = $trace['class'];
            }

            $function = '';
            if (!empty($trace['function'])) {
                $function = (isset($class) ? '->' : '') . $trace['function'];

                $args = '';
                if (!empty($trace['args'])) {
                    foreach ($trace['args'] as $arg) {
                        if (is_object($arg)) {
                            $args .= 'object(\'' . get_class($arg) . '\'), ';
                        }
                        elseif (is_string($arg))        {
                            $args .= $arg . ', ';
                        }
                    }
                    $args = substr($args, 0, -2);
                }
                $args = '(' . $args . ')';
            }

            $file = '';
            $line = '';
            if (!empty($trace['file'])) {
                $file = $trace['file'];

                if (!empty($trace['line'])) {
                    $line = '(' . $trace['line'] . ')';
                }
            }

            $exceptionLog .= sprintf('└> at %s%s%s in %s%s', $class, $function, $args, $file, $line) . PHP_EOL;
        }

        $entry = new JobLog();
        $entry
            ->setMessage($exceptionLog)
            ->setLevel($level)
            ->setJobId($this->job->getId())
            ->save();
    }

    /**
     * Executes the job.
     *
     * @param array $params An array of valued job parameters. The key is the
     * parameter name (as what returns the getParamFields() method), and the value
     * is the parameter's value.
     *
     * @return  boolean true if job execution succeeded.
     */
    abstract public function run(array $params);
}
