@extends('admin_layout.app')

@section('title','Admin Panel | Dashbaord')
@section('breadcrumbs_title','Dashbaord')
@section('MenuDashbaord','current')

@section('content')
<!-- container fluid Start -->
<div class="center-error">
    <div class="row">
        <div class="col-md-8">
            <div class="main-error mb-xlg">
                <h2 class="error-code text-dark text-center text-weight-semibold m-none">404 <i class="fa fa-file"></i></h2>
                <p class="error-explanation text-center">We're sorry, but the page you were looking for doesn't access.</p>
            </div>
        </div>
        <div class="col-md-4">
            <h4 class="text">Here are some useful links</h4>
            <ul class="nav nav-list primary">
                <li>
                    <a href="{{ route('admin.dashboard') }}"><i class="fa fa-caret-right text-dark"></i> Dashboard</a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- container fluid End -->

@endsection
@section('script')

@endsection