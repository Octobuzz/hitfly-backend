<?php

namespace App\Http\GraphQL\Mutations;

use App\Models\BonusOperationType;
use App\Models\Operation;
use App\Models\Purse;
use App\User;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Mutation;
use GraphQL;
use Exception;

class UseBonusesMutation extends Mutation
{
    protected $attributes = [
        'name' => 'UseBonusesMutation',
        'description' => 'Мутация для растраты бонусов',
    ];

    public function type()
    {
        return GraphQL::type('BonusTypes');
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
        $bonusType = BonusOperationType::query()->where('constant_name', '=', $args['constantBonus'])->first();

        if (null === $bonusType) {
            return null;
        }
        try {
            /** @var User $user */
            $user = Auth::user();
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
