<?php

use Phalcon\Config\Adapter\Ini;
use Phalcon\Loader;
use Phalcon\Di\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Events\Manager;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Router;
use ApiAuth\Middleware\RequestMiddleware;
// Error level
error_reporting(E_ALL ^ E_NOTICE);

// Constants 
define('DS', DIRECTORY_SEPARATOR);
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . DS);

// Config
$appConfig   = new Ini('config.ini');
$appFolders  = $appConfig->application->folder;
$appServices = $appConfig->services;

// Vars
$loadRoutes         = [];
$loadNamespaces     = [];
$defaultDirs        = [
    BASE_PATH . $config->application->migrationsDir,
];
$defaultNamespaces  = [
    'MicroService\Application' => BASE_PATH,
];

// Dependency Injection
$phalconDependencyInjector = new FactoryDefault();
$phalconDependencyInjector->set(
    'db',
    function () use ($appConfig) {
        return new DbAdapter(
            [
                'host'     => $appConfig->database->host,
                'username' => $appConfig->database->username,
                'password' => $appConfig->database->password,
                'dbname'   => $appConfig->database->dbname,
            ]
        );
    }
);

// Micro Application Start
$phalconMicro              = new Micro($phalconDependencyInjector);
$eventsManager             = new Manager();
$phalconLoader             = new Loader();
// $phalconRouter             = new Router(false);

// Services
foreach ($appServices as $serviceName => $servicePath) {
    $serviceDirectory = BASE_PATH . $appFolders->services . DS . $servicePath;
    $serviceConfig    = new Ini($serviceDirectory . DS . $appConfig->application->configFile);
    
    // Load Namespaces
    if ($serviceNamespaces = $serviceConfig->namespaces) {
        foreach ($serviceNamespaces as $namespace => $location) {
            $loadNamespaces[$namespace] = $serviceDirectory . $location;
        }
    }

    // Load Routes
    if ($serviceRoute = $serviceConfig->initialize->routes) {
        $loadRoutes[$service->name] = $serviceDirectory . $serviceRoute;
    }
}
// Autoloading
$appNamespaces = array_merge($defaultNamespaces, $loadNamespaces);
$phalconLoader->registerNamespaces($appNamespaces);
$phalconLoader->registerDirs($defaultDirs);
$phalconLoader->register();

// Routes
foreach ($loadRoutes as $serviceName => $routePath) {
    $routeFiles = scandir($routePath);
    foreach ($routeFiles as $routeFile) {
        $currentRoute = $routePath . DS . $routeFile;
        $fileExtension = new SplFileInfo($currentRoute);
        if (strtolower($fileExtension->getExtension()) === 'php') {
            require_once($routePath . DS . $routeFile);
        }
    }
}

// Events Manager
// Header validation
$eventsManager->attach('micro', new RequestMiddleware());
$phalconMicro->before(new RequestMiddleware());

// User validation
// TO DO

// Application Start
// $phalconMicro->setService('router', $phalconRouter, true);
$phalconMicro->setEventsManager($eventsManager);
$phalconMicro->handle();

