<x-layout>
    <div class="category-option">
        <select>
            @foreach($categories as $category)
                <option value="{{$category->id}}" data-url="{{route('category.show', $category->id)}}" id="category_{{$category->id}}">{{$category->title}}</option>
            @endforeach
        </select>
    </div>
    <div
        style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff"
        class="swiper gallery-top"
    >
        <div class="swiper-wrapper">
            @foreach($products as $product)
                <div class="swiper-slide">
                    <div class="swiper__container" data-count="{{count($products)}}">
                        <img src="{{$product->image}}" />
                    </div>
                </div>
            @endforeach
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
    <div thumbsSlider="" class="swiper gallery-thumbs">
        <div class="swiper-wrapper">
            @foreach($products as $product)
                <div class="swiper-slide">
                    <div class="swiper__container">
                        <img src="{{$product->image}}" />
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
    <script src="{{url('js/category.js')}}"></script>
</x-layout>
