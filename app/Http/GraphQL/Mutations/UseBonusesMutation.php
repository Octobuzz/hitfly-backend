<?php

namespace App\Http\GraphQL\Mutations;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use App\Models\BonusType;
use App\Models\Operation;
use App\Models\Purse;
use App\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Exception;

class UseBonusesMutation extends Mutation
{
    use GraphQLAuthTrait;
    protected $attributes = [
        'name' => 'UseBonusesMutation',
        'description' => 'Мутация для растраты бонусов',
    ];

    public function type()
    {
        return \GraphQL::type('BonusTypes');
    }

    public function args()
    {
        return [
            'constantBonus' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Название константы для растраты бонусов',
                'rules' => ['required', 'string', 'max:255'],
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $bonusType = BonusType::query()->where('constant_name', '=', $args['constantBonus'])->first();

        if (null === $bonusType) {
            return null;
        }
        try {
            /** @var User $user */
            $user = $this->getGuard()->user();
            /** @var Purse $purse */
            $purse = $user->purseBonus()->first();
            if (null === $purse) {
                $purse = new Purse([
                    'name' => Purse::NAME_BONUS,
                    'balance' => 0,
                ]);
                $user->purse()->save($purse);
                $purse->save();
            }
            $operation = new Operation([
                'direction' => Operation::DIRECTION_DECREASE,
                'amount' => $bonusType->bonus,
            ]);
            $purse->processOperation($operation);
        } catch (Exception $exception) {
            $bonusType = null;
        }

        return $bonusType;
    }
}
