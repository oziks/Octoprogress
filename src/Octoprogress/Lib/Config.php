<?php

namespace Octoprogress\Lib;

class Config
{
    protected $data = array();

    public function __construct($appRootDir)
    {
        $this
            ->addParams(array('root_dir' => rtrim($appRootDir, DIRECTORY_SEPARATOR)))
            ->generate()
        ;
    }

    public function __get($name)
    {
        $utils = new Utils();

        return $this->get($utils->underscore($name));
    }

    public function __call($method, $args)
    {
        if (strpos($method, 'get') === 0 && strlen($method) > 3)
        {
            $utils = new Utils();

            return $this->get($utils->underscore(substr($method, 3)));
        }
        throw new \Exception('Config : unknown function '.$method);
    }

    public function get($param)
    {
        if (!isset($this->data[$param]))
        {
            throw new \Exception('Config : unknown param '.$param);
        }

        return $this->data[$param];
    }

    public function addParams(array $params)
    {
        $this->data = $params + $this->data;

        return $this;
    }

    public function addParam($name, $value)
    {
        return $this->addParams(array($name => $value));
    }

    protected function generate()
    {
        return $this
            ->addParam('config_dir', sprintf('%s/app/config', $this->data['root_dir']))
        ;
    }
}
