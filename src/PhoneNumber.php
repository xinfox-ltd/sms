<?php

/**
 * [XinFox System] Copyright (c) 2011 - 2021 XINFOX.CN
 */
declare(strict_types=1);

namespace XinFox\Sms;

use XinFox\Sms\Contracts\PhoneNumberInterface;

class PhoneNumber implements PhoneNumberInterface
{
    private $number;

    public function __construct($number, $code = null)
    {
        $this->number = $number;
    }

    public function getNumber()
    {
        return $this->number;
    }
}