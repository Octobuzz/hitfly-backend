<?php

namespace App\Console\Commands;

use App\Events\Track\CreatedMusicWaveEvent;
use App\Models\Track;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
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
    protected $signature = 'hitfly:fft:tracks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создание спектра сигранала для треков';

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
        Track::query()
            ->where('state', '=', Track::CREATE_WAVE)
            ->chunk(10, function ($tracks) {
                /** @var Track $track */
                foreach ($tracks as $track) {
                    try {
                        $this->createMusicWaveTrack($track);
                        $track->state = Track::CONVERT_TRACK;
                        $track->save();

                        CreatedMusicWaveEvent::dispatch($track);
                        continue;
                    } catch (ProcessFailedException $exception) {
                        Log::alert($exception->getMessage(), $exception->getTrace());
                    }
                    $track->state = Track::PENDING;
                    $track->save();
                }
            });
    }

    private function createMusicWaveTrack(Track $track): bool
    {
        $trackFile = Storage::disk('public')->path($track->filename);

        $tmpFile = $this->convertTrack($trackFile);
        $musicWave = $this->createMusicWaveTMP($tmpFile);

        $track->music_wave = $musicWave;

        return true;
    }

    private function convertTrack($trackFile): string
    {
        $newNameWav = uniqid().'.wav';
        $tmpFile = self::TMP_DIR.$newNameWav;

        $processConvert = new Process(['./ffmpeg',  '-i', "$trackFile",  "$tmpFile"]);

        $processConvert->run();
        // executes after the command finishes
        if (!$processConvert->isSuccessful()) {
            throw new ProcessFailedException($processConvert);
        }

        return $tmpFile;
    }

    private function createMusicWaveTMP($tmpFile): array
    {
        $process = new Process(['/usr/bin/python3.5', './lib/main.py',  "$tmpFile"]);
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        $response = json_decode($process->getOutput());

        if (null === $response) {
            return [];
        }

        return $response;
    }
}
