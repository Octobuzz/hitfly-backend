<?php

namespace App\Contracts\Promo;

use App\BuisnessLogic\Promo\Discount;

interface PromoCodeContract
{
    /*
     * получить промокод на годовую подписку
     */
    public function getYearSubscribePromoCode(): string;

    public function getYearSubscribeDiscount(): Discount;
}
