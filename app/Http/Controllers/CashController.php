<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CashOpening;
use App\Models\CashClosing;
use App\Models\Expense;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CashController extends Controller
{
    public function openingForm()
    {
        // Ambil closing terakhir untuk pre-fill opening amount
        $lastClosing = CashClosing::latest('date')->first();
        $suggestedAmount = $lastClosing ? $lastClosing->actual_cash : 0;

        return view('cash.opening', compact('suggestedAmount', 'lastClosing'));
    }

    public function saveOpening(Request $request)
    {
        $request->validate([
            'opening_amount' => 'required|integer|min:0',
        ]);

        $activeShift = CashOpening::whereDoesntHave('cashClosing')->latest('date')->first();
        if ($activeShift) {
            return redirect()->route('dashboard.index')
                ->with('error', 'Masih ada shift aktif. Tutup kasir dulu sebelum membuka shift baru.');
        }

        // Cek apakah ada closing owner di hari yang sama yang menutup shift 1 (no_next_shift = true)
        $lastClosingToday = CashClosing::whereDate('date', today())
            ->whereHas('opening', function($q) {
                $q->where('owner_closed_same_day', true);
            })
            ->latest('date')
            ->first();

        if ($lastClosingToday) {
            return redirect()->route('dashboard.index')
                ->with('error', 'Owner telah menutup shift hari ini. Tidak boleh membuka shift baru pada hari yang sama.');
        }

        $todayShifts = CashOpening::whereDate('date', today())->count();
        $shiftNumber = $todayShifts + 1;

        CashOpening::create([
            'user_id'        => Auth::id(),
            'opening_amount' => $request->opening_amount,
            'date'           => now(),
            'shift_number'   => $shiftNumber,
        ]);

        return redirect()->route('dashboard.index')->with(
            'success',
            "Shift {$shiftNumber} dibuka dengan kas awal Rp " . number_format($request->opening_amount, 0, ',', '.')
        );
    }

    public function closingForm()
    {
        $opening = CashOpening::whereDoesntHave('cashClosing')->latest('date')->first();

        if (!$opening) {
            return redirect()->route('kas.opening.form')
                ->with('error', 'Belum ada shift aktif. Buka kasir terlebih dahulu.');
        }

        // PENTING: Hanya ambil transactions yang dibuat SETELAH shift dibuka
        $transactions = Transaction::where('status', 'paid')
            ->where('created_at', '>=', $opening->date)  // >= opening date, bukan between
            ->get();

        $totalSales   = $transactions->sum('total');
        $totalCash    = $transactions->where('payment_method', 'cash')->sum('total');
        $totalNonCash = $transactions->where('payment_method', '!=', 'cash')->sum('total');

        // Hanya ambil expenses yang terikat pada shift saat ini
        $expenses = Expense::where('cash_opening_id', $opening->id)
            ->where('date', '>=', $opening->date)  // hanya dari shift ini
            ->get();
        $totalExpenses = $expenses->sum('amount');

        $expectedCash = $totalCash - $totalExpenses;

        return view('cash.closing', compact(
            'totalSales',
            'totalCash',
            'totalNonCash',
            'totalExpenses',
            'expectedCash',
            'opening',
            'expenses'
        ));
    }

    public function saveClosing(Request $request)
    {
        $request->validate([
            'actual_cash'             => 'required|integer|min:0',
            'expenses.*.expense_name' => 'nullable|string|max:255',
            'expenses.*.amount'       => 'nullable|integer|min:0',
        ]);

        try {
            DB::transaction(function () use ($request) {

                $opening = CashOpening::whereDoesntHave('cashClosing')->latest('date')->first();
                if (!$opening) {
                    throw new \Exception('Tidak ada shift aktif untuk ditutup.');
                }

                $closingTime = now();

                // PENTING: Ambil transactions yang dibuat SETELAH shift dibuka
                $transactions = Transaction::where('status', 'paid')
                    ->where('created_at', '>=', $opening->date)
                    ->get();

                $totalSales   = $transactions->sum('total');
                $totalCash    = $transactions->where('payment_method', 'cash')->sum('total');
                $totalNonCash = $transactions->where('payment_method', '!=', 'cash')->sum('total');

                if ($request->has('expenses')) {
                    foreach ($request->expenses as $exp) {
                        if (!empty($exp['expense_name']) && !empty($exp['amount'])) {
                            Expense::create([
                                'cash_opening_id' => $opening->id,
                                'expense_name'    => $exp['expense_name'],
                                'amount'          => (int)$exp['amount'],
                                'date'            => $closingTime,
                                'user_id'         => Auth::id(),
                            ]);
                        }
                    }
                }

                // PENTING: Hanya hitung expenses yang terikat pada shift saat ini
                // Yang benar-benar dibuat/input di sesi closing ini
                $totalExpenses = Expense::where('cash_opening_id', $opening->id)
                    ->where('date', '>=', $opening->date) // expenses hanya dari shift ini
                    ->sum('amount');
                $expectedCash  = $totalCash - $totalExpenses;

                $difference = (int)$request->actual_cash - $expectedCash;

                // Cek apakah yang menutup adalah owner dan membuka shift hari yang sama
                $isOwnerClosingSameDay = Auth::user()->isOwner() && $opening->shift_number == 1;

                CashClosing::create([
                    'user_id'         => Auth::id(),
                    'cash_opening_id' => $opening->id,
                    'opening_amount'  => $opening->opening_amount,
                    'total_sales'     => $totalSales,
                    'total_cash'      => $totalCash,
                    'total_non_cash'  => $totalNonCash,
                    'total_expenses'  => $totalExpenses,
                    'expected_cash'   => $expectedCash,
                    'actual_cash'     => (int)$request->actual_cash,
                    'difference'      => $difference,
                    'shift_number'    => $opening->shift_number,
                    'date'            => $closingTime,
                ]);

                // Set flag jika owner menutup shift 1 hari yang sama
                if ($isOwnerClosingSameDay) {
                    $opening->update(['owner_closed_same_day' => true]);
                }
            });

            return redirect()->route('kas.opening.form')
                ->with('success', 'Shift ditutup. Silakan buka shift baru.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function history(Request $request)
    {
        $query = CashClosing::with('user')->orderBy('date', 'desc');

        if ($request->filled('kasir')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->kasir . '%');
            });
        }

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $start = Carbon::parse($request->tanggal_mulai)->startOfDay();
            $end   = Carbon::parse($request->tanggal_selesai)->endOfDay();
            $query->whereBetween('date', [$start, $end]);
        }

        $closings = $query->paginate(10)->appends($request->all());

        return view('cash.history', compact('closings'));
    }

    public function show($id)
    {
        $closing = CashClosing::with('user', 'opening')->find($id);

        if (!$closing) {
            return redirect()->route('kas.history')->with('error', 'Data kasir tidak ditemukan.');
        }

        $transactions = Transaction::where('status', 'paid')
            ->whereBetween('created_at', [$closing->opening->date, $closing->date])
            ->get();

        $expenses = Expense::where('cash_opening_id', $closing->cash_opening_id)->get();

        return view('cash.show', compact('closing', 'transactions', 'expenses'));
    }

    public function print($id)
    {
        $closing  = CashClosing::with('user', 'opening')->findOrFail($id);
        $expenses = Expense::where('cash_opening_id', $closing->cash_opening_id)->get();

        return view('cash.print', compact('closing', 'expenses'));
    }

    public static function hasActiveShift()
    {
        return CashOpening::whereDoesntHave('cashClosing')->exists();
    }
}
