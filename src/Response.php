<?php

/**
 * Represents the response from the phunTill API.
 * Stores the HTTP status and the JSON response as a string
 * 
 */

namespace HexMakina\phunTill;

class Response
{
    public const SUCCESS_CODES = [200 => 'OK', 201 => 'CREATED'];

    private string $content; // application/json
    private int $status;
    private bool $success;

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

        $this->success = isset(self::SUCCESS_CODES[$status]);
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
        return $this->success === true;
    }

    /**
     * Convert the API response content to an array.
     *
     * @return array The response content as an array.
     */
    public function message(): ?string
    {
        $message = null;

        if(strpos($this->content(), '{"message":"') === 0){
            $res = json_decode($this->content(), true);
            if(isset($res['message']))
                $message = $res['message'];

        }

        return $message;
    }


    public function asArray(): array
    {
        if ($message = $this->message())
            throw new phunTillException('API Error: ' . $message);
            
        $content = $this->content();
        $content = str_replace('\\', '\\\\', $content);
        $content = json_decode($content, true);
        
        if(is_null($content)){
            vd($this->content());
            dd(json_last_error_msg(), json_last_error());
        }

        return $content;
    }

    /**
     * Convert the API response content to an associative array with 'id' as keys and objects as values.
     *
     * @param string $key The key to use as the associative array's keys.
     * @param string $value The key to use as the associative array's values.
     * @return array An associative array with 'id' as keys and objects as values.
     */
    public function asAssoc(string $key = 'id', string $value = null): array
    {
        $ret = [];
        foreach ($this->asArray() as $obj) {
            if(empty($value))
            $ret[$obj[$key]] = empty($value) ? $obj : $obj->$value;
        }
        return $ret;
    }

    public function asIdLabel($key='id', $value='name'): array
    {
        $ret = [];

        foreach ($this->asArray() as $rec) {
            vd($rec);
            $ret[$rec[$key]] = $rec[$value];
        }

        return $ret;
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
