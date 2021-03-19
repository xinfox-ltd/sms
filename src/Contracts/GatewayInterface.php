<?php

/**
 * [XinFox System] Copyright (c) 2011 - 2021 XINFOX.CN
 */
declare(strict_types=1);

namespace XinFox\Sms\Contracts;

interface GatewayInterface
{
    /**
     * 获取网关名称
     *
     * @return string
     */
    public function getName(): string;

    /**
     * 发送短信
     *
     * @param PhoneNumberInterface $phoneNumber
     * @param MessageInterface $message
     * @return mixed
     */
    public function send(PhoneNumberInterface $phoneNumber, MessageInterface $message);
}