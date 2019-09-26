<?php

namespace App\Dictionaries;

use LogicException;

class RoleDictionary
{
    public const ROLE_STAR = 'star';
    public const ROLE_CRITIC = 'critic';
    public const ROLE_PROF_CRITIC = 'prof_critic';
    public const ROLE_PERFORMER = 'performer';

    /**
     * Иерархия ролей, поиск идёт по ключам чем больше ключ у роли тем она выше.
     */
    public const hierarchyRoles = [
        self::ROLE_PERFORMER,
        self::ROLE_CRITIC,
        self::ROLE_PROF_CRITIC,
        self::ROLE_STAR,
    ];

    /**
     * @param string $role
     *
     * @return int
     * @throws LogicException
     */
    public static function getKeyRole(string $role): int
    {
        $arraySearch = array_search($role, self::hierarchyRoles, true);
        if (false === $arraySearch) {
            throw new LogicException("Не найлена роль: $role");
        }

        return false === $arraySearch ? null : $arraySearch;
    }
}
