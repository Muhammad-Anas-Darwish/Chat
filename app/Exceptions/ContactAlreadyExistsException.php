<?php

namespace App\Exceptions;

use Exception;

class ContactAlreadyExistsException extends Exception
{
    protected $message = 'This Contact is already exists';
}
