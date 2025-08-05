<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) // Inject Request object
    {
        $transactionsQuery = Transaction::with('user')->with('orders.product')->latest();

        // Check for date range parameters
        if ($request->has(['start_date', 'end_date'])) {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();

            $transactionsQuery->whereBetween('created_at', [$startDate, $endDate]);
        } else {
            $startDate = Carbon::today()->subDays(7)->startOfDay();
            $endDate = Carbon::today()->endOfDay();

            $transactionsQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        $transactions = $transactionsQuery->get(); // Get the filtered transactions

        return Inertia::render('transactions/Index', [
            'transactions' => $transactions,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        if (Auth::user()->name == 'hakim') {
            $transaction->delete();

            // 2. Redirect back with a success message for Inertia.js
            return back()->with('message', 'Transaction deleted successfully!');
        } else {
            // 3. Handle Unauthorized Access
            // Option A: Redirect back with an error message
            return back()->with('error', 'You are not authorized to delete transactions!');

            // Option B: Abort with a 403 Forbidden status
            // abort(403, 'Unauthorized action.'); // This will show a generic error page
        }
    }
}
