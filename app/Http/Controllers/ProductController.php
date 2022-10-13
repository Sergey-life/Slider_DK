<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    private $file;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::when($request->has('title'), fn ($q) =>
            $q->where('title', 'like', '%' . $request->get('title') . '%')
        )
        ->orderBy('id', 'DESC')
        ->paginate(5);

        if ($request->ajax()) {
            return view('products.product-pagination', ['products' => $products]);
        }

        $categories = Category::all();

        return view('products.product', [
            'products' => $products,
            'categories' => $categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validations = [
            'title' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'category' => 'required'
        ];

        if (!$request->hasFile('image')) {
                $validations['image'] = '';
            }

        $validator = Validator::make($request->all(), $validations);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //  save image and name in database
        if ($request->id == '') {
            $product = new Product();
        }
        else
        {
            $product = Product::find($request->id);
        }
        $product->title = $request->title;
        if ($request->hasFile('image')) {
            $this->file = $request->file('image')->store('uploads/images');
            $product->image = $this->file;
        }
        $product->product_code = $request->product_code;
        $product->save();

        $category = Category::find($request->category);
        $product->categories()->sync($category);

        $resp = $request->id ? 'Ви успішно оновили товар!' : 'Ви успішно створили товар!';

        return response()->json(['status' => true, 'message' => $resp]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        Product::find($id)->delete();
        return response()->json(['status' => true, 'message' => 'Товар успішно видалено!']);
    }
}
