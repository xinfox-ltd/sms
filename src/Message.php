<?php

/**
 * [XinFox System] Copyright (c) 2011 - 2021 XINFOX.CN
 */
declare(strict_types=1);

namespace XinFox\Sms;

use XinFox\Sms\Contracts\GatewayInterface;
use XinFox\Sms\Contracts\MessageInterface;

/**
 * Class Message
 * @property
 * @package XinFox\Sms
 */
class Message implements MessageInterface
{
    protected $data = [];

    protected $content = '';

    protected $template;

    public function __construct(array $config)
    {
        $this->data = $config['data'] ?? [];
        $this->content = $config['content'] ?? '';
        $this->template = $config['template'] ?? '';
    }

    public function getData(GatewayInterface $gateway): array
    {
        return $this->getProperty($this->data, $gateway);
    }

    public function getContent(GatewayInterface $gateway): string
    {
        return $this->getProperty($this->content, $gateway);
    }

    public function getTemplate(GatewayInterface $gateway): string
    {
        return $this->getProperty($this->template, $gateway);
    }

    /**
     * @param $property
     * @param GatewayInterface $gateway
     * @return false|mixed
     */
    protected function getProperty($property, GatewayInterface $gateway)
    {
        return $property instanceof \Closure ? call_user_func($property, $gateway) : $property;
    }
}