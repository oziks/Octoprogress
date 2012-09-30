<?php
namespace Octoprogress\Command;

use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Command\Command as BaseCommand;

/**
 * Application aware command
 *
 * Provide a silex application in CLI context.
 */
class AbstractCommand extends BaseCommand
{
    protected $formatterHelper = null;

    /**
     * Return the current silex application
     *
     * @return Silex\Application
     */
    public function getSilexApplication()
    {
        return $this->getApplication()->getSilexApplication();
    }

    /**
     * Return an instance of FormatterHelper
     *
     * @return Symfony\Component\Console\Helper\FormatterHelper
     */
    public function getFormatterHelper()
    {
        return is_null($this->formatterHelper) ? $this->formatterHelper = new FormatterHelper() : $this->formatterHelper;
    }
}