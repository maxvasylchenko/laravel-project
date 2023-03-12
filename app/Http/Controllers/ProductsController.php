<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $products = Product::paginate(5);

        return view('products/index', compact('products'));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(Product $product)
    {
//        dd($product->user_rate);
        return view('products/show', compact('product'));
    }

    public function rate(Request $request, Product $product)
    {
//        dd($request->get('star'));
        $product->rateOnce($request->get('star'));

        return redirect()->back();
    }
}
