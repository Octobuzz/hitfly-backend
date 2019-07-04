<?php

namespace App\Console\Commands;

use App\Models\Track;
use getID3;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CreatePlayTimeTrackCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hitfly:create_play_time {track}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'oбновить длину трека';

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
        $trackId = $this->argument('track');

        $track = Track::query()->find($trackId);
        if (true === empty($track)) {
            return;
        }

        $driver = new getID3();

        $info = $driver->analyze(Storage::disk('public')->path($track->filename));
        $track->length = $info['playtime_seconds'];
        $track->save();

        return;
    }
}
