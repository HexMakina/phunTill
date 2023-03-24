<?php

/*
 * phunTill you have to do it yourself
 *
 */

namespace HexMakina\phunTill;

class Response
{
    const SUCCESS_CODES = [200 => true, 201 => true];

    private string $content;
    private int $status;

    public function __construct(string $content, $status)
    {
      $this->content = $content;
      $this->status = (int)$status;
    }

    public function content(): string
    {
      return $this->content;
    }

    public function array()
    {
        return (array)json_decode($this->content());
    }

    public function status() : int
    {
        return $this->status;
    }

    public function success() : bool
    {
      return isset(self::SUCCESS_CODES[$this->status()]) && self::SUCCESS_CODES[$this->status()] === true;
    }
}
