<?php

namespace App\Rules;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use Illuminate\Contracts\Validation\Rule;

class CanBuyProduct implements Rule
{
    use GraphQLAuthTrait;

    /**
     * разрешено ли покупать товары пользователю.
     */
    public function __construct()
    {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $user = $this->getGuard()->user();

        return ($user->inRoles(['prof_critic', 'star'])) ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.canBuyProduct');
    }
}
