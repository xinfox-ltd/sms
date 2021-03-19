<?php

/**
 * [XinFox System] Copyright (c) 2011 - 2021 XINFOX.CN
 */
declare(strict_types=1);

namespace XinFox\Sms\Http;

use Psr\Http\Message\RequestInterface;

abstract class Exception extends \RuntimeException
{
    protected RequestInterface $request;

    public function __construct($message, RequestInterface $request, $previous = null)
    {
        $this->request = $request;

        parent::__construct($message, 0, $previous);
    }

    /**
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}