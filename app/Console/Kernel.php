<?php

namespace App\Console;

use App\BuisnessLogic\Emails\Notification;
use App\Console\Commands\CalculateListenedUserCommand;
use App\Console\Commands\CalculateListeningTrackCommand;
use App\Console\Commands\ConvertTrackCommand;
use App\Console\Commands\CreatePlayTimeTrackCommand;
use App\Console\Commands\CreateTopFiftyCommand;
use App\Console\Commands\MusicalWaveCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    const TIME_CREATE_TOP_FIFTY = '01:00';
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
        $schedule->call(function () {
            $this->notification->birthdayCongratulation();
        })->dailyAt('10:00');
        $schedule->call(function () {
            $this->notification->fewComments();
        })->weekly()->at('10:00');
        $schedule->call(function () {
            $this->notification->longAgoNotVisited();
        })->dailyAt(1, '10:00');
        $schedule->call(function () {
            $this->notification->everyMonthDispatchNotVisited();
        })->monthlyOn(1, '10:00');

        $schedule->call(function () {
            $this->notification->remindForEvent();
        })->dailyAt('10:00');
        $schedule->call(function () {
            $this->notification->reachTop(20);
        })->dailyAt('10:00');
        // Создание топ 50 каждый день в 1 час ночи
        $schedule->command(CreateTopFiftyCommand::class)->dailyAt(self::TIME_CREATE_TOP_FIFTY);
        $schedule->command(CalculateListeningTrackCommand::class)->everyTenMinutes();
        $schedule->command(CalculateListenedUserCommand::class)->everyTenMinutes();
        $schedule->command(MusicalWaveCommand::class)->everyFiveMinutes()->name('create_music_wave')->withoutOverlapping();
        $schedule->command(ConvertTrackCommand::class)->everyFiveMinutes()->name('convert_track')->withoutOverlapping();
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
