<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentsController extends Controller
{

    /**
     * @param Request $request
     * @return View
     */
    public function get(Request $request): View
    {
        if (!$request->filled('period-payment')) {
            $payments = Payment::all();
        } else{
            // TODO filtros
            $payments = [];
        }
        return view('admin.payments', ['payments' => $payments]);
    }
}
