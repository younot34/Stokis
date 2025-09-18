<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function outgoing(Request $request)
    {
        $warehouses = Warehouse::all();

        // Filter tanggal / bulan
        $date = $request->input('date'); // untuk harian
        $month = $request->input('month'); // format: YYYY-MM
        $warehouseId = $request->input('warehouse_id');

        $query = Transaction::where('type', 'out');

        if($warehouseId) {
            $query->where('warehouse_id', $warehouseId);
        }

        // Filter harian
        if($date) {
            $query->whereDate('created_at', $date);
        }

        // Filter bulanan
        if($month) {
            $query->whereYear('created_at', Carbon::parse($month)->year)
                  ->whereMonth('created_at', Carbon::parse($month)->month);
        }

        $transactions = $query->with('product','warehouse')->get();

        return view('admin.reports.outgoing', compact(
            'transactions','warehouses','warehouseId','date','month'
        ));
    }
}
