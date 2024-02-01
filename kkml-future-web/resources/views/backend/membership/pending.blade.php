@extends('backend.layouts.app')

@section('content')

    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-auto">
                <h1 class="h3">{{translate('Customer Membership')}}</h1>
            </div>
            <div class="col text-right">
                <a href="{{ route('member.all') }}" class="btn btn-circle btn-info">
                    <span>{{translate('All Members')}}</span>
                </a>
                <a href="{{ route('member.reject') }}" class="btn btn-circle btn-info">
                    <span>{{translate('Reject Members')}}</span>
                </a>
            </div>
        </div>
    </div>
    <br>

    <div class="card">
        <form class="" id="sort_users" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col text-center text-md-left">
                    <h5 class="mb-md-0 h6">{{ translate('Pending Member') }}</h5>
                </div>
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
                        <th data-breakpoints="lg">{{translate('Phone Number')}}</th>
                        <th data-breakpoints="lg">{{translate('Date of Birth')}}</th>
                        <th data-breakpoints="lg">{{translate('Card Type & No.')}}</th>
                        <th data-breakpoints="lg">{{translate("Address")}}</th>
                        <th data-breakpoints="lg">{{translate("City")}}</th>
                        <th data-breakpoints="lg">{{translate('Country')}}</th>
                        <th data-breakpoints="lg">{{translate('Postal Code')}}</th>
                        <th data-breakpoints="lg">{{translate('Email')}}</th>
                        <th data-breakpoints="lg">{{translate('Emergency Contact')}}</th>
                        <th data-breakpoints="lg" class="text-right">{{translate('Options')}}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($users as $key => $user)
                    <tr>
                        <td>{{ ($key+1) + ($users->currentPage() - 1)*$users->perPage() }}</td>
                        <td>
                            <div class="row gutters-5">
                                <div class="col-auto">
                                    @if($user->avatar!='')
                                    <img src="{{ uploaded_asset($user->avatar) }}" alt="{{translate('image')}}" height="32px" width="32px">
                                    @else
                                    <span class="text-danger">No Picture</span>
                                    @endif
                                </div>
                                <div class="col">
                                    <span
                                        class="text-muted text-truncate-2">{{ $user->name }}</span>
                                </div>
                            </div>
                        </td>
                        
                        <td>
                            {{ $user->phone }} </br>
                        </td>
                        <td>
                            {{ $user->birth_date }} </br>
                        </td>
                        <td>
                            {{ $user->id_card_type }} - {{$user->id_card_num}} </br>
                        </td>
                        <td>
                            {{ $user->address }}
                        </td>
                        <td>
                            {{ $user->city }}
                        </td>
                        <td>
                            {{ $user->country }}
                        </td>
                        @if($user->postal_code != null)
                            <td>
                                {{ $user->postal_code }}
                            </td>
                        @else
                            <td></td>
                        @endif
                        <td>
                            {{ $user->email }}
                        </td>
                        <td>
                            {{ $user->emergency_contact }}
                        </td>
                        <td class="text-right">
                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                            href="{{route('member.op', ['id' => $user->id, 'parameter' => 'accept'])}}"
                                title="{{ translate('Accept') }}">
                                <i class="las la-check-circle"></i>
                            </a>
                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                               data-href="{{route('member.op', ['id' => $user->id, 'parameter' => 'reject'])}}"
                               title="{{ translate('Reject') }}">
                               <i class="las la-ban"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $users->appends(request()->input())->links() }}
            </div>
        </div>
    </div>

@endsection

@section('modal')
    @include('modals.member_pending_reject')
@endsection


@section('script')
    <script type="text/javascript">

        $(document).ready(function () {
            //$('#container').removeClass('mainnav-lg').addClass('mainnav-sm');
        });

        function sort_users(el) {
            $('#sort_users').submit();
        }

    </script>
@endsection
