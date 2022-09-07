<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class GarcomController extends Controller
{
    /**
     * @return View
     */
    public function get(): View
    {
        return view('garcom.dashboard');
    }
}
