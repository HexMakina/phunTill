<?php

/*
 * phunTill Collections
 *
 */

namespace HexMakina\phunTill;

class Collection
{
    private static POSAPI $api;

    public static function api(?POSAPI $api = null)
    {
        if (!is_null($api)) {
            self::$api = $api;
        }
        if (is_null(self::$api)) {
            throw new \InvalidArgumentException('NO POS API');
        }

        return self::$api;
    }

    public static function __callStatic($name, $arguments)
    {
        $params = [];

        switch ($name) {

                //* List available articles. Required user permission: Backoffice view
            case 'articles':
                $endpoint = 'article';
                $params = $arguments[0] ?? false ? ['active' => 'true'] : [];
            break;

                //* List available courses. Required user permission: Backoffice view
            case 'courses':
                $endpoint = 'course';
                $params = $arguments[0] ?? false ? ['active' => 'true'] : [];
            break;

                //* List available departments. Required user permission: Backoffice view
            case 'departments':
                $endpoint = 'department-info';
                $params = isset($arguments[0]) ? ['salesAreaId' => $arguments[0]] : [] ;
            break;

                //* List available categories. Required user permission: Backoffice view
            case 'categories':
                $endpoint = 'category';
            break;

            case 'prices':
                $endpoint = 'price-info';
            break;

            case 'options':
                $endpoint = 'option-info';
            break;

            case 'areas':
                $endpoint = 'sales-area-info';
            break;

            case 'printers':
                $endpoint = 'printer';
            break;        
        }

        return self::api()->get($endpoint, $params);
    }
}
