<?php
namespace ApiAuth\Model\Table;

use Phalcon\Validation;
use Phalcon\Mvc\Model;
use Phalcon\Security\Random;
use Phalcon\Validation\Validator\Uniqueness;
use ApiAuth\Model\Model as ApiModel;
use ApiAuth\Model\Table\Users as User;


class Tokens extends ApiModel
{
    public $token;
    public $expires_at;
    protected $id;
    protected $active;

    public function initialize()
    {
        $this->setSource('tokens');

        $this->belongsTo(
            'id',
            '\ApiAuth\Model\Table\Users',
            'token_id'
        );
    }
    
    public function validation()
    {
        $validation = new Validation();
        
        $validation->add(
            "token",
            new Uniqueness(
                [
                    "message" => "Error on generating token.",
                ]
            )
        );
        return $this->validate($validation);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    protected function setUserToken($userid)
    {
        $user = new User();
        $user = $user->findFirst(
            [
                "conditions" => "id = '$userid'",
            ]
        );
        if (!$user){
            echo $this->getErrorReturn('003', 'User not found.');
            exit();
        }

        if ($this->save() === false) {
            echo $this->getErrorReturn('002', 'Error when generating token.', $this->getValdationErrors($this->getMessages()));
            exit();
        }

        $user->setTokenId($this->id);
        $user->setActive(1);
        $user->save();
    }

    public function generateToken($userid)
    {
        $date    = new \DateTime();
        $random  = new Random();
        $config  = $this->getConfig('security');
        $this->user_id    = $userid;
        $this->token      = $random->uuid();
        $this->created_at = $date->format("Y-m-d H:i:s");
        $this->updated_at = $date->format("Y-m-d H:i:s");
        $this->expires_at = $date->add(new \DateInterval('P'. $config->tokenDays .'D'))->format("Y-m-d H:i:s");
        $this->active = 1;
        $this->setUserToken($userid);
        return $this;
    }
}