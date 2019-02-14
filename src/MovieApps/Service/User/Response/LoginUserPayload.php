<?php

namespace MovieApps\Service\User\Response;

use MovieApps\Response\Payload;

class LoginUserPayload extends Payload
{
    /**
     * @var string
     */
    public $authToken;

    /**
     * @var string
     */
    public $emailAddress;

    /**
     * @var string
     */
    public $deviceFriendlyName;

    /**
     * @var string
     */
    public $adultPinEnabled = 'false';

    /**
     * @var string
     */
    public $parentPinEnabled = 'false';

    /**
     * @var string
     */
    public $purchasePinEnabled = 'false';

    /**
     * @var string
     */
    public $parentalControlsConfigured = 'false';
}
