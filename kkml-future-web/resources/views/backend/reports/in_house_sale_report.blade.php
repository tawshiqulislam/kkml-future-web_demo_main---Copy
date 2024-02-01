@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class=" align-items-center">
       <h1 class="h3">{{translate('Inhouse Product sale report')}}</h1>
	</div>
</div>

<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('in_house_sale_report.index') }}" method="GET">
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label mb-2">{{translate('Sort by Category')}} :</label>
                        <div class="col-md-6 mb-2">
                            <select id="demo-ease" class="aiz-selectpicker" name="category_id" required>
                                @foreach (\App\Category::all() as $key => $category)
                                    <option value="{{ $category->id }}" @if($category->id == $sort_by) selected @endif >{{ $category->getTranslation('name') }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <button class="btn btn-primary" type="submit">{{ translate('Filter') }}</button>
                        </div>
                    </div>
                </form>

                <table class="table table-bordered aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ translate('Product Name') }}</th>
                            <th>{{ translate('Num of Sale') }}</th>
                            <th>{{ translate('Price') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $key => $product)
                            <tr>
                                <td>{{ ($key+1) + ($products->currentPage() - 1)*$products->perPage() }}</td>
                                <td>{{ $product->getTranslation('name') }}</td>
                                <td>{{ $product->num_of_sale }}</td>
                                <td>{{ $product->unit_price }}<small> &#2547; &nbsp;/{{ $product->unit }}</small><br>{{ $product->purchase_price }}<small> &#2547; &nbsp;/{{ $product->unit }} <b>(Discount price)</b></small></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination mt-4">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
