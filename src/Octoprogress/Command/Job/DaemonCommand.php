<?php


namespace Octoprogress\Command\Job;

use Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Output\OutputInterface;

use Octoprogress\Command\AbstractCommand,
    Octoprogress\Model\JobQuery,
    Octoprogress\Model\JobPeer;

class DaemonCommand extends AbstractCommand
{
    protected function configure()
    {
        $this
            ->setName('job:daemon')
            ->setDescription("Daemon who run the jobs of the project")
            ->addOption('working-directory-path', 'w', InputOption::VALUE_OPTIONAL)
            ->setDefinition(array(
                new InputOption('polling-delay', null, InputOption::VALUE_REQUIRED, 'Time between each start job (default: 5)', 5),
                new InputOption('max-execution-time', null, InputOption::VALUE_REQUIRED, 'Max execution time (default: 3600)', 3600),
                new InputOption('memory-limit', null, InputOption::VALUE_OPTIONAL, 'Memory limit (exemple: 128M)'),
        ));
    }

    /**
     * @throws \Exception
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return mixed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Job:daemon</info> <comment>started</comment>.');

        $startedAt = time();
        $memoryLimit = $input->getOption('memory-limit');

        // set memory limit
        if (!empty($memoryLimit)) {
            $this->setMemoryLimit($memoryLimit);
            $output->writeln(sprintf('<info>Changing the default memory limit by %s bytes</info>', ini_get('memory_limit')));
        }

        JobQuery::cleanBrokenJobs();

        while (true) {
            // select an idle job
            $idleJob = JobPeer::getOneIdleJob();
            if ($idleJob !== null) {
                try
                {
                    $output->writeln(sprintf('<info>%s</info>', 'execution of the job'));
                    $idleJob->run(array(
                        'rootDir' => $this->getApplication()->getRootDir(),
                        'output' => $output
                    ));
                }
                catch (\Exception $e)
                {
                    $message = sprintf('{job} An error has occurred on job execution : %s', $e->getMessage());
                    $output->writeln(sprintf('<error>%s</error>', $message));
                    exit (0);
                }

                sleep($input->getOption('polling-delay'));
            }

            sleep($input->getOption('polling-delay'));
            if ((time() - $startedAt) > $input->getOption('max-execution-time')) {
                exit (0);
            }
        }
    }

    /**
     * @static
     * @param $value
     * @return bool|int|string
     */
    protected static function setMemoryLimit($value)
    {
        if (($val = self::returnBytes($value)) > self::returnBytes(ini_get('memory_limit'))) {
            if ($ret = ini_set('memory_limit', $val)) {
                return self::returnBytes(ini_get('memory_limit'));
            }
            else {
                return false;
            }
        }
        else {
            return self::returnBytes(ini_get('memory_limit'));
        }
    }

    /**
     * @static
     * @param int|string $val
     * @return int|string
     */
    protected static function returnBytes($val)
    {
        $val = trim($val);
        $last = strtolower($val[strlen($val) - 1]);
        switch ($last) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }

        return $val;
    }
}
