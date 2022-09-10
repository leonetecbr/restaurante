<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Table;
use Exception;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * @return View
     * @throws Exception
     */
    public function admin(): View
    {
        $date_end = date('Y-m-d');
        $date_start = date('Y-m-d', strtotime('-6 days'));
        $query = Payment::whereDate('time', '>=', $date_start);
        $query = $query->whereDate('time', '<=', $date_end)->get()->toArray();
        $dates = [
            date('d/m', strtotime('-6 days')),
            date('d/m', strtotime('-5 days')),
            date('d/m', strtotime('-4 days')),
            date('d/m', strtotime('-3 days')),
            date('d/m', strtotime('-2 days')),
            date('d/m', strtotime('-1 days')),
            date('d/m'),
        ];
        $result = $this->countData($query, $dates);
        return view('admin.dashboard', $result);
    }

    /**
     * @param array $data
     * @param string[] $dates
     * @return array
     * @throws Exception
     */
    private function countData(array $data, array $dates): array
    {
        $transactions = [0, 0, 0, 0, 0, 0, 0];
        $sales = [0, 0, 0, 0, 0, 0, 0];
        $values = [0, 0, 0, 0, 0, 0, 0];

        foreach ($data as $pay) {
            $time = date('d/m', strtotime($pay['time']));
            foreach ($dates as $i => $date) {
                if ($time === $date) {
                    $transactions[$i] += 1;
                    if ($pay['client'] === 1) {
                        $sales[$i] += 1;
                    }
                    $values[$i] += $pay['value'];
                }
            }
        }

        return [
            'dates' => $dates,
            'transactions' => $transactions,
            'sales' => $sales,
            'values' => $values,
        ];
    }

    /**
     * @return View
     */
    public function garcom(): View
    {
        $tables = Table::all();
        return view('garcom.dashboard', ['tables' => $tables]);
    }
}
