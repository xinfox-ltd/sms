<?php

/**
 * [XinFox System] Copyright (c) 2011 - 2021 XINFOX.CN
 */
declare(strict_types=1);

namespace XinFox\Sms;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use XinFox\Sms\Contracts\GatewayInterface;

abstract class Gateway implements GatewayInterface
{
    protected array $config = [];

    public function __construct(array $config = [])
    {
       $this->config = $config;
    }

    public function get($uri, $query = [], $headers = [])
    {
        return $this->request('GET', $uri, [
            'headers' => $headers,
            'query' => $query,
        ]);
    }

    public function request($method, $uri, $options = [])
    {
        return $this->parseResponse($this->getHttpClient()->request($method, $uri, $options));
    }

    /**
     * @param ResponseInterface $response
     * @return mixed|string
     */
    public function parseResponse(ResponseInterface $response)
    {
        $contentType = $response->getHeaderLine('Content-Type');
        $contents = $response->getBody()->getContents();

        if (false !== stripos($contentType, 'json') || stripos($contentType, 'javascript')) {
            return json_decode($contents, true);
        } elseif (false !== stripos($contentType, 'xml')) {
            return json_decode(json_encode(simplexml_load_string($contents)), true);
        }

        return $contents;
    }

    public function getName(): string
    {
        return strtolower(str_replace([__NAMESPACE__.'\\', 'Gateway'], '', \get_class($this)));
    }

    /**
     *
     * @param array $options
     *
     * @return Client
     *
     * @codeCoverageIgnore
     */
    protected function getHttpClient(array $options = []): Client
    {
        return new Client($options);
    }
}