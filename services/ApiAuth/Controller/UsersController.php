<?php
namespace ApiAuth\Controller;

use Phalcon\Http\Request;
use Phalcon\Mvc\Controller;
use ApiAuth\Model\Table\Users as User;

class UsersController extends Controller
{

    public function newAction()
    {
        $request = $this->request;
        $requestData = (object) $this->request->getJsonRawBody(true);

        $user = new User();
        $user->generateUser($requestData->username, $requestData->webhook_url);
        echo json_encode($user);
    }

    public function activateAction()
    {
        $request = $this->request;
        $requestData = (object) $this->request->getJsonRawBody(true);
        $user = new User();
        $response = $user->activateUser($requestData->secret_key);

        echo json_encode($response);
        exit();
    }

    public function batata()
    {
        echo 'batata';
    }
}