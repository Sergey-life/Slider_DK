<x-layout>
    <div class="antialiased">
        <div class="container">
            <!-- main app container -->
            <div class="readersack">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>SliderDK</h3>

                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#addupdatepopup">
                                Додати товар
                            </button>
                            <div id="search">
                                <form id="searchform" name="searchform">
                                    <div class="form-group">
                                        <label>Пошук по назві</label>
                                        <input type="text" name="title" value="{{request()->get('title', '')}}" class="form-control" />
                                        @csrf
                                    </div>
                                    <div class="form-group">
                                        <label>Пошук по коду</label>
                                        <input type="text" name="product_code" value="{{request()->get('product_code', '')}}" class="form-control" />
                                    </div>
                                    <a href="{{url('products')}}" class="btn btn-success" id="search_btn">Пошук</a>
                                </form>
                            </div>
                            <div id="pagination_data">
                                @include('products.product-pagination', ['products' => $products])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- credits -->
            <div class="text-center">
                <p>
                    <a href="#" target="_top">SliderDK</a>
                </p>
            </div>
        </div>
        <div class="modal" id="addupdatepopup" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{url('products')}}" class="formsubmit" method="POST" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title">Товар</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @csrf
                            <div class="row">
                                <input type="hidden" placeholder="id" value="" name="id">
                                <div class="col-12">
                                    <label>Назва</label>
                                    <input type="text" placeholder="Назва" value="{{old('title')}}" name="title" class="form-control">
                                </div>
                                <div class="col-12">
                                    <label>Код товару</label>
                                    <input type="text" placeholder="Код товару" value="{{old('product_code')}}" name="product_code" class="form-control">
                                </div>
                                <div class="col-12">
                                    <fieldset>
                                        <legend class="mb-2">Категорія</legend>
                                        @foreach($categories as $category)
                                            <input type="checkbox" name="category[]" value="{{$category->id}}">
                                            <label for="category">{{$category->title}}</label>
                                        @endforeach
                                    </fieldset>
                                </div>
                                <div class="col-12">
                                    <label>Зображення</label>
                                    <div>
                                        <input type="file" placeholder="Зображення" name="image" id="imgInp">
                                        <img style="visibility: hidden" id="prview" width="100" height="100">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Зберегти</button>
                            <button type="button" class="btn btn-secondary">Закрити</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
