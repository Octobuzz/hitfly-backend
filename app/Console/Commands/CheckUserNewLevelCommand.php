<?php

namespace App\Console\Commands;

use App\BuisnessLogic\BonusProgram\UserLevels;
use App\User;
use Illuminate\Console\Command;

class CheckUserNewLevelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hitfly:user:levels {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Проверить достижение нового уровня пользователями';

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
        $userId = $this->argument('user');
        $user = User::find($userId);
        $userLevels = new UserLevels();
        $userLevels->changeUserLevel($user);
    }
}
