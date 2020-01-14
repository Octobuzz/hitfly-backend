<?php

namespace App\Console;

use App\BuisnessLogic\Emails\Notification;
use App\Console\Commands\CalculateListenedUserCommand;
use App\Console\Commands\CalculateListeningTrackCommand;
use App\Console\Commands\CalculateTopWeeklyCommand;
use App\Console\Commands\ConvertTrackCommand;
use App\Console\Commands\CreatePlayTimeTrackCommand;
use App\Console\Commands\CreateTopFiftyCommand;
use App\Console\Commands\GenerateSitemap;
use App\Console\Commands\MusicalWaveCommand;
use App\Jobs\CheckUserNewLevelJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    const TIME_CREATE_TOP_FIFTY = '01:00';
    const TIME_GENERATE_SITEMAP = '01:10';
    private $notification;

    public function __construct(Application $app, Dispatcher $events, Notification $notification)
    {
        parent::__construct($app, $events);
        $this->notification = $notification;
    }

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        CreatePlayTimeTrackCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        //Поздравления с днем рождения
//        $schedule->call(function () {
//            $this->notification->birthdayCongratulation();
//        })->dailyAt('10:00');
        $schedule->call(function () {
            $this->notification->fewComments('week');
        })->weekly()->at('10:00');
        $schedule->call(function () {
            $this->notification->fewComments('month');
        })->monthlyOn(date('t'), '10:00');
        $schedule->call(function () {
            $this->notification->longAgoNotVisited();
        })->dailyAt('10:00');
        $schedule->call(function () {
            $this->notification->everyMonthDispatchNotVisited();
        })->monthlyOn(1, '10:00');

//        $schedule->call(function () {
//            $this->notification->remindForEvent();
//        })->dailyAt('10:00');
//        $schedule->call(function () {
//            $this->notification->reachTop(50);
//        })->dailyAt('10:00');
        // Создание топ 50 по понедельникам в 1 час ночи
        $schedule->command(CreateTopFiftyCommand::class)->weeklyOn(1, self::TIME_CREATE_TOP_FIFTY);
        $schedule->command(CalculateListeningTrackCommand::class)->everyTenMinutes();
        $schedule->command(CalculateListenedUserCommand::class)->everyTenMinutes();
        $schedule->command(MusicalWaveCommand::class)->everyMinute()->name('create_music_wave')->withoutOverlapping();
        $schedule->command(ConvertTrackCommand::class)->everyMinute()->name('convert_track')->withoutOverlapping();
        $schedule->command(CalculateTopWeeklyCommand::class)->weeklyOn(1);
        $schedule->command(GenerateSitemap::class)->dailyAt(self::TIME_GENERATE_SITEMAP);

        $schedule->job(new CheckUserNewLevelJob())->name('check_user_new_level')->dailyAt('01:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
