<?php

namespace App\BuisnessLogic\Emails;

use App\BuisnessLogic\Recommendation\Recommendation;
use App\Jobs\BirthdayCongratulationsEmailJob;
use App\Http\Controllers\Controller;
use  App\User;
use Carbon\Carbon;

class NotificationController extends Controller
{
    private $listOfUsers;

    /**
     * очередь писем с поздравлениями на день рождения
     */
    public function birthdayCongratulation(){

        $this->listOfUsers = $this->getUsersBirthdayToday();
        $recommend = new Recommendation();
        foreach ($this->listOfUsers as $user){
            dispatch(new BirthdayCongratulationsEmailJob($user,$recommend->getBirthdayPlayLists($user)))->onQueue('low');
        }

    }

    /**
     * Получает пользователей у которых сегодня день рождения
     * @return User[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    protected function getUsersBirthdayToday(){
        return User::query()->whereMonth('birthday', Carbon::today()->month)->whereDay('birthday', Carbon::today()->day)->whereNotNull('email')->get();
    }
}
