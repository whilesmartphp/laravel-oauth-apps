<?php

namespace Whilesmart\LaravelOauthApps\Exceptions;

use Exception;

class NoClientIdException extends Exception
{
    protected $message = 'No client id provided';

    protected $code = 400;
}
