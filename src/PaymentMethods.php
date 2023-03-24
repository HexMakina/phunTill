<?php

/*
 * phunTill Orders
 *
 */

namespace HexMakina\phunTill;

class PaymentMethods {

    private int $cash_id;
    private int $card_id;

    public function __construct(POSAPI $api)
    {
      $payment_methods = $api->request('payment-info')->get();

      foreach($payment_methods->array() as $method)
      {
        if(!isset($this->cash_id) && $method->name == 'Cash')
          $this->cash_id = (int)$method->id;

        if(!isset($this->card_id) && $method->name == 'Card')
          $this->card_id = (int)$method->id;
      }

      if(is_null($this->cash_id))
          throw new phunTillException('Payment method "cash" is not available');

      if(is_null($this->card_id))
          throw new phunTillException('Payment method "card" is not available');

    }

    public function cash() : int
    {
        return $this->cash_id;
    }

    public function card() : int
    {
        return $this->card_id;
    }


}
