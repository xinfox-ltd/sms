<?php

/**
 * [XinFox System] Copyright (c) 2011 - 2021 XINFOX.CN
 */
declare(strict_types=1);

namespace XinFox\Sms;

use XinFox\Sms\Contracts\GatewayInterface;
use XinFox\Sms\Contracts\MessageInterface;
use XinFox\Sms\Contracts\PhoneNumberInterface;
use XinFox\Sms\Exceptions\InvalidArgumentException;

class XinFoxSms
{
    protected array $config;

    protected ?string $gateway = null;

    /**
     * XinFoxSms constructor.
     * @param array $config
     * @throws InvalidArgumentException
     */
    public function __construct(array $config)
    {
        if (!empty($config['default']['gateway'])) {
            $gateway = $config['default']['gateway'];
            if (empty($config['gateways'][$gateway])) {
                throw new InvalidArgumentException('The default gateway configuration must not be empty');
            }
            $this->gateway = $gateway;
        }

        $this->config = $config;
    }

    /**
     * @param $phoneNumber
     * @param $message
     * @param array $options
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function send($phoneNumber, $message, array $options = [])
    {
        if (!$phoneNumber instanceof PhoneNumberInterface) {
            $phoneNumber = new PhoneNumber($phoneNumber);
        }

        if (is_array($message)) {
            $message = new Message($message);
        } elseif ($message instanceof \Closure) {
            $message = call_user_func($message);
        }

        if (!$message instanceof MessageInterface) {
            throw new InvalidArgumentException('The closure function must return a message object');
        }

        return $this->getGateway()->send($phoneNumber, $message);
    }

    /**
     * @return GatewayInterface
     * @throws InvalidArgumentException
     */
    public function getGateway(): GatewayInterface
    {
        if (empty($this->gateway) || empty($this->config['gateways'][$this->gateway])) {
            throw new InvalidArgumentException(
                sprintf('Gateway %s configuration does not exist', $this->gateway)
            );
        }
        $name = ucfirst(str_replace(['-', '_', ''], '', $this->gateway));
        $className = __NAMESPACE__ . "\\Gateways\\{$name}Gateway";

        $gateway = new $className($this->config['gateways'][$this->gateway]);
        if (!($gateway instanceof GatewayInterface)) {
            throw new InvalidArgumentException(
                sprintf('Gateway "%s" must implement interface %s.', $name, GatewayInterface::class)
            );
        }

        return $gateway;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function gateway(string $name): XinFoxSms
    {
        $this->gateway = $name;

        return $this;
    }

}