<?php

/*
 * phunTill you have to do it yourself
 *
 */

namespace HexMakina\phunTill;

class Request
{

    private POSAPI $api;

    private string $endpoint;
    private ?string $version;

    private string $method;

    private array $parameters = [];
    private array $options = [];

    public function __construct(POSAPI $api, string $endpoint, string $method='GET', $version = null)
    {
        $this->api = $api;
        $this->endpoint = $endpoint;
        $this->method = $method;
        $this->version = $version;

        $this->withOptions([
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD => $this->api->authString(),
            CURLOPT_HTTPHEADER => $this->api->headers()
        ]);
    }

    public function withParameter($key, $value)
    {
        $this->parameters[$key] = $value;

        return $this;
    }

    public function withParameters(array $dat_ass)
    {
        foreach ($dat_ass as $key => $value)
            $this->withParameter($key, $value);

        return $this;
    }

    public function withOption($key, $value)
    {
        $this->options[$key] = $value;

        return $this;
    }

    public function withOptions(array $dat_ass)
    {
        foreach ($dat_ass as $key => $value)
            $this->withOption($key, $value);

        return $this;
    }

    public function URL($endpoint = null, $version = null): string
    {
        $endpoint = $endpoint ?? $this->endpoint;
        if (is_array($this->parameters) && !empty($this->parameters)) {
            $endpoint .= '?' . http_build_query($this->parameters);
        }

        $version = $version ?? $this->version ?? $this->api->version();

        return sprintf('%s/api/%s/%s/%s', $this->api->baseUrl(), $version, $this->api->database(), $endpoint);
    }

    public function method(): string
    {
        return $this->method;
    }

    public function options(): array
    {
        return $this->options;
    }
}
