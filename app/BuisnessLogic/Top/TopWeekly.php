<?php

namespace App\BuisnessLogic\Top;

use App\Interfaces\Top\TopWeeklyInterface;
use App\Models\Track;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class TopWeekly implements TopWeeklyInterface
{
    public function add(Track $track): void
    {
        $topWeekly = Cache::get(self::TOP_WEEKLY_KEY, []);

        $user = Auth::user();
        $userIndetification = (null === $user) ? Session::get('id') : $user->id;
        $topWeekly[$track->id][$userIndetification] = empty($topWeekly[$track->id][$userIndetification]) ? 0 : $topWeekly[$track->id][$userIndetification];
        $topWeekly[$track->id][$userIndetification] = now();

        Cache::forever(self::TOP_WEEKLY_KEY, $topWeekly);
    }

    public function calculate(): void
    {
        $topWeekly = Cache::get(self::TOP_WEEKLY_KEY, []);
        if (empty($topWeekly)) {
            return;
        }

        $carbon = Carbon::now();
        $topWeeklyCalculated = [];
        foreach ($topWeekly as $idTrack => $users) {
            $countUser = 0;
            foreach ($users as $keyId => $userDate) {
                if ($carbon->diffInDays($userDate) >= 7) {
                    unset($topWeekly[$idTrack][$keyId]);
                    continue;
                }
                ++$countUser;
            }
            $topWeeklyCalculated[$idTrack] = $countUser;
        }

        arsort($topWeeklyCalculated);
        $topWeeklyCalculated = array_slice($topWeeklyCalculated, 0, 100, true);
        Cache::forever(self::TOP_WEEKLY_KEY_CALCULATED, array_keys($topWeeklyCalculated));
    }

    public function get(): array
    {
        return Cache::get(self::TOP_WEEKLY_KEY_CALCULATED, []);
    }
}
