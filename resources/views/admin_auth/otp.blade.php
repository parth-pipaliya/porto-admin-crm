@extends('admin_layout.auth')

@section('title','Admin Panel | Sign In')
@section('breadcrumbs_title','')
@section('MenuDashbaord','')

@section('content')
<!-- container fluid Start -->
<section class="body-sign">
    <div class="center-sign">
        <a href="/" class="logo pull-left">
            <img src="{{ asset('admin_assets/images/logo.png') }}" height="54" alt="Porto Admin" />
        </a>

        <div class="panel panel-sign">
            <div class="panel-title-sign mt-xl text-right">
                <h2 class="title text-uppercase text-weight-bold m-none"><i class="fa fa-key mr-xs"></i> Security Verification</h2>
            </div>
            <div class="panel-body">
                <form action="{{ route('admin.otpPost') }}" method="POST">
                    @csrf
                    <input type="hidden" name="email" value="{{$email}}">
                    <input type="hidden" name="password" value="{{$password}}">
                    <div class="form-group mb-lg">
                        <label>Security Verification</label>
                        <div class="input-group input-group-icon">
                            <input name="otp" type="text" class="form-control input-lg  {{ $errors->has('otp')?'is-invalid':'' }}"  />
                            <span class="input-group-addon">
                                <span class="icon icon-lg">
                                    <i class="fa fa-key"></i>
                                </span>
                            </span>
                        </div>
                        @if ($errors->has('otp'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('otp') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-sm-8">
                            <!-- <div class="checkbox-custom checkbox-default">
                                <input id="RememberMe" name="rememberme" type="checkbox"/>
                                <label for="RememberMe">Remember Me</label>
                            </div> -->
                        </div>
                        <div class="col-sm-4 text-right">
                            <button type="submit" class="btn btn-primary hidden-xs">Submit</button>
                            <button type="submit" class="btn btn-primary btn-block btn-lg visible-xs mt-lg">Submit</button>
                        </div>
                    </div>

                    <!-- <span class="mt-lg mb-lg line-thru text-center text-uppercase">
                        <span>or</span>
                    </span> -->

                    <!-- <div class="mb-xs text-center">
                        <a class="btn btn-facebook mb-md ml-xs mr-xs">Connect with <i class="fa fa-facebook"></i></a>
                        <a class="btn btn-twitter mb-md ml-xs mr-xs">Connect with <i class="fa fa-twitter"></i></a>
                    </div> -->

                    <!-- <p class="text-center mt-5">Don't have an account yet? <a href="pages-signup.html">Sign In!</a></p> -->

                </form>
            </div>
        </div>

        <p class="text-center text-muted mt-md mb-md">&copy; Copyright 2016. All Rights Reserved.</p>
    </div>
</section>
<!-- container fluid End -->

@endsection
@section('script')

@endsection