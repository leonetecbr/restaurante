<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\Routing\Annotation\Route;

class PaymentsController extends Controller
{

    /**
     * Gera o histórico de pagamentos
     *
     * @param Request $request
     * @return View
     * @throws Exception
     */
    #[Route('/admin/payments', name: 'admin.payments', methods: 'get')]
    public function get(Request $request): View
    {
        if (!$request->filled('period-payment')) {// Todos os períodos
            $payments = Payment::orderBy('table_id')->paginate();
        } else {
            $days = $request->input('period-payment');
            if ($days != -1) {
                $date_end = date('Y-m-d');
                $date_start = ($days != 0) ?
                    date('Y-m-d', strtotime('-' . ($days - 1) . ' days')) : // Demais períodos
                    $date_end; // Hoje
            } else {
                // Ontem
                $date_start = date('Y-m-d', strtotime('-1 days'));
                $date_end = $date_start;
            }

            $query = Payment::query();
            $query->whereDate('time', '>=', $date_start);
            $query->whereDate('time', '<=', $date_end)->orderBy('table_id');
            $payments = $query->paginate();
        }
        return view('admin.payments', ['payments' => $payments]);
    }
}
