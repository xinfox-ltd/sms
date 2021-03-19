<?php

/**
 * [XinFox System] Copyright (c) 2011 - 2021 XINFOX.CN
 */
declare(strict_types=1);

namespace XinFox\Sms\Exceptions;

class GatewayErrorException extends \Exception
{
    /**
     * @var array
     */
    public array $raw = [];

    /**
     * GatewayErrorException constructor.
     *
     * @param string $message
     * @param int    $code
     * @param array  $raw
     */
    public function __construct(string $message, $code, array $raw = [])
    {
        parent::__construct($message, intval($code));

        $this->raw = $raw;
    }
}