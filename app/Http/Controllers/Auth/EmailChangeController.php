<?php

namespace App\Http\Controllers\Auth;

use App\Jobs\EmailChangedJob;
use App\Models\EmailChange;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;

class EmailChangeController extends Controller
{
    public function changeEmail($id, $token)
    {
        $tokenCollection = EmailChange::query()->where('user_id', '=', $id)->get();

        foreach ($tokenCollection as $row) {

            if (null !== $row && $row->token == $token && Carbon::now() < $row->updated_at->addMinute(60)) {
                $user = User::find($id);
                $oldEmail = $user->email;
                $user->email = $row->new_email;
                $user->save();
                EmailChange::query()->where('user_id', '=', $id)->delete();
                dispatch(new EmailChangedJob($user, $oldEmail))->onQueue('low');

                return redirect('/email-change');
            }
        }

        return redirect('/email-change-failed');
    }

    public function emailChanged()
    {
        return view('auth.emailChanged');
    }

    public function emailChangeFailed()
    {
        return view('auth.emailChangeFailed');
    }
}
