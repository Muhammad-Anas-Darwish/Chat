<?php

namespace App\Exceptions;

use Exception;

class BlockedContactException extends Exception
{
    protected $message = 'You are blocked from this contact';
}
