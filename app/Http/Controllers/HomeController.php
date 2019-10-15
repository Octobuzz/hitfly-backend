<?php

namespace App\Http\Controllers;

use App\BuisnessLogic\Sitemap\SitemapGenerator;
use Illuminate\Support\Facades\Cookie;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($asdadsa = null)
    {
        if (null === \Auth::user()) {
//             return redirect('/login');
        } else {
            Cookie::queue(\App\Services\Auth\JsonGuard::HEADER_NAME_TOKEN, \Auth::user()->access_token, 864000);
        }

        return view('spa');
    }

    public function map()
    {
        $sitemap = new SitemapGenerator();
        $htmlMap = $sitemap->getHTMLMap();
        return view('map', ['htmlMap' =>$htmlMap]);
    }

    public function policy($asdadsa = null)
    {
        return view('policy');
    }
}
