<?php

/*
 * phunTill you have to do it yourself
 *
 */

namespace HexMakina\phunTill;

class Response
{

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

    public function array() : array
    {
        return (array)json_decode($this->content());
    }

    public function key_value($key='id', $value='name'): array
    {
        $ret = [];

        $res = $this->array();
        foreach ($res as $rec) {
            $ret[$rec->$key] = $rec->$value;
        }

        return $ret;
    }

    public function status(): int
    {
        return $this->status;
    }
}
