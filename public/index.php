<?php

/**
 * @define Document root
 * @define Application path
 * @define Staging development
 */
defined('DOCUMENT_ROOT') || define('DOCUMENT_ROOT', $_SERVER["DOCUMENT_ROOT"]);
defined('APP_PATH') || define('APP_PATH', DOCUMENT_ROOT . '/../apps');

defined('APPLICATION_ENV') ||
define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Require configurations
require_once APP_PATH . '/config/application.php';

// Require composite libraries
require_once DOCUMENT_ROOT . ' /../vendor/autoload.php';

// Create factory container
$di = new Phalcon\DI\FactoryDefault();

// Set default routes
$di->set('router', function () {

    $router = new \Phalcon\Mvc\Router();
    $router->removeExtraSlashes(true)
        ->setDefaults([
            'module' => 'Frontend',
            'controller' => 'index',
            'action' => 'index'
        ]);

    require APP_PATH . '/config/routes.php';
    return $router;

});

// Set global configuration
$di->set('config', function () use ($config) {
    return (new \Phalcon\Config($config));
});

try {

    $application = new Phalcon\Mvc\Application($di);
    // Register modules
    $application->registerModules([
        'Frontend' => [
            'className' => 'Modules\Frontend',
            'path' => APP_PATH . '/Modules/Frontend/Module.php',
        ],
        'Backend' => [
            'className' => 'Modules\Backend',
            'path' => APP_PATH . '/Modules/Backend/Module.php',
        ],
    ])->setDefaultModule('Frontend');

    if (APPLICATION_ENV === 'development') {
        // require whoops
        new Whoops\Provider\Phalcon\WhoopsServiceProvider($di);
    }

    // Handle the request
    echo $application->handle()->getContent();

} catch (\Exception $e) {
    echo $e->getMessage();
}
