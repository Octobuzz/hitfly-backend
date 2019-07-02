<?php

namespace App\Console\Commands;

use App\Events\Track\CreatedMusicWaveEvent;
use App\Models\Track;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class MusicalWaveCommand extends Command
{
    const TMP_DIR = '/tmp/';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hitfly:musical_wave {track}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        try {
            $trackId = $this->argument('track');

            $track = Track::query()->find($trackId);
            if (true === empty($track)) {
                return;
            }

            $trackFile = Storage::disk('public')->path($track->filename);

            $newNameWav = uniqid().'.wav';
            $tmpFile = self::TMP_DIR.$newNameWav;

            $processConvert = new Process(['/usr/bin/ffmpeg',  '-i', "$trackFile",  "$tmpFile"]);

            $processConvert->run();
            // executes after the command finishes
            if (!$processConvert->isSuccessful()) {
                throw new ProcessFailedException($processConvert);
            }

            $process = new Process(['/usr/bin/python3', './lib/main.py',  "$tmpFile"]);
            $process->run();

            // executes after the command finishes
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
            $response = json_decode($process->getOutput());

            if (null === $response) {
                return;
            }
            $track->music_wave = $response;
            $track->save();

            CreatedMusicWaveEvent::dispatch($track);
        } catch (ProcessFailedException $exception) {
            echo $exception->getMessage();
        }
    }
}
