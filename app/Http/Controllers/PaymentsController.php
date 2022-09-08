<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentsController extends Controller
{

    /**
     * @param Request $request
     * @return View
     * @throws Exception
     */
    public function get(Request $request): View
    {
        if (!$request->filled('period-payment')) {
            $payments = Payment::paginate();
        } else{
            $payments = [];
            $days = $request->input('period-payment');
            if ($days != -1) {
                $date_end = date('Y-m-d');
                $date_start = ($days != 0) ?
                                            date('Y-m-d', strtotime('-' . ($days - 1) . ' days')):
                                            $date_end;
            }else{
                $date_start =  date('Y-m-d', strtotime('-1 days'));
                $date_end = $date_start;
            }

            $query = Payment::query();
            $query->whereDate('time', '>=', $date_start);
            $query->whereDate('time', '<=', $date_end);
            $payments = $query->paginate();
        }
        return view('admin.payments', ['payments' => $payments]);
    }
}
