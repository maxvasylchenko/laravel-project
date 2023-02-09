<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryContract;
use App\Services\FileStorageService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $products = Product::with('categories')->orderByDesc('created_at')->paginate(5);

        return view ('admin/products/index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin/products/create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateProductRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateProductRequest $request, ProductRepositoryContract $repository)
    {
        return $repository->create($request) ?
            redirect()->route('admin.products.index') :
            redirect()->back()->withInput();
//        \DB::beginTransaction();
//        dd($repository->create($request));
//        \DB::beginTransaction();
//        $data = $request->validated();
//        $data = array_merge(
//            $request->validated(),
//            ['thumbnail' => FileStorageService::upload($request->file('thumbnail'))]
//        );
//        dd($data);
//        $data = $request->validated();

//        $file = FileStorageService::upload($data['thumbnail']);
//        dd($file);
//        dd($data);
//        $data = array_merge($request->validated(), ['thumbnail' => '-']);
//        $categories = $data['categories'];
//        unset($data['categories']);
//        $product = Product::create($data);
//        \DB::rollBack();
//        $product->categories()->attach($categories);
//        dd($data, $product->categories);
//       dd($request->validated());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
