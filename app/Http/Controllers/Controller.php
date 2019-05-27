<?php

namespace App\Http\Controllers;

use App\Models\Favourite;
use App\Models\Track;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function test()
    {
        $tmp['favouriteable_type'] = Track::class;
        $tmp['favouriteable_id'] = 156;
        $tmp['user_id'] = Auth::user()->id;
        $favourite = Favourite::create($tmp);
        $favourite->save();

        return response()->json($favourite);
    }
}
