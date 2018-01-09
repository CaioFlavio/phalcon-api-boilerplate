<?php
use Phalcon\Events\Manager;
use Phalcon\Mvc\Micro;
use TrackingApi\Middleware\AuthMiddleware;
use TrackingApi\Middleware\RequestMiddleware;


/*
 Routes loader 
*/


/*
 Middlewares
*/
$eventsManager = new Manager();

// Request Type Validation
$eventsManager->attach('micro', new RequestMiddleware());
$app->before(new RequestMiddleware());

// User token authentication
$eventsManager->attach('micro', new AuthMiddleware());
$app->before(new AuthMiddleware());

$app->setEventsManager($eventsManager);
