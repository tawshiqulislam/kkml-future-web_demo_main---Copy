@extends('backend.layouts.app')

@section('content')

    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-auto">
                <h1 class="h3">{{translate('All Services')}}</h1>
            </div>
            @if($type != 'Seller')
                <div class="col text-right">
                    <a href="{{ route('service.create') }}" class="btn btn-circle btn-info">
                        <span>{{translate('Add New Service')}}</span>
                    </a>
                </div>
            @endif
        </div>
    </div>
    <br>

    <div class="card">
        <form class="" id="sort_products" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col text-center text-md-left">
                    <h5 class="mb-md-0 h6">{{ translate('All Service') }}</h5>
                </div>
                @if($type == 'Seller')
                    <div class="col-md-2 ml-auto">
                        <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" id="user_id"
                                name="user_id" onchange="sort_products()">
                            <option value="">{{ translate('All Sellers') }}</option>
                            @foreach (App\Seller::all() as $key => $seller)
                                @if ($seller->user != null && $seller->user->shop != null)
                                    <option value="{{ $seller->user->id }}"
                                            @if ($seller->user->id == $seller_id) selected @endif>{{ $seller->user->shop->name }}
                                        ({{ $seller->user->name }})
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                @endif
                @if($type == 'All')
                    <div class="col-md-2 ml-auto">
                        <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" id="user_id"
                                name="user_id" onchange="sort_products()">
                            <option value="">{{ translate('All Sellers') }}</option>
                            @foreach (App\User::where('user_type', '=', 'admin')->orWhere('user_type', '=', 'seller')->get() as $key => $seller)
                                <option value="{{ $seller->id }}"
                                        @if ($seller->id == $seller_id) selected @endif>{{ $seller->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="col-md-2">
                    <div class="form-group mb-0">
                        <input type="text" class="form-control form-control-sm" id="search" name="search"
                               @isset($sort_search) value="{{ $sort_search }}"
                               @endisset placeholder="{{ translate('Type & Enter') }}">
                    </div>
                </div>
            </div>
        </form>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                <tr>
                    <th data-breakpoints="lg">#</th>
                    <th width="30%">{{translate('Name')}}</th>
                    @if($type == 'Seller' || $type == 'All')
                        <th data-breakpoints="lg">{{translate('Added By')}}</th>
                    @endif
                    <th data-breakpoints="lg">{{translate('Description')}}</th>
                    <th data-breakpoints="lg">{{translate('Service Category')}}</th>
                    <th data-breakpoints="lg">{{translate('Contact Info')}}</th>
                    <th data-breakpoints="lg">{{translate('Published')}}</th>
                    <th data-breakpoints="lg" class="text-right">{{translate('Options')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($services as $key => $service)
                    <tr>
                        <td>{{ ($key+1) + ($services->currentPage() - 1)*$services->perPage() }}</td>
                        <td>
                            <div class="row gutters-5">
                                <div class="col-auto">
                                    @if($service->thumbnail_img!='')
                                    <img src="{{ uploaded_asset($service->thumbnail_img) }}" alt="{{translate('image')}}" height="32px" width="32px">
                                    @else
                                    <span class="text-danger">No Previous Image</span>
                                    @endif
                                </div>
                                <div class="col">
                                    <span
                                        class="text-muted text-truncate-2">{{ $service->name }}</span>
                                </div>
                            </div>
                        </td>
                        @if($type == 'Seller' || $type == 'All')
                            {{-- <td>{{ $service->user->id }}</td> --}}
                            <td>{{ $service->user->name }}</td>
                        @endif
                        <td>
                            {{ $service->description }} </br>
                        </td>
                        <td>
                            {{-- {{ $service->service_category->name }} --}}
                            {{ (App\ServiceCategory::where('id',$service->category_id)->first())->name }}
                        </td>
                        <td>
                            {{ $service->address }}
                        </td>
                        <td>
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input onchange="update_published(this)" value="{{ $service->id }}"
                                       type="checkbox" <?php if ($service->published == 1) echo "checked"; ?> >
                                <span class="slider round"></span>
                            </label>
                        </td>
                        <td class="text-right">
                            <a class="btn btn-soft-success btn-icon btn-circle btn-sm"
                               href="{{route('service.view', $service->id)}}" target="_blank"
                               title="{{ translate('View') }}">
                                <i class="las la-eye"></i>
                            </a>
                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                            href="{{route('service.edit', ['id'=>$service->id])}}"
                                title="{{ translate('Edit') }}">
                                <i class="las la-edit"></i>
                            </a>
                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                               data-href="{{route('service.destroy',['id'=>$service->id])}}"
                               title="{{ translate('Delete') }}">
                                <i class="las la-trash"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $services->appends(request()->input())->links() }}
            </div>
        </div>
    </div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection


@section('script')
    <script type="text/javascript">

        $(document).ready(function () {
            //$('#container').removeClass('mainnav-lg').addClass('mainnav-sm');
        });

        function update_published(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('service.published') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function (data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', '{{ translate('Published service updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        function sort_products(el) {
            $('#sort_products').submit();
        }

    </script>
@endsection
