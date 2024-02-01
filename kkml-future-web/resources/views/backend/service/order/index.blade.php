@extends('backend.layouts.app')

@section('content')
<div class="card">
    <form class="" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col text-center text-md-left">
                <h5 class="mb-md-0 h6">{{ translate('All Service Orders') }}</h5>
            </div>
            <div class="col-lg-2">
                <div class="form-group mb-0">
                    <input type="text" class="aiz-date-range form-control" value="{{ $date }}" name="date" placeholder="{{ translate('Filter by date') }}" data-format="DD-MM-Y" data-separator=" to " data-advanced-range="true" autocomplete="off">
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group mb-0">
                    <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type Order code & hit Enter') }}">
                </div>
            </div>
            <div class="col-auto">
                <div class="form-group mb-0">
                    <button type="submit" class="btn btn-primary">{{ translate('Filter') }}</button>
                </div>
            </div>
        </div>
    </form>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ translate('Order Code') }}</th>
                    <th data-breakpoints="md">{{ translate('Name Of Service') }}</th>
                    <th data-breakpoints="md">{{ translate('User') }}</th>
                    <th data-breakpoints="md">{{ translate('Name') }}</th>
                    <th data-breakpoints="md">{{ translate('Phone') }}</th>
                    <th data-breakpoints="md">{{ translate('Email') }}</th>
                    <th data-breakpoints="md">{{ translate('Status') }}</th>
                    <th class="text-right" width="15%">{{translate('options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $key => $order)
                <tr>
                    <td>
                        {{ ($key+1) + ($orders->currentPage() - 1)*$orders->perPage() }}
                    </td>
                    <td>
                        {{ $order->code }}
                    </td>
                    <td>
                        {{(App\Service::where('id', $order->service_id)->first())->name}}
                    </td>
                    <td>
                        {{$order->user_info}}
                    </td>
                    <td>
                        {{$order->name}}
                    </td>
                    <td>
                        {{$order->phone}}
                    </td>
                    <td>
                        {{$order->email}}
                    </td>
                    <td>
                        {{$order->status}}
                    </td>
                    <td class="text-right">
                        @if($order->status == 'New')
                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('service_order.accept', $order->id)}}" title="{{ translate('Complete') }}">
                            <i class="las la-check-circle"></i>
                        </a>
                        <a class="btn btn-soft-danger btn-icon btn-circle btn-sm" href="{{route('service_order.reject', $order->id)}}" title="{{ translate('Reject') }}">
                            <i class="las la-ban"></i>
                        </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="aiz-pagination">
            {{ $orders->appends(request()->input())->links() }}
        </div>
        
    </div>
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script type="text/javascript">

    </script>
@endsection
