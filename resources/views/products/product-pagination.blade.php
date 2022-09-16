<table class="table table-striped table-dark table-bordered">
    <tr>
        <th>#</th>
        <th>Назва</th>
        <th>Код товару</th>
        <th>Зображення</th>
        <th>Дата</th>
        <th>Дії</th>
    </tr>
    @foreach($products as $product)
        <tr>
            <td>{{$product->id}}</td>
            <td>{{$product->title}}</td>
            <td>{{$product->product_code}}</td>
            <td><img src="{{$product->image}}" width="100"></td>
            <td>{{$product->created_at}}</td>
            <td>
                <a href="{{route('products.update', $product->id)}}" data-json="{{$product}}" data-categories="{{$product->categories}}" class="btn btn-block edit-product btn btn-success btn-sm mb-2">Редагувати</a>
                <form action="{{route('products.destroy', $product->id)}}" class="formsubmit" method="Post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-block btn btn-danger btn-sm">Видалити</button>
                </form>
            </td>
        </tr>
    @endforeach
</table>
<div id="pagination">
    {{$products->links()}}
</div>
