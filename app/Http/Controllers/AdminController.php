<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AdminController extends Controller
{

    /**
     * @return View
     */
    public function get(): View
    {
        return view('admin.dashboard');
    }

    /**
     * @return View
     */
    public function orders(): View
    {
        return view('admin.orders');
    }
}
