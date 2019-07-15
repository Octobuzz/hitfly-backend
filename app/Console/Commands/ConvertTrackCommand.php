<?php

namespace App\Console\Commands;

use App\Models\Track;
use getID3;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ConvertTrackCommand extends Command
{
    const TMP_DIR = '/tmp/';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hitfly:convert:tracks';

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
        Track::query()
            ->where('state', '=', Track::CONVERT_TRACK)
            ->chunk(10, function ($tracks) {
                /** @var Track $track */
                foreach ($tracks as $track) {
                    $driver = new getID3();
                    $info = $driver->analyze(Storage::disk('public')->path($track->filename));
                    try {
                        $oldName = $track->filename;
                        if ($info['audio']['bitrate'] > 192000) {
                            $this->convertToMp3($track);
                        }
                        $this->convertToStandartmp3($track);
                        $track->state = Track::PUBLISHED;

                        if ($oldName !== $track->filename) {
                            Storage::disk('public')->delete($oldName);
                        }
                    } catch (ProcessFailedException $exception) {
                        $track->state = Track::PENDING;
                    }

                    $track->save();
                }
            });
    }

    /**
     * @param Track $track
     *
     * @throws ProcessFailedException
     */
    private function convertToStandartmp3(Track $track)
    {
        $newName192 = uniqid().'.mp3';
        $tmpFile192 = Storage::disk('public')->path($track->getPathTrack().DIRECTORY_SEPARATOR.$newName192);

        $trackFile = Storage::disk('public')->path($track->filename);

        $processConvert = new Process(['./ffmpeg',  '-i', "$trackFile", '-ab', '192k', '-map_metadata', '0', '-id3v2_version', '3', "$tmpFile192"]);
        $processConvert->run();
        // executes after the command finishes
        if (!$processConvert->isSuccessful()) {
            throw new ProcessFailedException($processConvert);
        }

        $track->filename = $track->getPathTrack().DIRECTORY_SEPARATOR.$newName192;
    }

    /**
     * @param Track $track
     *
     * @throws ProcessFailedException
     */
    private function convertToMp3(Track $track)
    {
        $newName = uniqid().'.mp3';
        $tmpFile320 = Storage::disk('public')->path($track->getPathTrack().DIRECTORY_SEPARATOR.$newName);

        $trackFile = Storage::disk('public')->path($track->filename);

        $processConvert = new Process(['./ffmpeg',  '-i', "$trackFile", '-ab', '320k', '-map_metadata', '0', '-id3v2_version', '3', "$tmpFile320"]);
        $processConvert->run();
        // executes after the command finishes
        if (!$processConvert->isSuccessful()) {
            throw new ProcessFailedException($processConvert);
        }

        $track->bitrate_hight = $track->getPathTrack().DIRECTORY_SEPARATOR.$newName;
    }
}
