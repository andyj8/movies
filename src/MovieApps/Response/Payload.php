<?php

namespace MovieApps\Response;

class Payload
{
    /**
     * @var string
     */
    public $responseCode;

    /**
     * @var string
     */
    public $responseMessage;

    /**
     * @param string $responseCode
     * @param string $responseMessage
     */
    public function __construct($responseCode = '', $responseMessage = '')
    {
        $this->responseCode = $responseCode;
        $this->responseMessage = $responseMessage;
    }

    /**
     * @return Payload
     */
    public static function success()
    {
        $response = new self();
        $response->responseCode = '0';
        $response->responseMessage = 'Success';
        
        return $response;
    }

    /**
     * @return Payload
     */
    public static function noAuth()
    {
        $response = new self();
        $response->responseCode = '31';
        $response->responseMessage = 'AuthToken Not Available';
        
        return $response;
    }

    /**
     * @return Payload
     */
    public static function titleNotFound()
    {
        $response = new self();
        $response->responseCode = '1';
        $response->responseMessage = 'Title ID not found.';

        return $response;
    }

    /**
     * @return Payload
     */
    public static function userNotFound()
    {
        $response = new self();
        $response->responseCode = '601';
        $response->responseMessage = 'The user id provided was not found.';

        return $response;
    }

    /**
     * @param string $message
     * @return Payload
     */
    public static function unhandledException($message = 'Unhandled exception')
    {
        $response = new self();
        $response->responseCode = '550';
        $response->responseMessage = $message;
        
        return $response;
    }
}
