<?php

namespace App\Console\Commands;

use App\BuisnessLogic\TopFifty;
use App\Models\Track;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class CalculateListeningTrackCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hitfly:calculated_listening_track';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создание списка прослушанных треков за последние 24 часа';

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
        $lestenedTrack = Cache::get(TopFifty::LISTENED_TRACK_KEY, []);
        if (true === empty($lestenedTrack)) {
            return;
        }

        $carbon = Carbon::now();

        foreach ($lestenedTrack as $idTrack => $date) {
            if (null === Track::query()->find($idTrack)) {
                unset($lestenedTrack[$idTrack]);
                continue;
            }
            if ($carbon->diffInDays($date) > 0) {
                unset($lestenedTrack[$idTrack]);
            }
        }

        Cache::forever(TopFifty::LISTENED_TRACK_KEY, $lestenedTrack);

        $idsTrack = array_slice(array_keys($lestenedTrack), 0, 10000, true);
        Cache::forever(TopFifty::LISTENED_TRACK_KEY_CALCULATED, $idsTrack);
    }
}
