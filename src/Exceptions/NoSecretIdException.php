<?php

namespace Whilesmart\LaravelAppAuthentication\Exceptions;

use Exception;

class NoSecretIdException extends Exception
{
    protected $message = 'No secret Id provided';

    protected $code = 400;
}
