<?php

namespace App\Exceptions;

use Exception;

class NotEnoughCoinsException extends Exception
{
    protected $message = 'Недостаточно средств для выполнения данного действия!';
}
