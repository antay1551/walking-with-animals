<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $products = Product::all();

        return view('product.all', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Product $product
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function buy(Product $product)
    {
        return view('product.buy', compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirm(Request $request)
    {
        $product = Product::query()->findOrFail($request->get('product_id'));

        $user = User::query()->firstOrCreate([
            'email' => $request->get('email')
        ], [
            'first_name' => $request->get('name'),
            'last_name' => $request->get('name'),
            'password' => null,
            'address' => $request->get('address'),
        ]);

        auth()->login($user);

        $user->orders()->create([
            'product_id' => $product->id,
            'price' => $product->price,
        ]);

        return redirect()->route('checkout');
    }

    public function checkout()
    {
        $order = Order::query()
            ->with('product')
            ->whereNull('payed_at')
            ->latest()
            ->firstOrFail();

        $paymentIntent = auth()->user()->createSetupIntent();

        return view('product.checkout', compact('order', 'paymentIntent'));
    }

    public function pay(Request $request)
    {
        $user = auth()->user();
        $paymentMethod = $request->get('payment_method');
        $order = Order::query()->where('user_id', $user->id)->findOrFail($request->get('order_id'));

        try {
            $user->createOrGetStripeCustomer();
            $user->updateDefaultPaymentMethod($paymentMethod);
            $user->charge($order->price, $paymentMethod);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('success');
    }
}
