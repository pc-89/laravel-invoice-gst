<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\User; // 🌟 Import the User Model

class DashboardController extends Controller
{
    public function index()
    {
        // 🌟 FIXED: Retrieve the collection data records instead of just a raw count integer
        $pendingUsers = User::where('role', 'user')
                            ->whereRaw('is_approved = false')
                            ->get();

        // Optional stats calculation fallback values matching your legacy commented code:
        $totalUsers = User::where('role', 'user')->count();
        $totalInvoices = DB::table('invoices')->count();
        $totalSales = DB::table('invoices')->sum('grand_total');

        return view('dashboard.index', compact(
            'pendingUsers',
            'totalUsers',
            'totalInvoices',
            'totalSales'
        ));
    }
}
