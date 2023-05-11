<?php

/*
 * phunTill Orders
 *
 */

namespace HexMakina\phunTill;

class PaymentMethods
{
    public const CASH = 'Cash';
    public const CARD = 'Bancontact';
    
    private int $cash_id;
    private int $card_id;

    public function __construct(POSAPI $api)
    {
        $payment_methods = $api->get('payment-info');
        foreach ($payment_methods->asArray() as $method) {
            if (!isset($this->cash_id) && $method->name == self::CASH)
                $this->cash_id = (int)$method->id;

            if (!isset($this->card_id) && $method->name == self::CARD)
                $this->card_id = (int)$method->id;
        }

        if (empty($this->cash_id))
            throw new phunTillException('Payment method "cash" is not available');

        if (empty($this->card_id))
            throw new phunTillException('Payment method "card" is not available');
    }

    public function cash(): int
    {
        return $this->cash_id;
    }

    public function card(): int
    {
        return $this->card_id;
    }
}
