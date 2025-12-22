<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\CashOpening;
use App\Models\OpenBill;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    // =========================
    // HALAMAN TRANSAKSI UTAMA
    // =========================
    public function index()
    {
        $products = Product::orderBy('name', 'asc')->get();

        return view('transactions.index', [
            'products'      => $products,
            'cart'          => Session::get('cart', []),
            'cartDiscount'  => Session::get('cart_discount', ['value' => 0, 'type' => 'nominal']),
            'cartOrderInfo' => Session::get('cart_order_info', ['customer_name' => '', 'order_type' => 'dine_in']),
            'openBills'     => Session::get('open_bills', [])
        ]);
    }

    // =========================
    // TAMBAH PRODUK KE KERANJANG
    // =========================
    public function addToCart(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = Session::get('cart', []);

        // Cek stok
        if ($product->has_stock && $product->stock <= 0) {
            return response()->json(['success' => false, 'message' => 'Stok habis!'], 400);
        }

        if (isset($cart[$id])) {
            if ($product->has_stock && $cart[$id]['quantity'] >= $product->stock) {
                return response()->json(['success' => false, 'message' => 'Stok tidak mencukupi!'], 400);
            }
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'name'          => $product->name,
                'price'         => (float) $product->price,
                'quantity'      => 1,
                'note'          => '',
                'discount'      => 0,
                'discount_type' => 'nominal'
            ];
        }

        Session::put('cart', $cart);

        $rawTotal = $this->calculateTotal($cart);

        return response()->json([
            'success'   => true,
            'cart'      => $cart,
            'total'     => $this->formatMoney($rawTotal),
            'raw_total' => $rawTotal
        ]);
    }

    // =========================
    // HAPUS PRODUK DARI KERANJANG
    // =========================
    public function removeFromCart(Request $request, $id)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
        }

        empty($cart) ? Session::forget('cart') : Session::put('cart', $cart);

        $total = $this->calculateTotal($cart);

        return response()->json([
            'success'   => true,
            'cart'      => $cart,
            'total'     => $this->formatMoney($total),
            'raw_total' => $total
        ]);
    }

    // =========================
    // UPDATE KERANJANG
    // =========================
    public function updateCart(Request $request)
    {
        $cart = Session::get('cart', []);

        // Handle sync action (for clearing/syncing entire cart)
        if ($request->action === 'sync') {
            // Jika ada cart baru, gunakan itu; kalau tidak, gunakan empty array (clear)
            $newCart = $request->cart ?? [];
            $newCart = is_array($newCart) ? $newCart : [];

            if (empty($newCart)) {
                Session::forget(['cart', 'cart_discount', 'cart_order_info']);
                $cart = [];
            } else {
                Session::put('cart', $newCart);
                $cart = $newCart;
            }

            $rawTotal = $this->calculateTotal($cart);
            return response()->json([
                'success'   => true,
                'cart'      => $cart,
                'total'     => $this->formatMoney($rawTotal),
                'raw_total' => $rawTotal,
            ]);
        }

        // Jika tidak ada id item, anggap ini update global (diskon/order info)
        if (!$request->id) {
            // Update diskon keranjang jika dikirim
            if ($request->filled('discount_value')) {
                Session::put('cart_discount', [
                    'value' => max(0, (float)$request->discount_value),
                    'type'  => in_array($request->discount_type, ['percent', 'nominal']) ? $request->discount_type : 'nominal'
                ]);
            }

            // Update order info jika dikirim
            if ($request->filled('customer_name') || $request->filled('order_type')) {
                $currentInfo = Session::get('cart_order_info', ['customer_name' => '', 'order_type' => 'dine_in']);
                Session::put('cart_order_info', [
                    'customer_name' => $request->customer_name ?? $currentInfo['customer_name'] ?? '',
                    'order_type'    => in_array($request->order_type, ['dine_in', 'take_away']) ? $request->order_type : ($currentInfo['order_type'] ?? 'dine_in'),
                ]);
            }

            $rawTotal = $this->calculateTotal($cart);
            return response()->json([
                'success'   => true,
                'cart'      => $cart,
                'total'     => $this->formatMoney($rawTotal),
                'raw_total' => $rawTotal,
            ]);
        }

        if (!isset($cart[$request->id])) {
            return response()->json(['success' => false, 'message' => 'Item tidak ditemukan!'], 400);
        }

        $id = $request->id;
        $product = Product::find($id);

        if ($product && $product->has_stock && $request->quantity > $product->stock) {
            return response()->json(['success' => false, 'message' => 'Stok tidak mencukupi!'], 400);
        }

        // Update item
        $cart[$id]['quantity']      = max(1, (int)$request->quantity);
        $cart[$id]['note']          = $request->note ?? $cart[$id]['note'];
        $cart[$id]['discount']      = max(0, (float)$request->discount);
        $cart[$id]['discount_type'] = in_array($request->discount_type, ['percent', 'nominal'])
            ? $request->discount_type
            : 'nominal';

        Session::put('cart', $cart);

        // Update diskon keranjang
        if ($request->filled('discount_value')) {
            Session::put('cart_discount', [
                'value' => max(0, (float)$request->discount_value),
                'type'  => in_array($request->discount_type, ['percent', 'nominal']) ? $request->discount_type : 'nominal'
            ]);
        }

        // Update order info
        if ($request->filled('customer_name')) {
            Session::put('cart_order_info', [
                'customer_name' => $request->customer_name,
                'order_type'    => $request->order_type ?? 'dine_in'
            ]);
        }

        $rawTotal = $this->calculateTotal($cart);
        $itemSubtotal = $this->calculateItemSubtotal($cart[$id]);

        return response()->json([
            'success'   => true,
            'cart'      => $cart,
            'total'     => $this->formatMoney($rawTotal),
            'raw_total' => $rawTotal,
            'itemSubtotal' => $this->formatMoney($itemSubtotal)
        ]);
    }

    // =========================
    // HITUNG SUBTOTAL ITEM (setelah diskon item)
    // =========================
    private function calculateItemSubtotal($item)
    {
        $price = (float) $item['price'];
        $qty = (int) $item['quantity'];
        $subtotal = $price * $qty;

        if (!empty($item['discount']) && $item['discount'] > 0) {
            if ($item['discount_type'] === 'percent') {
                $subtotal -= $subtotal * ($item['discount'] / 100);
            } else {
                // diskon nominal per item dianggap per item (bukan per qty)
                // jika intentnya diskon nominal per unit, sesuaikan di sini.
                $subtotal -= $item['discount'];
            }
        }

        return max(0, round($subtotal, 2));
    }

    // =========================
    // HITUNG TOTAL KERANJANG (mengaplikasikan diskon global)
    // =========================
    private function calculateTotal($cart = null)
    {
        $cart = $cart ?? Session::get('cart', []);
        $discount = Session::get('cart_discount', ['value' => 0, 'type' => 'nominal']);

        // subtotal setelah diskon per-item
        $baseSubtotals = collect($cart)->map(fn($i) => $this->calculateItemSubtotal($i));
        $subtotal = $baseSubtotals->sum();
        $total = $subtotal;

        if (!empty($discount) && ($discount['value'] ?? 0) > 0) {
            $val = (float)$discount['value'];
            if ($discount['type'] === 'percent') {
                $total = $subtotal * (1 - $val / 100);
            } else {
                // nominal: kurangi proporsional sesuai subtotal item
                $total = $subtotal;
                $total -= min($subtotal, $val); // pastikan tidak negatif
            }
        }

        return max(0, round($total, 2));
    }

    private function formatMoney($amount)
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    // =========================
    // CHECKOUT
    // =========================
    public function checkout(Request $request)
    {
        // Pastikan ada kas awal (shift aktif)
        $cash = CashOpening::whereDoesntHave('cashClosing')->latest('date')->first();
        if (!$cash) {
            return response()->json(['success' => false, 'message' => 'Belum ada kas awal! Silakan buka kas terlebih dahulu.'], 422);
        }

        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'Keranjang kosong!'], 422);
        }

        $discount = Session::get('cart_discount', ['value' => 0, 'type' => 'nominal']);
        $orderInfo = Session::get('cart_order_info', ['customer_name' => '', 'order_type' => 'dine_in']);
        $paymentMethod = $request->payment_method ?? 'cash';

        return DB::transaction(function () use ($cart, $discount, $orderInfo, $paymentMethod) {
            // 1) Hitung subtotal per item (setelah diskon item), lalu hitung pembagian diskon global
            $items = [];
            $totalBase = 0.0;

            foreach ($cart as $productId => $item) {
                $baseSubtotal = $this->calculateItemSubtotal($item); // sudah memperhitungkan diskon per item
                $items[$productId] = [
                    'data' => $item,
                    'base_subtotal' => $baseSubtotal
                ];
                $totalBase += $baseSubtotal;
            }

            // 2) Terapkan diskon global ke setiap item (proporsional untuk nominal)
            $finalTotal = 0.0;
            $globalVal = (float) ($discount['value'] ?? 0);
            $globalType = $discount['type'] ?? 'nominal';

            foreach ($items as $productId => &$it) {
                $base = $it['base_subtotal'];
                $final = $base;

                if ($globalVal > 0 && $totalBase > 0) {
                    if ($globalType === 'percent') {
                        $final = $base * (1 - $globalVal / 100);
                    } else {
                        // nominal -> proporsional
                        $portion = $base / $totalBase;
                        $deduct = $portion * $globalVal;
                        $final = max(0, $base - $deduct);
                    }
                }

                // round per item final
                $final = round($final, 2);
                $it['final_subtotal'] = $final;
                $finalTotal += $final;
            }
            unset($it);

            // Guard: jika rounding menyebabkan perbedaan kecil, adjust last item
            $finalTotal = round($finalTotal, 2);

            // 3) Simpan Transaction dengan total = finalTotal
            $transaction = Transaction::create([
                'code'           => uniqid(),
                'total'          => $finalTotal,
                'customer_name'  => $orderInfo['customer_name'],
                'order_type'     => $orderInfo['order_type'],
                'payment_method' => $paymentMethod,
                'status'         => 'paid',
                'discount_value' => $discount['value'] ?? 0,
                'discount_type'  => $discount['type'] ?? 'nominal',
                'user_id'        => Auth::id(),
            ]);

            // 4) Simpan tiap TransactionItem (subtotal = final subtotal setelah semua diskon)
            foreach ($items as $productId => $it) {
                $product = Product::find($productId);
                if (!$product) continue;

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $productId,
                    'quantity'       => $it['data']['quantity'],
                    'price'          => $it['data']['price'],
                    // Simpan subtotal sebagai nilai FINAL (setelah diskon item + diskon global)
                    'subtotal'       => $it['final_subtotal'],
                    'note'           => $it['data']['note'] ?? null,
                    'discount'       => $it['data']['discount'] ?? 0,
                    'discount_type'  => $it['data']['discount_type'] ?? 'nominal',
                ]);

                if ($product->has_stock) {
                    $product->decrement('stock', $it['data']['quantity']);
                }
            }

            // 5) Jika ada open bill aktif, hapus dari DB (selesai)
            $openBillId = Session::pull('open_bill_id');
            if ($openBillId) {
                OpenBill::where('id', $openBillId)->where('user_id', Auth::id())->delete();
            }

            // 6) Bersihkan session cart
            Session::forget(['cart', 'cart_discount', 'cart_order_info']);

            return response()->json([
                'success'         => true,
                'transaction_id'  => $transaction->id,
                'total'           => $finalTotal,
                'formatted_total' => $this->formatMoney($finalTotal),
                'payment_method'  => $paymentMethod
            ]);
        });
    }

    // =========================
    // CANCEL TRANSAKSI
    // =========================
    public function cancelTransaction(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:255']);

        $transaction = Transaction::with('items.product')->findOrFail($id);

        if ($transaction->status === 'cancel')
            return back()->with('error', 'Transaksi sudah dibatalkan.');

        if ($transaction->created_at->toDateString() !== now()->toDateString())
            return back()->with('error', 'Hanya transaksi di hari yang sama yang dapat dibatalkan.');

        DB::transaction(function () use ($transaction, $request) {
            foreach ($transaction->items as $item) {
                $product = $item->product;
                if ($product && $product->has_stock) {
                    $product->increment('stock', $item->quantity);
                }
            }

            $transaction->update([
                'status'        => 'cancel',
                'cancel_reason' => $request->reason,
                'cancelled_at'  => now(),
            ]);
        });

        return redirect()->route('riwayat.index')->with('success', 'Transaksi dibatalkan & stok dikembalikan.');
    }

    // =========================
    // HISTORY
    // =========================
    public function history(Request $request)
    {
        $query = Transaction::with(['items.product', 'user'])
            ->orderBy('created_at', 'desc');

        if ($request->tanggal_awal && $request->tanggal_akhir) {
            $query->whereBetween('created_at', [
                $request->tanggal_awal . ' 00:00:00',
                $request->tanggal_akhir . ' 23:59:59'
            ]);
        }

        if ($request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->kasir) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%$request->kasir%"));
        }

        $transactions = $query->get();

        $riwayatPerHari = $transactions->groupBy(fn($trx) => $trx->created_at->format('Y-m-d'))
            ->map(function ($hari, $tanggal) {
                return [
                    'tanggal'      => Carbon::parse($tanggal)->translatedFormat('l, d F Y'),
                    'total_harian' => $hari->where('status', '!=', 'cancel')->sum('total'),
                    'transaksis'   => $hari
                ];
            });

        return view('transactions.history', compact('riwayatPerHari'));
    }

    // =========================
    // DETAIL TRANSAKSI
    // =========================
    public function show($id)
    {
        $transaction = Transaction::with(['items.product', 'user'])
            ->findOrFail($id);

        return view('transactions.show', compact('transaction'));
    }

    // =========================
    // OPEN BILL: Simpan ke DB
    // =========================
    public function saveOpenBill(Request $request)
    {
        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'Keranjang kosong!'], 422);
        }

        $discount = [
            'value' => max(0, (float)$request->discount_value),
            'type'  => in_array($request->discount_type, ['percent', 'nominal']) ? $request->discount_type : 'nominal'
        ];

        $total = $this->calculateTotal($cart);

        $bill = OpenBill::create([
            'bill_code'      => uniqid('BILL'),
            'cart'           => $cart,
            'customer_name'  => $request->customer_name ?? '',
            'order_type'     => in_array($request->order_type, ['dine_in', 'take_away']) ? $request->order_type : 'dine_in',
            'discount_value' => $discount['value'],
            'discount_type'  => $discount['type'],
            'total'          => $total,
            'user_id'        => Auth::id(),
        ]);

        // Bersihkan keranjang setelah disimpan sebagai Open Bill
        Session::forget(['cart', 'cart_discount', 'cart_order_info', 'open_bill_id']);

        return response()->json(['success' => true, 'bill' => $bill]);
    }

    // =========================
    // OPEN BILL: Get / Load / Delete
    // =========================
    public function getOpenBills()
    {
        $bills = OpenBill::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($bill) {
                $bill->formatted_date = $bill->created_at ? $bill->created_at->format('d M Y H:i') : '';
                return $bill;
            });

        return response()->json($bills);
    }

    // Halaman Open Bill
    public function openBillPage()
    {
        // Tampilkan semua Open Bill untuk semua user (data global/shared)
        $bills = OpenBill::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('transactions.open_bills', compact('bills'));
    }

    public function loadOpenBill($id)
    {
        // Izinkan semua user untuk load Open Bill (data global)
        $bill = OpenBill::find($id);

        if (!$bill) {
            return response()->json(['success' => false, 'message' => 'Open Bill tidak ditemukan'], 404);
        }

        // Restore cart ke session
        Session::put('cart', $bill->cart);
        Session::put('cart_discount', [
            'value' => $bill->discount_value,
            'type'  => $bill->discount_type
        ]);
        Session::put('cart_order_info', [
            'customer_name' => $bill->customer_name,
            'order_type'    => $bill->order_type
        ]);
        // Tandai open bill aktif di session, tidak dihapus saat load
        Session::put('open_bill_id', $bill->id);

        return response()->json(['success' => true, 'bill' => $bill]);
    }

    public function deleteOpenBill($id)
    {
        // Izinkan semua user untuk delete Open Bill (data global)
        $bill = OpenBill::find($id);

        if ($bill) {
            $bill->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }
}
