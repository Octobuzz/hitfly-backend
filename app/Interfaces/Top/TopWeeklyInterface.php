<?php

namespace App\Interfaces\Top;

use App\Models\Track;

/**
 * Топ недели
 * Interface TopWeeklyInterface.
 */
interface TopWeeklyInterface
{
    const TOP_WEEKLY_KEY = 'TOP_FIFTY_KEY';
    const TOP_WEEKLY_KEY_CALCULATED = 'TOP_FIFTY_KEY_CALCULATED';

    public function add(Track $track);

    public function calculate(): void;

    public function get(): array;
}
