@extends('frontend.layouts.user_panel')

@section('panel_content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                @if(Auth::user()->membership == '')
                    <div class="">
                        <h1 class="h3">{{ translate('Become A Member') }}</h1>
                    </div>
                @endif
            </div>
            <div class="col-md-6">
                @php
                    $terms =  \App\Page::where('slug', 'memberbenefit')->first();
                @endphp
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                    About Membership
                </button>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body">
                        <?php echo $terms->content ?>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Done</button>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(Auth::user()->membership == 'waiting')
    <div class="">
        Please wait you already registerd.
    </div>
    @elseif(Auth::user()->membership == 'approved')
    <div class="">
        You are already a membar go to member dashboard.
    </div>
    @elseif(Auth::user()->membership == 'rejected')
    <div class="">
        Oops.!! Your membership has been rejected. Contact us for any query.
    </div>
    @else
    <div class="">
        <form class="form form-horizontal mar-top" action="{{route('membership.create')}}" method="POST">
            <div class="row gutters-5">
                <div class="col-lg-8">
                    @if(Session::has('error'))
                        <div class="alert alert-danger alert-block">
                            <button type="button" class="close m-1" data-dismiss="alert"></button>
                            <strong>{{ Session::get('error') }}</strong>
                        </div>
                    @endif
                </div>
                <div class="col-lg-9">
                    @csrf
                    <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                    {{-- <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{translate('About Membership')}}</h5>
                            <div class="col text-right">
                                <a href="{{ route('service.create') }}" class="btn btn-circle btn-info">
                                    <span>{{translate('Add New Service')}}</span>
                                </a>
                            </div>
                        </div>
                    </div> --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{translate('Please provide true information for membership.')}}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{translate('Full Name')}} <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="name" placeholder="{{ translate('Full Name') }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{translate('Date of Birth')}}<span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="date" class="form-control" name="birth_date" placeholder="{{ translate('Date of Birth') }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{translate('Phone Number')}}<span class="text-danger">*</span><br><span class="font-weight-bold font-italic small">Can use this to log in.</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="phone" value="{{strstr(Auth::user()->phone,'01')}}" placeholder="{{ translate('Contact Number') }}" pattern="[0-9]{11}" title="Type 11 Digits. ex: 01XXXXXXXXX" required>
                                    <span class="font-weight-bold font-italic small">Changing this will also change your login credential.</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{translate('Other Information')}}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{translate('Select Card Type:')}} <span class="text-danger">*</span></label>
                                &emsp;&emsp;<input type="radio" name="card" value="NID">
                                <label class="m-3 col-from-label" for="nid">NID</label>&emsp;&emsp;
                                <input type="radio" name="card" value="Passport">
                                <label class="m-3 col-from-label" for="passport">Passport</label>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{translate('NID/Passport')}} <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="number" class="form-control" name="id_card_number" placeholder="{{ translate('National ID card number/Passport number') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{translate('Country')}}<span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="country" pattern="[A-Za-z]+" placeholder="{{ translate('Country') }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{translate('City')}}<span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="city" pattern="[A-Za-z]+" placeholder="{{ translate('City') }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{translate('Postal Code (optional)')}}</label>
                                <div class="col-md-8">
                                    <input type="number" class="form-control" name="postal_code" placeholder="{{ translate('Postal Code') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{translate('Address')}}<span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="address" placeholder="{{ translate('Address') }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{translate('Email')}}<span class="text-danger">*</span><br><span class="font-weight-bold font-italic small">Can use this to log in.</span></label>
                                <div class="col-md-8">
                                    <input type="email" class="form-control" name="email" placeholder="{{ translate('Email') }}" value="{{Auth::user()->email}}" required>
                                    <span class="font-weight-bold font-italic small">Changing this will also change your login credential.</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">
                                    {{'Password'}}<span class="text-danger">*</span>
                                    <br>
                                    <span class="font-weight-bold font-italic small">(Current Password)</span>
                                </label>
                                <div class="col-md-8">
                                    <input type="password" class="form-control" name="pass" placeholder="{{ translate('Password') }}" required/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{translate('Emergency Contact')}}<span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="emergency_contact" pattern="[0-9]+" placeholder="{{ translate('Emergency Contact') }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{translate('Referral Code')}}</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" placeholder="{{  translate('Referral Code (if any)') }}" name="referral_code">
                                </div>
                            </div>

                            <div class="form-group row m-2">
                                <input type="checkbox" class="form-check" name="paper" value="sign" required>
                                <label class="col-lg-10 col-from-label m-1">I, hereby , is accepting the <a href="{{route('terms')}}">terms & conditions</a> of Khoshroz Kitab Mahal Limited.<span class="text-danger">*</span></label>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="btn-toolbar float-left mb-3" role="toolbar" aria-label="Toolbar with button groups">
                        <div class="btn-group" role="group" aria-label="Second group">
                            <button type="submit" name="button" value="publish" class="btn btn-success">{{ translate('Apply for Membership') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @endif
@endsection
