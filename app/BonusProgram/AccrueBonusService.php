<?php

namespace App\BonusProgram;

use App\Dictionaries\RoleDictionary;
use App\Events\CompletedTaskEvent;
use App\Models\BonusType;
use App\Models\Operation;
use App\Models\Purse;
use App\User;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AccrueBonusService
{
    /**
     * @param string    $bonusName  Контанта название бонуса
     * @param User|null $user
     * @param int       $operations Количество возможный операций по бонусной программе указать -1 для многократных операций
     * @param int|null  $bonusBall  кол-во балов , если не указано берет из БД
     * @param null      $extraData  дополнительные данные
     *
     * @return bool
     */
    public function process(
        string $bonusName,
        ?User $user = null,
        int $operations = 1,
        int $bonusBall = null,
        $extraData = null
    ): bool {
        if (null === $user) {
            return false;
        }

        if (false === $this->participatesInBonusProgram($user)) {
            return false;
        }
        $bonusType = $this->getBonusType($bonusName);

        if (null === $bonusType) {
            return false;
        }

        $purse = $this->getPurse($user);
        $bonus = $bonusBall ?? $bonusType->bonus;

        $countOperation = Operation::countOperation($bonusType, $user, $extraData);
        if ($operations === $countOperation && -1 !== $operations) {
            return false;
        }

        $process = $this->processOperation($bonusType, $bonus, $purse, $extraData);

        if (false === $process) {
            return false;
        }

        event(new CompletedTaskEvent($user, $bonusType->description, $bonus));

        return true;
    }

    /**
     * участвует ли пользователь в бонусной программе.
     *
     * @param User $user
     *
     * @return bool
     */
    private function participatesInBonusProgram(User $user): bool
    {
        return $user->inRoles([
            RoleDictionary::ROLE_LISTENER,
            RoleDictionary::ROLE_PERFORMER,
            RoleDictionary::ROLE_CRITIC,
        ]);
    }

    /**
     * @param BonusType $bonusType
     * @param int       $bonus
     * @param Purse     $purse
     * @param mixed     $extraData
     *
     * @return bool
     */
    private function processOperation(BonusType $bonusType, int $bonus, Purse $purse, $extraData = null): bool
    {
        try {
            $operation = new Operation([
                'direction' => Operation::DIRECTION_INCREASE,
                'amount' => $bonus,
                'description' => $bonusType->name,
                'type_id' => $bonusType->id,
                'extra_data' => $extraData,
            ]);
            $purse->processOperation($operation);
            $process = true;
        } catch (Exception $exception) {
            Log::alert($exception->getMessage());
            $process = false;
        }

        return $process;
    }

    /**
     * @param string $bonusName
     *
     * @return BonusType|null
     */
    private function getBonusType(string $bonusName): ?BonusType
    {
        $keyCache = BonusType::BONUS_TYPE.'_'.$bonusName;
        $bonusType = Cache::remember($keyCache, 60, function () use ($bonusName) {
            return BonusType::query()->where('constant_name', '=', $bonusName)->first();
        });

        return $bonusType;
    }

    /**
     * @param User $user
     *
     * @return Purse
     */
    private function getPurse(User $user): Purse
    {
        $purse = $user->purseBonus;
        if (null === $purse) { //при регистрации через соцсети может не быть кошелька
            $purse = $user->purseBonus()->firstOrCreate(['user_id' => $user->id, 'name' => Purse::NAME_BONUS]);
        }

        return $purse;
    }
}
