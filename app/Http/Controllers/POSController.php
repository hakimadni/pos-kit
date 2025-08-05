<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\MenuSet;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class POSController extends Controller
{
    public function index(Request $request)
    {
        $selectedMenuSetId = $request->query('menu_set_id');

        $sesionMenuset = session('menu_set_id');
        //Kalo kosong
        if (!$sesionMenuset) {
            $sesionMenuset = Session::put('menu_set_id', 'All');
        }
        //Kalo ada ganti menu
        if ($selectedMenuSetId) {
            $sesionMenuset = Session::put('menu_set_id', $selectedMenuSetId);
        }

        //Ambil dari session lagi
        $sesionMenuset = session('menu_set_id');

        $productsQuery = Product::with('category');
        if ($sesionMenuset == 'All') {
            $productsQuery;
        } else {
            $productsQuery->where('set_id', $sesionMenuset);
        }

        $products = $productsQuery->get();
        $cart = Cart::where('user_id', Auth::id())->with('product')->get();

        $sets = MenuSet::orderBy('name')->get(['id', 'name']);

        return Inertia::render('POS', [
            'sets' => $sets,
            'products' => $products,
            'cart' => $cart,
            'currentSetId' => $sesionMenuset,
        ]);
    }

    public function checkout()
    {
        $user_id = Auth::id();
        $cart = \App\Models\Cart::where('user_id', $user_id)->get();

        if ($cart->isEmpty()) {
            return back()->with('error', 'Your cart is empty.');
        }

        $total = $cart->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        try {
            DB::transaction(function () use ($user_id, $cart, $total) {
                $transaction = Transaction::create([
                    'user_id' => $user_id,
                    'gobiz_transaction_id' => null,
                    'total_amount' => $total,
                    'status' => 'paid',
                ]);

                foreach ($cart as $item) {
                    Order::create([
                        'user_id' => $user_id,
                        'transaction_id' => $transaction->id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                    ]);
                }
            });
            \App\Models\Cart::where('user_id', $user_id)->delete();
        } catch (\Exception $e) {
            return back()->with('error', 'Checkout failed: ' . $e->getMessage());
        }
        return back()->with('message', 'Checkout successful! Total: ' . $total);
    }
}
