<?php
namespace ApiAuth\Model\Http;

use Phalcon\Http\Response as PhalconResponse;

class Response extends PhalconResponse
{
    protected function getStatusLabel($statusCode)
    {
        $statusList = [
            100 => 'Continue',
            101 => 'Switching Protocols',
            102 => 'Processing',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            207 => 'Multi-Status',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            306 => 'Switch Proxy',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            418 => 'I\'m a teapot',
            422 => 'Unprocessable Entity',
            423 => 'Locked',
            424 => 'Failed Dependency',
            425 => 'Unordered Collection',
            426 => 'Upgrade Required',
            449 => 'Retry With',
            450 => 'Blocked by Windows Parental Controls',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            506 => 'Variant Also Negotiates',
            507 => 'Insufficient Storage',
            509 => 'Bandwidth Limit Exceeded',
            510 => 'Not Extended'
        ];
        if ($statusLabel = $statusList[$statusCode]) {
            return $statusLabel;
        }
        return $statusList[400];
    }

    public function setApiResponse($statusCode, $customError = array())
    {
        $this->setStatusCode($statusCode, $this->getStatusLabel($statusCode));
        if ($customError) {
            $this->setJsonContent($customError);
        }
        $this->send();
    }

    public function setUnsuportedMediaTypeResponse($mediaType)
    {
        $this->setApiResponse(415, [
            'error_code'    => "001",
            'error_message' => "Request type '". $mediaType ."' not supported."
        ]);
    }

    public function setForbidden()
    {
        $this->setApiResponse(403,[
            'error_code'     => "002",
            'error_message'  => "You're not allowed to access this page"
        ]);
    }
}