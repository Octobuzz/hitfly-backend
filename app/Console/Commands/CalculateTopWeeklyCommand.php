<?php

namespace App\Console\Commands;

use App\Interfaces\Top\TopWeeklyInterface;
use Illuminate\Console\Command;

class CalculateTopWeeklyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hitfly:calculate:top_weekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создание топ недели';

    private $topWeekly;

    /**
     * CalculateTopWeeklyCommand constructor.
     *
     * @param TopWeeklyInterface $topWeekly
     */
    public function __construct(TopWeeklyInterface $topWeekly)
    {
        parent::__construct();
        $this->topWeekly = $topWeekly;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->topWeekly->calculate();
    }
}
