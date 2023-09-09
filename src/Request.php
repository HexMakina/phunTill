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

        $this->setInitialOptions();
    }

    /**
     * Initialize default cURL options.
     */
    private function setInitialOptions(): void
    {
        $this->withOptions([
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD => $this->api->authString(),
            CURLOPT_HTTPHEADER => $this->api->headers()
        ]);
    }

    /**
     * Add a single parameter.
     *
     * @param mixed $key
     * @param mixed $value
     * @return self
     */
    public function withParameter($key, $value): self
    {
        $this->parameters[$key] = $value;
        return $this;
    }

    /**
     * Add multiple parameters.
     *
     * @param array $parameters
     * @return self
     */
    public function withParameters(array $parameters): self
    {
        foreach ($parameters as $key => $value) {
            $this->withParameter($key, $value);
        }
        return $this;
    }

    /**
     * Add a single cURL option.
     *
     * @param mixed $key
     * @param mixed $value
     * @return self
     */
    public function withOption($key, $value): self
    {
        $this->options[$key] = $value;
        return $this;
    }

    /**
     * Add multiple cURL options.
     *
     * @param array $options
     * @return self
     */
    public function withOptions(array $options): self
    {
        foreach ($options as $key => $value) {
            $this->withOption($key, $value);
        }
        return $this;
    }

    /**
     * Generate the URL for the API request.
     *
     * @param mixed $endpoint
     * @param mixed $version
     * @return string
     */
    public function URL($endpoint = null, $version = null): string
    {
        $endpoint = $endpoint ?? $this->endpoint;
        if (is_array($this->parameters) && !empty($this->parameters)) {
            $endpoint .= '?' . http_build_query($this->parameters);
        }

        $version = $version ?? $this->version ?? $this->api->version();

        return sprintf('%s/api/%s/%s/%s', $this->api->baseUrl(), $version, $this->api->database(), $endpoint);
    }

    /**
     * Get the HTTP method.
     *
     * @return string
     */
    public function method(): string
    {
        return $this->method;
    }

    /**
     * Get the cURL options.
     *
     * @return array
     */
    public function options(): array
    {
        return $this->options;
    }
}
