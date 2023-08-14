<?php

/*
 * phunTill Orders
 *
 */

namespace HexMakina\phunTill;

class Item
{

    //	Sequential order item number
    public int $number = -1;

    // ID of article to order. Specify 0 when order item type is 6
    public int $articleId;

    // see ItemType class
    public int $orderItemType;

    // Quantity of ordered items. Only valid when order item type is 0 or 5
    public int $quantity;

    public string $text;


    public function __construct(int $articleId, int $orderItemType, int $quantity = 0)
    {
        if (!ItemType::validate($orderItemType))
            throw new phunTillException('INVALID_ITEM_TYPE_(' . $orderItemType . ')');

        $this->articleId = $articleId;
        $this->orderItemType = $orderItemType;
        $this->quantity = $quantity;
    }

    public function setNumber(int $number)
    {
        $this->number = $number;
    }

    public function setQuantity(int $quantity)
    {
        $this->quantity = $quantity;
    }

    public function setText(string $text)
    {
        $this->text = $text;
    }
}
