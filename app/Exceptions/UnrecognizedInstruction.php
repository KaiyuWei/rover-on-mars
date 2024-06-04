<?php

namespace App\Exceptions;

use Exception;

class UnrecognizedInstruction extends Exception
{
    public const ERROR_CODE = 1;
}
