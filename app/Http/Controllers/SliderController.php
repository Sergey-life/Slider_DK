<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    protected $html = [];

    public function index()
    {
        $categories = Category::all();
        $products = $categories->first()->products()->orderBy('id', 'DESC')->get();

        return view('welcome', [
            'categories' => $categories,
            'products' => $products
            ]);
    }

    public function show(Request $request)
    {
        $products = Category::find($request->id)->products()->orderBy('id', 'DESC')->get();
        foreach ($products as $product) {
            $this->html[] = '<div class="swiper-slide"><div class="swiper__container" data-count='.count($products).'>'."<img src=".$product->image.">"."</div></div>";
        }

        return response()->json($this->html);
    }
}
