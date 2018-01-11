<?php
namespace ApiAuth\Model\Table;

use ApiAuth\Model\Model;

class UserRoles extends Model
{
    protected $id;
    protected $role;
    protected $grant_all;
    protected $created_at;
    public $active;
    public $updated_at;

    public function initialize()
    {
        $this->setSource('user_roles');

        $this->hasMany(
            'id',
            '\ApiAuth\Model\Table\Users',
            'role_id',
            [
                'alias' => 'Users',
            ]
        );
    }

    public function validation()
    {
        $validation = new Validation();

        $validation->add(
            "role",
            new Uniqueness(
                [
                    "message" => "The permission role already exists.",
                ]
            )
        );

        return $this->validate($validation);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function getName()
    {
        return $this->getRole();
    }

    public function getGrantAll()
    {
        return $this->grant_all;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function getActive()
    {
        return $this->active;
    }

    public function setRole($value)
    {
        $this->role = $value;
    }

    public function setActive($value)
    {
        $this->active = $value;
    }

    public function setUpdatedAt($value)
    {
        $this->updated_at = $value;
    }

    public function isAdmin()
    {
        return $this->getGrantAll();
    }
}