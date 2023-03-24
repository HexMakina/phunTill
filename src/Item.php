<?php

/*
 * phunTill Orders
 *
 */

namespace HexMakina\phunTill;

class Item {

    //	Sequential order item number
    public int $number = 0;

    // ID of article to order. Specify 0 when order item type is 6
    public $articleId;
    /*
      integer <int32>
      Order item type
      0 - Normal article (either regular or menu)
      1 - Must have option
      2 - Free option
      3 - Supplement
      4 - Condiment
      5 - Menu item
      6 - Article message
      */

    public $orderItemType;

    // Only required when the article has enabled manual price
    public $manualPrice = null;

    // Quantity of ordered items. Only valid when order item type is 0 or 5
    public $quantity = null;

    public $text = null;

    public function __construct($articleId, $orderItemType, $quantity=0) {
        $this->articleId = $articleId;
        $this->orderItemType = $orderItemType;
        $this->quantity = $quantity;
    }

    public function number($number = null)
    {
      if(!is_null($number))
        $this->number=$number;

      return $this->number;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    public function setText($text)
    {
      $this->text = $text;
    }

    public function setManualPrice($manualPrice)
    {
      $this->manualPrice = $manualPrice;

    }
}
