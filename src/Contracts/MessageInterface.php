<?php

/**
 * [XinFox System] Copyright (c) 2011 - 2021 XINFOX.CN
 */
declare(strict_types=1);

namespace XinFox\Sms\Contracts;

interface MessageInterface
{
    public function getData(GatewayInterface $gateway): array;

    public function getContent(GatewayInterface $gateway): string;

    public function getTemplate(GatewayInterface $gateway);
}