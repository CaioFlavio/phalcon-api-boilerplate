<?php
namespace ApiAuth\Controller;

use Phalcon\Http\Request;
use Phalcon\Mvc\Controller;
use ApiAuth\Model\Table\Users as User;
use ApiAuth\Model\Acl\AccessControl;

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

    public function testAction($secret, $token)
    {
        var_dump($this->router->getParams('secret_key'));
        echo $secret;
        echo '<br>batata<br>';
        echo $token;
        // echo $this->request->getParam('param');
        // var_dump($this->getActiveHandler());
    }
}