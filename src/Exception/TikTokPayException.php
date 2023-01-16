<?php

namespace Chowjiawei\TikTokPay\Exception;

use Exception;
use Throwable;

class TikTokPayException extends Exception
{
    public function __construct($message = "", $code = 10000, Throwable $previous = null)
    {
        parent::__construct($message,$code,$previous);
    }
}
