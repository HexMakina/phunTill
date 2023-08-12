<?php

/*
 * phunTill Orders
 *
 */

namespace HexMakina\phunTill;

class Order {

    public $clientName;
    public $tableNumber;
    public $tablePart;
    public $items = [];
    public $takeAwayNotes;

    public function __construct(Table $table, $items = []) {
        $this->tableNumber = $table->getNumber();
        $this->tablePart = $table->getPart();
        $this->items = is_array($items) ? $items : [];
    }

    public function tableNumber()
    {
      return $this->tableNumber;
    }

    public function tablePart()
    {
      return $this->tablePart;
    }

    private function addItem($item): int {

        $this->items[] = $item;
        $last_index = array_key_last($this->items);
        $item->number($last_index+1);

        return $item->number();
    }
    
    public function setClient(string $name){
        $this->clientName = $name;
    }

    public function addNormalArticle($articleId, $quantity): int
    {
        return $this->addItem(new Item($articleId, 0, $quantity));
    }

    public function addMessage($text): int{
      $articleId = 0;
      $orderItemType = 6;

      $message = new Item($articleId, $orderItemType);
      $message->setText($text);

      return $this->addItem($message);
    }

    public function setNote(string $text){
      $this->takeAwayNotes = $text;
    }

    public function items():array
    {
      return $this->items;
    }

    public function json(): string
    {
      return json_encode($this->asArray());
    }

    public function asArray(): array
    {
      $res = (array)$this;

      foreach($res['items'] as $number => $item)
        $res['items'][$number] = (array)$item;

      return $res;
    }
}

  /*

  $data = array(
    'request' => 'createorder',
    'table' => 'table_number',
    'waiter' => 'waiter_name',
    'items' => array(
        array(
            'code' => 'item_code',
            'qty' => 'item_quantity',
            'price' => 'item_price',
            'modifiers' => array(
                array(
                    'code' => 'modifier_code',
                    'qty' => 'modifier_quantity',
                    'price' => 'modifier_price',
                ),
            ),
        ),
    ),
);
*/
