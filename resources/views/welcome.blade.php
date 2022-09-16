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
        class="swiper mySwiper2"
    >
        <div class="swiper-wrapper">
            @foreach($products as $product)
                <div class="swiper-slide">
                    <div class="swiper__container">
                        <img src="{{$product->image}}" />
                    </div>
                </div>
            @endforeach
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
    <div thumbsSlider="" class="swiper mySwiper">
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

    <!-- Initialize Swiper -->
{{--    <script>--}}
{{--        var swiper = new Swiper(".mySwiper", {--}}
{{--            spaceBetween: 10,--}}
{{--            slidesPerView: 4,--}}
{{--            freeMode: true,--}}
{{--            watchSlidesProgress: true,--}}
{{--        });--}}
{{--        var swiper2 = new Swiper(".mySwiper2", {--}}
{{--            spaceBetween: 10,--}}
{{--            navigation: {--}}
{{--                nextEl: ".swiper-button-next",--}}
{{--                prevEl: ".swiper-button-prev",--}}
{{--            },--}}
{{--            thumbs: {--}}
{{--                swiper: swiper,--}}
{{--            },--}}
{{--        });--}}
{{--    </script>--}}
</x-layout>
