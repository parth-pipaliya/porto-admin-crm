@extends('admin_layout.auth')

@section('title','Admin Panel | Sign In')
@section('breadcrumbs_title','')
@section('MenuDashbaord','')

@section('content')
<!-- container fluid Start -->
<section class="body-sign body-locked">
    <div class="center-sign">
        <div class="panel panel-sign">
            <div class="panel-body">
                <form action="{{ route('admin.lockscreenPost') }}" method="POST">
                    @csrf
                    <div class="current-user text-center">
                        <img src="{{ asset('admin_assets/images/!logged-user.jpg') }}" alt="John Doe" class="img-circle user-image" />
                        <h2 class="user-name text-dark m-none">
                            @if(Auth::guard('admin')->user())
                                {{Auth::guard('admin')->user()->name}}
                            @endif
                        </h2>
                        <p class="user-email m-none">
                            @if(Auth::guard('admin')->user())
                                {{Auth::guard('admin')->user()->email}}
                            @endif
                        </p>
                    </div>
                    <div class="form-group mb-lg">
                        <div class="input-group input-group-icon">
                            <input name="password" type="password" class="form-control input-lg {{ $errors->has('password')?'is-invalid':'' }}" placeholder="Password" />
                            <span class="input-group-addon">
                                <span class="icon icon-lg">
                                    <i class="fa fa-lock"></i>
                                </span>
                            </span>
                        </div>
                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-xs-6">
                            <p class="mt-xs mb-none">
                                <a href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Not you?</a>
                            </p>
                        </div>
                        <div class="col-xs-6 text-right">
                            <button type="submit" class="btn btn-primary">Unlock</button>
                        </div>
                    </div>
                </form>
                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</section>
<!-- container fluid End -->

@endsection
@section('script')

@endsection