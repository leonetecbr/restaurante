<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DashboardController extends Controller
{

    /**
     * @return View
     */
    public function admin(): View
    {
        return view('admin.dashboard');
    }

    /**
     * @return View
     */
    public function garcom(): View
    {
        return view('garcom.dashboard');
    }
}
