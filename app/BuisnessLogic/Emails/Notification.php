<?php

namespace App\BuisnessLogic\Emails;

use App\BuisnessLogic\Recommendation\Recommendation;
use App\Jobs\BirthdayCongratulationsEmailJob;
use App\Jobs\FewCommentsJob;
use  App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Log\Logger;
use App\BuisnessLogic\Playlist\Tracks;

class Notification
{
    private $listOfUsers;
    private $recommendation;

    public function __construct(Recommendation $recommendation)
    {
        $this->recommendation = $recommendation;
    }

    /**
     * очередь писем с поздравлениями на день рождения
     */
    public function birthdayCongratulation(){

        $this->listOfUsers = $this->getUsersBirthdayToday();
        $recommend = $this->recommendation;
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

    public function fewComments(){

        $users = $this->getUsersWithFewComments();

        $tracks = new Tracks();
        foreach ($users as $user){
            dispatch(new FewCommentsJob($user,$tracks->getNewTracks(3)))->onQueue('low');
        }

    }

    /**
     * список пользователей, которые за неделю оставили мало отзывов
     * @return User[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    private function getUsersWithFewComments(){

        $users = User::query()->whereIn('id', function ($query){
            $query->select('user_id') ->from('admin_role_users')->where('role_id','=','4')->whereNotIn('user_id',function ($query2){
                $query2->select('user_id')
                    ->from('comments')
                    ->whereBetween('created_at',[Carbon::now()->startOfWeek(),Carbon::now()->endOfDay()])
                    ->groupBy('user_id')
                    ->havingRaw('count(`user_id`) >= ?', [env('FEW_FEEDBACK_PERIOD')]);
                });
            }

            )->get();


        return $users;
    }
}
