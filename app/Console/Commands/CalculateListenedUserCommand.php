<?php

namespace App\Console\Commands;

use App\BuisnessLogic\TopFifty;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class CalculateListenedUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hitfly:calculated_listening_user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Количество пользователей просушивающих треки за последние 24 часа';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $lestenedUser = Cache::get(TopFifty::LISTENED_USER_KEY, []);
        if (true === empty($lestenedUser)) {
            return;
        }

        $carbon = Carbon::now();

        foreach ($lestenedUser as $idUser => $date) {
            if ($carbon->diffInDays($date) > 0) {
                unset($lestenedUser[$idUser]);
            }
        }

        Cache::forever(TopFifty::LISTENED_USER_KEY, $lestenedUser);

        $idsUser = array_keys($lestenedUser);
        Cache::forever(TopFifty::LISTENED_USER_KEY_CALCULATED, count($idsUser));
    }
}
