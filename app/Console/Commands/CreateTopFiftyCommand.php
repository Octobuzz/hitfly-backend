<?php

namespace App\Console\Commands;

use App\BuisnessLogic\TopFifty;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class CreateTopFiftyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hitfly:create_top_fifty';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создание Топ 50';

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
        $topFifty = Cache::get(TopFifty::TOP_FIFTY_KEY, []);

        if (true === empty($topFifty)) {
            return;
        }

        $carbon = Carbon::now();
        $topFiftyReturn = [];
        foreach ($topFifty as $key => $users) {
            foreach ($users as $keyUser => $date) {
                $date = $carbon->diffInDays($date);
                if ($date > 1) {
                    unset($users[$keyUser]);
                }
            }
            $topFiftyReturn[$key] = count($users);
        }
        arsort($topFiftyReturn);
        $arrTopFifty = array_slice($topFiftyReturn, 0, 50, true);
        Cache::forever(TopFifty::TOP_FIFTY_KEY_CALCULATED, array_keys($arrTopFifty));
    }
}
