<?php
namespace ApiAuth\Controller;

use Phalcon\Http\Request;
use Phalcon\Mvc\Controller;
use ApiAuth\Model\Table\Users as User;
use ApiAuth\Model\Acl\AccessControl;

class UsersController extends Controller
{

    public function new()
    {
        $request = $this->request;
        $requestData = (object) $this->request->getJsonRawBody(true);

        $user = new User();
        $user->generateUser($requestData->username, $requestData->webhook_url);
        echo json_encode($user);
    }

    public function activate()
    {
        $request = $this->request;
        $requestData = (object) $this->request->getJsonRawBody(true);
        $user = new User();
        $response = $user->activateUser($requestData->secret_key);

        echo json_encode($response);
        exit();
    }

    public function test($secret, $token)
    {
        var_dump($this);
        echo 'batata';
        // echo $this->request->getParam('param');
        // var_dump($this->getActiveHandler());
    }
}