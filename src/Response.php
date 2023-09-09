<?php

/**
 * Represents the response from the phunTill API.
 */

namespace HexMakina\phunTill;

class Response
{
    public const SUCCESS_CODES = [200, 201];

    private string $content; // application/json
    private int $status;

    /**
     * Constructor for Response class.
     *
     * @param string $content The raw content of the API response.
     * @param int $status The HTTP status code of the API response.
     */
    public function __construct(string $content, int $status)
    {
        $this->content = $content;
        $this->status = $status;
    }

    /**
     * Get the raw content of the API response.
     *
     * @return string The response content.
     */
    public function content(): string
    {
        return $this->content;
    }

    /**
     * Convert the API response content to an array.
     *
     * @return array The response content as an array.
     */
    public function asArray(): array
    {
        return json_decode($this->content(), true);
    }

    /**
     * Convert the API response content to an associative array with 'id' as keys and objects as values.
     *
     * @param string $key The key to use as the associative array's keys.
     * @param string $value The key to use as the associative array's values.
     * @return array An associative array with 'id' as keys and objects as values.
     */
    public function asAssoc(string $key = 'id', string $value = 'name'): array
    {
        $ret = [];
        foreach ($this->asArray() as $obj) {
            $ret[$obj[$key]] = $obj;
        }
        return $ret;
    }

    /**
     * Get the HTTP status code of the API response.
     *
     * @return int The HTTP status code.
     */
    public function status(): int
    {
        return $this->status;
    }

    /**
     * Check if the API response represents a successful request.
     *
     * @return bool True if the response status is a success code, false otherwise.
     */
    public function isSuccess(): bool
    {
        return in_array($this->status, self::SUCCESS_CODES);
    }

    /**
     * Get the error message from the API response.
     *
     * @return string The error message.
     */
    public function error(): string
    {
        $ret = $this->asArray();
        return $ret['message'] ?? 'no message key in the phunTill Response, programming expectation error';
    }
}
