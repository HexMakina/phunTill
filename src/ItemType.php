<?php

/*
 * phunTill item types
 * 
    Order item type (integer <int32>)
    0 - Normal article (either regular or menu)
    1 - Must have option
    2 - Free option
    3 - Supplement
    4 - Condiment
    5 - Menu item
    6 - Article message
 *
 */

namespace HexMakina\phunTill;

class ItemType{

    public const NORMAL = 0;

    public const OPTION_MANDATORY = 1;
    public const OPTION_FREE = 2;
    public const OPTION_PAY = 3;

    public const CONDIMENT = 4;
    public const MENU = 5;
    public const MESSAGE = 6;

    public static function validate($code): bool
    {
        return $code === self::NORMAL 
            || $code === self::OPTION_MANDATORY
            || $code === self::OPTION_FREE
            || $code === self::OPTION_PAY
            || $code === self::CONDIMENT
            || $code === self::MENU
            || $code === self::MESSAGE;
    }
}
