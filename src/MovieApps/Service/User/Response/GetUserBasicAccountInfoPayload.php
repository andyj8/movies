<?php

namespace MovieApps\Service\User\Response;

use MovieApps\Response\Payload;

class GetUserBasicAccountInfoPayload extends Payload
{
    /**
     * @var string
     */
    public $firstName;

    /**
     * @var string
     */
    public $lastName;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $dOB;

    /**
     * @var string
     */
    public $termsAcceptance;

    /**
     * @var string
     */
    public $newsletterAcceptance;
}
