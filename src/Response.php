<?php

/*
 * phunTill you have to do it yourself
 *
 */

namespace HexMakina\phunTill;

class Response
{

    public const SUCCESS_CODES = [200, 201];

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

    public function asArray() : array
    {
        return json_decode($this->content(), true);
    }

    public function asAssoc(): array
    {
        $ret = [];
        foreach($this->asArray() as $obj){
            $ret[$obj->id] = $obj;
        }
        return $ret;
    }

    public function asIdLabel($key='id', $value='name'): array
    {
        $ret = [];

        $res = $this->asArray();
        foreach ($res as $rec) {
            $ret[$rec->$key] = $rec->$value;
        }

        return $ret;
    }

    public function status(): int
    {
        return $this->status;
    }

    public function isSuccess(): bool
    {
        return in_array($this->status, self::SUCCESS_CODES);
    }
    
    public function error(): string
    {
        $ret = $this->asArray();
        return $ret['message']; 
    }




}
