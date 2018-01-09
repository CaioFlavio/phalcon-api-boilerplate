<?php

use Phalcon\Config\Adapter\Ini;
use Phalcon\Loader;
use Phalcon\Di\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Events\Manager;
use Phalcon\Mvc\Micro;

// Error level
error_reporting(E_ALL ^ E_NOTICE);

// Defining Constants 
define('DS', DIRECTORY_SEPARATOR);
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . DS);

// Application Config
$appConfig = new Ini('config.ini');
$appServices = $appConfig->services;

// Initializing Vars
$loadRoutes         = [];
$loadNamespaces     = [];
$defaultDirs        = [
    BASE_PATH . $config->application->migrationsDir,
];
$defaultNamespaces  = [
    'MicroService\Application' => BASE_PATH,
];
// Create Phalcon Dependency Injection
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
$phalconMicro = new Micro($phalconDependencyInjector);

// Loading Service Data
foreach ($appServices as $serviceName => $servicePath) {
    $serviceDirectory = BASE_PATH . $servicePath;
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

// Autoloader
$phalconLoader = new Loader();

$appNamespaces = array_merge(
    $defaultNamespaces, 
    $loadNamespaces
);

// Autoloading Namespaces
$phalconLoader->registerNamespaces($appNamespaces);

// Autoloading Directories
$phalconLoader->registerDirs($defaultDirs);

// Autoload Register
$phalconLoader->register();

// Load Service Routes
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
// Application Start
$phalconMicro->handle();

