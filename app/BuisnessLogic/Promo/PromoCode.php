<?php


namespace App\BuisnessLogic\Promo;

use App\Contracts\Promo\PromoCodeContract;

class PromoCode implements PromoCodeContract
{

    public function getYearSubscribePromoCode() :string
    {
        // TODO: получать реальный промо код
        return "PROMO_CODE_FAKE";
    }

    public function getYearSubscribeDiscount(): Discount
    {
        // TODO: получать реальную скидку
        return new Discount(30,Discount::TYPE_PERCENT);
    }
}
