<?php

namespace App\Dictionaries;

use LogicException;

class RoleDictionary
{
    public const ROLE_STAR = 'star'; // Звезда
    public const ROLE_CRITIC = 'critic'; //Критик
    public const ROLE_PROF_CRITIC = 'prof_critic'; // Проф критик
    public const ROLE_PERFORMER = 'performer'; // исполнитель
    public const ROLE_LISTENER = 'listener'; // Слушатель
    public const ROLE_ADMIN = 'administrator'; // Администратор

    /**
     * Иерархия ролей, поиск идёт по ключам чем больше ключ у роли тем она выше.
     */
    public const hierarchyRoles = [
        self::ROLE_LISTENER,
        self::ROLE_PERFORMER,
        self::ROLE_CRITIC,
        self::ROLE_PROF_CRITIC,
        self::ROLE_STAR,
    ];

    /**
     * @param string $role
     *
     * @return int
     *
     * @throws LogicException
     */
    public static function getKeyRole(string $role): int
    {
        $arraySearch = array_search($role, self::hierarchyRoles, true);
        if (false === $arraySearch) {
            throw new LogicException("Не найдена роль: $role");
        }

        return $arraySearch;
    }

    public static function getPreviousRoleSlug(string $role): string
    {
        $arraySearch = array_search($role, self::hierarchyRoles, true);
        if (false === $arraySearch) {
            throw new LogicException("Не найдена роль: $role");
        }
        $oldKeyRole = $arraySearch - 1;
        if (false === isset(self::hierarchyRoles[$oldKeyRole])) {
            throw new LogicException('Не существующая роль');
        }

        return self::hierarchyRoles[$oldKeyRole];
    }
}
