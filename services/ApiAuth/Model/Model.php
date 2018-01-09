<?php
namespace ApiAuth\Model;

use Phalcon\Config\Adapter\Ini;
use Phalcon\Mvc\Model as PhalconModel;

class Model extends PhalconModel
{

    public $virtual_field_list;

    public function getValdationErrors($errorMessages)
    {
        $error = '';
        foreach ($errorMessages as $message) {
            $error .= $message . ' ';
        }
        return $error;
    }

    public function getConfig($type)
    {
        $config = new Ini(APP_PATH . 'config/config.ini');
        if (!is_null($type)) {
            return $config->{$type};
        }
    }

    public function getErrorReturn($errorCode, $errorMessage, $errorInfo = '')
    {
        return json_encode(
            array(
                'error_code'    => $errorCode,
                'error_message' => $errorMessage,
                'error_info'    => $errorInfo
            )
        );
    }

    public function toArray($columns = null) {
        $data = parent::toArray($columns);
        $virtual_fields = [];
        if (!empty($this->virtual_field_list)) {
            foreach ($this->virtual_field_list as $name) {
                $getter_name = 'get' . \Phalcon\Text::camelize($name);
                $virtual_fields[$name] = $this->{$getter_name}();
            }
        }
        $data = array_merge($data, $virtual_fields);
        return $data;
    }
}