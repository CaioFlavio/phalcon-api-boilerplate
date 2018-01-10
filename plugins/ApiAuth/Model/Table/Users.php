<?php
namespace ApiAuth\Model\Table;

use Phalcon\Filter;
use Phalcon\Security\Random;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;
use ApiAuth\Model\Model;
use ApiAuth\Model\Table\Tokens as Token;

class Users extends Model
{
    public $username;
    protected $id;
    protected $token_id;
    protected $secret_key;
    protected $webhook_url;
    protected $created_at;
    protected $updated_at;
    protected $active;

    public function initialize()
    {
        $this->setSource('users');

        $this->hasMany(
            'id',
            '\ApiAuth\Model\Table\Tokens',
            'user_id',
            [
                'alias'      => 'Token',
                'params' => [
                    'conditions' => 'expires_at >= :today: AND active = :status:',
                    'bind'       => [
                        'today'  => date('Y-m-d H:i:s'),
                        'status' => 1,
                    ],
                    'order'      => 'id DESC'
                ]
            ]
        );
    }

    public function validation()
    {
        $validation = new Validation();

        $validation->add(
            "username",
            new Uniqueness(
                [
                    "message" => "The username already exists.",
                ]
            )
        );
        
        $validation->add(
            "secret_key",
            new Uniqueness(
                [
                    "message" => "Error on generating secret key.",
                ]
            )
        );

        return $this->validate($validation);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getSecretKey()
    {
        return $this->secret_key;
    }
    
    public function getWebhookUrl()
    {
        return $this->webhook_url;
    }

    public function setUsername($name)
    {
        $this->username = $name;
    }

    public function setTokenId($tokenid)
    {
        $this->token_id = $tokenid;
    }

    public function setActive($status)
    {
        $this->active = $status;
    }


    public function generateUser($username, $webhook)
    {
        $filter = new Filter();
        $random = new Random();
        $date   = new \DateTime();

        $this->username   = $filter->sanitize($username, "string");
        $this->secret_key = $random->base64Safe(16);
        $this->webhook_url= $filter->sanitize($webhook, "string");
        $this->created_at = $date->format('Y-m-d H:i:s');
        $this->updated_at = $date->format('Y-m-d H:i:s');
        $this->active    = 0;
        if($this->save() === false){
            echo json_encode(
                array(
                    'error_code'    => '001',
                    'error_message' => 'Error when generating user.',
                    'error_info'    => $this->getValdationErrors($this->getMessages())
                )
            );
            exit();
        }
        return $this;
    }

    public function activateUser($secretKey)
    {
        $user = $this->findFirst(
            [
                "conditions" => "secret_key = '$secretKey'",
            ]
        );

        $token = new Token();
        $token->generateToken($user->getId());

        return json_encode($token);
    }

    public static function findBySecret($secretKey)
    {
        return Users::findFirst(
            [
                "secret_key = :secret:",
                "bind" => [
                    'secret' => $secretKey,
                ]
            ]
        );
    }
}