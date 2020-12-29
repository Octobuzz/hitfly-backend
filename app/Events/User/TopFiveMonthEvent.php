<?php

namespace App\Events\User;

class TopFiveMonthEvent
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
