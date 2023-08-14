<?php

/*
 * phunTill Orders
 *
 */

namespace HexMakina\phunTill;

class Order
{
    public $clientName;
    public $tableNumber;
    public $tablePart;
    public $items = [];
    public $takeAwayNotes;
    public $takeAwayTimestamp;

    public function __construct(Table $table, $items = [])
    {
        $this->tableNumber = $table->getNumber();
        $this->tablePart = $table->getPart();
        $this->items = is_array($items) ? $items : [];
    }

    public function setClient(string $name)
    {
        $this->clientName = $name;
    }

    public function tableNumber()
    {
        return $this->tableNumber;
    }

    public function tablePart()
    {
        return $this->tablePart;
    }

    public function items(): array
    {
        return $this->items;
    }


    public function setNote(string $text)
    {
        $this->takeAwayNotes = $text;
    }

    public function setDate(\DateTimeImmutable $date)
    {
        $this->takeAwayTimestamp = $date->format('Y-m-d\TH:i:s');
    }

    public function addItem($item)
    {
        $this->items[] = $item;
        $last_index = array_key_last($this->items);
        return $item->setNumber($last_index + 1);
    }

    public function addMessage($text)
    {
        $message = new Item(0, ItemType::MESSAGE);
        $message->setText($text);

        $this->addItem($message);
    }

    public function json(): string
    {
        return json_encode($this->asArray());
    }

    public function asArray(): array
    {
        $res = (array)$this;

        foreach ($res['items'] as $number => $item)
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
