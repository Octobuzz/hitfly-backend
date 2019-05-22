<?php

namespace App\Observers;

use App\Models\EmailChange;
use App\User;

class EmailChangeObserver
{
    public function updating(User $user)
    {
        $emailChange = EmailChange::findOrCreate(['new_email' => $user->email]);
        var_dump($emailChange);
        die();

        return false;
    }

    public function saving(User $user)
    {
        $emailChange = EmailChange::findOrCreate(['new_email' => $user->email]);
        var_dump($emailChange);
        die();

        return false;
    }
}
