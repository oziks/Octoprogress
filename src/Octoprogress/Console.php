<?php

namespace Octoprogress;

use Symfony\Component\Console\Application as ConsoleApplication;
use Silex\Application as SilexApplication;

/**
 * Application.
 *
 * @author Ludovic Fleury <ludo.fleury@gmail.com>
 */
class Console extends ConsoleApplication
{
    private $silexApplication;

    /**
     * Constructor.
     *
     * @param Silex\Appplication $silexApplication
     * @param string             $name              The name of the application
     * @param string             $version           The version of the application
     */
    public function __construct(SilexApplication $silexApplication, $name = 'UNKNOWN', $version = 'UNKNOWN')
    {
        parent::__construct($name, $version);

        $this->silexApplication = $silexApplication;
    }

    /**
     * Return the current silex application
     *
     * @return Silex\Application
     */
    public function getSilexApplication()
    {
        return $this->silexApplication;
    }

    public function getRootDir()
    {
        return isset($this->silexApplication['config']) ? $this->silexApplication['config']->get('root_dir') : null;
    }
}