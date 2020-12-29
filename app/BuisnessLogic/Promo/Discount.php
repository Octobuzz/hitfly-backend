<?php

namespace App\BuisnessLogic\Promo;

final class Discount
{
    private $discountSize;
    private $discountType;

    const TYPE_PERCENT = 'PERCENT';
    const TYPE_NATURAL = 'NATURAL';

    public function __construct(int $discountSize, $discountType)
    {
        $this->discountSize = $discountSize;
        switch ($discountType) {
            case self::TYPE_PERCENT:
                $this->discountType = $discountType;
                break;
            case self::TYPE_NATURAL:
                $this->discountType = $discountType;
                break;
            default:
                throw new \Exception('Неизвестный тип скидки');
        }
    }

    public function getDiscountType(): string
    {
        return env('DISCOUNT_TYPE_'.$this->discountType);
    }

    public function getDiscountSize(): int
    {
        return $this->discountSize;
    }
}
