<?php

namespace App\Console\Commands;

use App\BuisnessLogic\TopFifty;
use App\Events\CreatedTopFiftyEvent;
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
    protected $storeDays;
    protected $topDays;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
        $this->storeDays = config('bonus.topFifty.storeDays');
        $this->topDays = config('bonus.topFifty.topDays');
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

                if ($date > $this->topDays) {
                    unset($users[$keyUser]);
                }
                if ($date > $this->storeDays) {
                    unset($topFifty[$key][$keyUser]);
                }
            }

            $count = count($users);
            if (0 === $count) {
                continue;
            }
            $topFiftyReturn[$key] = $count;
        }

        $topFifty = array_filter($topFifty, function ($song) {
            return !empty($song);
        });

        Cache::forever(TopFifty::TOP_FIFTY_KEY, $topFifty);
        arsort($topFiftyReturn);

        $arrTopFifty = array_slice($topFiftyReturn, 0, 50, true);
        $value = array_keys($arrTopFifty);

        if (false === empty($value)) {
            Cache::forever(TopFifty::TOP_FIFTY_KEY_CALCULATED, $value);
            event(new CreatedTopFiftyEvent($value));
        }
    }
}
