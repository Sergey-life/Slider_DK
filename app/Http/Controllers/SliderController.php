<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    protected $productsImage = [];

    public function index()
    {
        $categories = Category::all();
        $products = $categories->first()->products;

        return view('welcome', [
            'categories' => $categories,
            'products' => $products
            ]);
    }

    public function show(Request $request)
    {
        $category = Category::find($request->id);
        $html = [];
        foreach ($category->products as $product) {
            $html[] = '<div class="swiper-slide"><div class="swiper__container">'."<img src=".$product->image.">"."</div></div>";
        }

        return response()->json($html);
    }
}
