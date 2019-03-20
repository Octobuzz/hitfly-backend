<?php

namespace App\BuisnessLogic\Emails;

use App\BuisnessLogic\Recommendation\Recommendation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\BirthdayCongratulation;
use Mail;
use  App\User;
use Carbon\Carbon;

class NotificationController extends Controller
{
    private $listOfUsers;

    public function birthdayCongratulation(){
        $this->listOfUsers = $this->getUsersBirthdayToday();
        $recommend = new Recommendation();
        foreach ($this->listOfUsers as $user){
            Mail::to($user->email)->queue(new BirthdayCongratulation($user,$recommend->getBirthdayPlayLists($user)));
            die();
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
