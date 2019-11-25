<?php

namespace App\Events\User;

class TopFiveWeekEvent
{
    private $idUser;

    public function __construct(int $idUser)
    {
        $this->idUser = $idUser;
    }

    /**
     * @return int
     */
    public function getIdUser(): int
    {
        return $this->idUser;
    }
}
