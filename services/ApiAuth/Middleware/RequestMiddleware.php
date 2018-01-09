<?php
namespace ApiAuth\Middleware;

use Phalcon\Events\Event;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\MiddlewareInterface;
use ApiAuth\Model\Http\Response;

class RequestMiddleware implements MiddlewareInterface
{

    protected function validateHeader($headerType)
    {
        switch ($headerType) {
            case 'application/json':
                return true;
            break;
            default:
                $response = new Response;
                $response->setUnsuportedMediaTypeResponse($headerType);
                exit();
            break;
        }
        return false;
    }
    
    public function validateRequest($request)
    {
        if (!$this->validateHeader($request->getHeader('CONTENT_TYPE'))) {
            return false;
        }
        return true;
    }

    public function beforeHandleRoute(Event $event, Micro $application)
    {
        $request = $application->request;
        $requestData = $request->getJsonRawBody(true);
        if ($this->validateRequest($request)) {
            return true;
        } else {
            return false;
        }
    }

    public function call(Micro $application)
    {
        return false;
    }
}