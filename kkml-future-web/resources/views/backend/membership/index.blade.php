@extends('backend.layouts.app')

@section('content')

    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-auto">
                <h1 class="h3">{{translate('All Membership')}}</h1>
            </div>
            <div class="col text-right">
                <a href="{{ route('member.pending') }}" class="btn btn-circle btn-info">
                    <span>{{translate('Pending Members')}}</span>
                </a>
                <a href="{{ route('member.reject') }}" class="btn btn-circle btn-info">
                    <span>{{translate('Reject Members')}}</span>
                </a>
            </div>
        </div>
    </div>
    <br>
    @if(Session::has('success'))
    <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ Session::get('success') }}</strong>
    </div>
@elseif(Session::has('error'))
    <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ Session::get('error') }}</strong>
    </div>
@endif
    <div class="card">
        <form class="" id="sort_users" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col text-center text-md-left">
                    <h5 class="mb-md-0 h6">{{ translate('All Members') }}</h5>
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
                        <th data-breakpoints="lg">{{translate('Referral Code')}}</th>
                        <th data-breakpoints="lg">{{translate('Total Referral User')}}</th>
                        <th data-breakpoints="lg">{{translate('Balance')}}</th>
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
                    @php
                        echo $users;
                    @endphp
                @foreach($users as $key => $user)
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
                    {{$user->referral_code}} </br>
                </td>
                <td>
                    {{$user->where('referred_by', $user->id)->count()}} </br>
                </td>
                <td>
                    {{ $user->balance }} </br>
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
                        {{ $user->address }}
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
                    href="{{route('member.op', ['id' => $user->id, 'parameter' => 'pending'])}}"
                        title="{{ translate('Make User Pending') }}">
                        <i class="las la-pause-circle"></i>
                    </a>
                    <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                       data-href="{{route('member.op', ['id' => $user->id, 'parameter' => 'delete'])}}"
                       title="{{ translate('Delete Membership') }}">
                       <i class="las la-trash"></i>
                    </a>
                </td>
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
@include('modals.member_delete_modal')
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
