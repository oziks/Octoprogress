<?php 

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Octoprogress\Lib\Config;
use Symfony\Component\Yaml\Yaml;
use Propel\Silex\PropelServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TranslationServiceProvider;

$app = new Application();

$app['config'] = $app->share(function() {
    return new Config(dirname(__DIR__));
});

$app['config']->addParams(array(
    'github' => Yaml::parse(sprintf('%s/github.yml', $app['config']->get('config_dir'))),
));

$app->register(new PropelServiceProvider(), array(
    'propel.config_file' => $app['config']->get('config_dir').'/Propel/conf/Octoprogress-conf.php',
    'propel.model_path'  => $app['config']->get('root_dir').'/src',
));

$app->register(new FormServiceProvider());
$app->register(new TranslationServiceProvider());
$app->register(new SessionServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new UrlGeneratorServiceProvider());
$app->register(new TwigServiceProvider(), array(
    'twig.path'    => $app['config']->get('root_dir').'/src/Octoprogress/Resources/views',
    'twig.options' => array('cache' => $app['config']->get('root_dir').'/app/cache'),
));

return $app;