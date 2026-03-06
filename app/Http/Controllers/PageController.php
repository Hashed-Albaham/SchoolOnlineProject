<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * [PP1] Privacy Policy page
     */
    public function privacy()
    {
        return view('pages.privacy');
    }

    /**
     * [PP1] Terms of Service page
     */
    public function terms()
    {
        return view('pages.terms');
    }
}
