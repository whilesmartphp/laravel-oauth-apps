<?php

namespace Whilesmart\LaravelAppAuthentication\Exceptions;

use Exception;

class InvalidAppCredentialsException extends Exception
{
    protected $message = 'Invalid x-client-id and or x-secret-id provided.';

    protected $code = 400;
}
