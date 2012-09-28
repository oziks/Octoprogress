<?php 

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Octoprogress\Lib\Config;
use Symfony\Component\Yaml\Yaml;
use Propel\Silex\PropelServiceProvider;

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

$app->register(new Silex\Provider\SessionServiceProvider());

$app->register(new UrlGeneratorServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new TwigServiceProvider(), array(
    'twig.path'    => array(
        $app['config']->get('root_dir').'/app/Resources/views',
        $app['config']->get('root_dir').'/src/Octoprogress/Resources/views'
    ),
    'twig.options' => array('cache' => $app['config']->get('root_dir').'/app/cache'),
));
$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    // add custom globals, filters, tags, ...

    return $twig;
}));

return $app;