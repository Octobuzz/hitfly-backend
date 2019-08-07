<?php

namespace App\Jobs;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Artisan;

/**
 * проверка на получение нового уровня пользователем
 * Class CheckUserNewLevelJob.
 */
class CheckUserNewLevelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $userList = User::query()->where('level', '<>', User::LEVEL_SUPER_MUSIC_LOVER)->get();
        foreach ($userList as $user) {
            Artisan::queue('hitfly:user:levels', ['user' => $user->id])->onQueue('low');
        }
    }
}
