<?php
namespace ApiAuth\Model\Acl;

use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;
use Phalcon\Acl\Adapter\Memory as AclList;

class AccessControl {
    public $accessControlList;
    protected $userRoles = [];

    protected function isValidRoleName($roleName)
    {
        if (!is_string($roleName)) {
            throw new \Exception("Role name '" . $roleName . "' must be a string.");
        }
    }

    protected function isValidResource($roleResource)
    {
        if (!is_string($roleResource)) {
            throw new \Exception("Resource '" . $roleResource . "' must be a string.");
        }
    }

    protected function isValidActions($allowedActions)
    {
        if (!is_array($allowedActions)) {
            throw new \Exception("The ACL allowed actions must be in an array.");
        }
    }

    protected function setPermissions() 
    {
        foreach ($this->userRoles as $roleName => $roleResources) {
            $this->isValidRoleName($roleName);
            $newRole = new Role($roleName);
            $this->accessControlList->addRole($newRole);
            foreach ($roleResources as $controllerName => $controllerActions) {
                $this->isValidResource($controllerName);
                $this->isValidActions($controllerActions);
                $newResource = new Resource($controllerName);
                $this->accessControlList->addResource($newResource, $controllerActions);
                foreach ($controllerActions as $controllerAction) {
                    $this->accessControlList->allow($roleName, $controllerName, $controllerAction);
                }
            }
        }
    }

    protected function setUserRoles($userRoles)
    {
        $this->userRoles = $userRoles;
    }

    public function __construct($userRoles)
    {
        $this->accessControlList = new AclList;
        $this->accessControlList->setDefaultAction(Acl::DENY);
        $this->setUserRoles($userRoles);
        $this->setPermissions();
        return $this;
    }

}