<?php

/**
 * [XinFox System] Copyright (c) 2011 - 2021 XINFOX.CN
 */
declare(strict_types=1);

namespace XinFox\Sms\Gateways;

use XinFox\Sms\Contracts\MessageInterface;
use XinFox\Sms\Contracts\PhoneNumberInterface;
use XinFox\Sms\Exceptions\GatewayErrorException;
use XinFox\Sms\Gateway;

use function json_encode;

class AliyunGateway extends Gateway
{
    protected string $endpointUrl = 'http://dysmsapi.aliyuncs.com';

    protected string $regionId = 'cn-hangzhou';

    protected string $format = 'JSON';

    protected string $signatureMethod = 'HMAC-SHA1';

    protected string $action = 'SendSms';

    protected string $version = '2017-05-25';

    protected string $signatureVersion = '1.0';

    /**
     * @param PhoneNumberInterface $phoneNumber
     * @param MessageInterface $message
     * @return array
     * @throws GatewayErrorException
     */
    public function send(PhoneNumberInterface $phoneNumber, MessageInterface $message): array
    {
        $data = $message->getData($this);

        $signName = $data['sign_name'] ?? $this->config['sign_name'];

        unset($data['sign_name']);

        $params = [
            'RegionId' => $this->regionId,
            'AccessKeyId' => $this->config['access_key_id'],
            'Format' => $this->format,
            'SignatureMethod' => $this->signatureMethod,
            'SignatureVersion' => $this->signatureVersion,
            'SignatureNonce' => uniqid(),
            'Timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
            'Action' => $this->action,
            'Version' => $this->version,
            'PhoneNumbers' => $phoneNumber->getNumber(),
            'SignName' => $signName,
            'TemplateCode' => $message->getTemplate($this),
            'TemplateParam' => json_encode($data, JSON_FORCE_OBJECT),
        ];

        $params['Signature'] = $this->generateSign($params);
return $params;
        $result = $this->get($this->endpointUrl, $params);
        if ('OK' != $result['Code']) {
            throw new GatewayErrorException($result['Message'], $result['Code'], $result);
        }

        return $result;
    }

    private function generateSign(array $params): string
    {
        ksort($params);
        $accessKeySecret = $this->config['access_key_secret'];
        $stringToSign = 'GET&%2F&' . urlencode(http_build_query($params, '', '&', PHP_QUERY_RFC3986));

        return base64_encode(hash_hmac('sha1', $stringToSign, $accessKeySecret . '&', true));
    }
}