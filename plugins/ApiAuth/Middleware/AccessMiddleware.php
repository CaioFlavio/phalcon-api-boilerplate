<?php
namespace ApiAuth\Middleware;

use Phalcon\Mvc\Micro;
use Phalcon\Events\Event;
use Phalcon\Config\Adapter\Ini;
use Phalcon\Mvc\Micro\MiddlewareInterface;
use ApiAuth\Model\Http\Response;
use ApiAuth\Model\Acl\AccessControl;

class AccessMiddleware implements MiddlewareInterface
{
    public $acl;
    protected $response;
    protected $rolesAccessList;

    public function __construct()
    {
        $this->response = new Response;
    }

    protected function getDataByRequestType($application)
    {
        $request = $application->request;
        $router  = $application->router;

        if ($request->isPost() || $request->isPut()) {
            return $request->getJsonRawBody(true);
        }

        if ($request->isGet()) {
            if ($queryParams = (object) $router->getParams()) {
                if ($queryParams->secret_key || $queryParams->token) {
                    return $queryParams;
                }
                return true;
            }
        }
        return false;
    }
    
    protected function loadRoles()
    {
        $appConfig    = new Ini(BASE_PATH . DS . 'app' . DS . 'config.ini');
        $plugin       = reset(explode("\\", __NAMESPACE__));
        $pluginConfig = new Ini(BASE_PATH . $appConfig->application->folder->services . DS . $plugin . DS . $appConfig->application->configFile);
        $accessRoles = [];
        foreach ($pluginConfig->acl as $roleName => $controllers){
            foreach ($controllers as $controllerName => $actions) {
                $accessRoles[$roleName][$controllerName] = explode(',', $actions);
            }
        } 
        $this->acl = new AccessControl($accessRoles);
    }

    public function getUserRole($requestData)
    {
        if(property_exists($requestData, 'secret_key')) {
            return;
        } else {
            return 'Guest';
        }
    }

    public function beforeHandleRoute(Event $event, Micro $application)
    {
        $request   = $application->request;
        $routeInfo = null;
        $isAllowed = true;
        $route     = $application->router->getMatchedRoute();

        if (!$route) {
            $this->response->setNotFound();
            return false;
        }

        if ($routeName = $route->getName()) {
            $this->loadRoles();
            $routeInfo = explode('.', $routeName); 
            $requestData = $this->getDataByRequestType($application);
            $userRole    = $this->getUserRole($requestData, $routeInfo);
            $isAllowed   = $this->acl->access->isAllowed($userRole, $routeInfo[0], $routeInfo[1]);
        }
        
        if (!$isAllowed) {
           $this->response->setForbidden();
           return false;
        }

        return $isAllowed;
    }

    public function call(Micro $application)
    {
        return false;
    }
}