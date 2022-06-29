@extends('admin_layout.app')

@if(!empty($data->id))
    @section('title','Admin Panel | Edit Role Details')
@else
    @section('title','Admin Panel | Add Role Details')
@endif
@section('breadcrumbs_title','Role')
@section('MenuDashbaord','current')

@section('css')
@endsection

@section('content')
<div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body panel-body">       
                    <form method="POST" action="{{route('admin.role.store')}}" id="role_form" name="role_form">
                        @csrf
                        <input id="id" type="hidden" name="id" value="{{ !empty($data->id) ? $data->id : '' }}">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>Role Name</label>
                                <input id="role_name" type="text" required class="form-control @error('role_name') is-invalid @enderror" name="role_name" value="{{!empty($data->role_name) ? $data->role_name : old('role_name') }}" autocomplete="role_name" autofocus/>
                                @error('role_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        @if(count($permission_cat) > 0)
                        <div class="border border-light rounded mb-3 mt-5">
                            <div class="card-detail-view">
                                <h4 class="mt-0 mb-0 header-title">Permission Details</h4>
                                <div class="card-detail-list">
                                @php ($i = 1)
                                @foreach($permission_cat as $permissions_category)
                                    <div class="row col-sm-11">
                                        <label for="name" class="col-form-label text-md-right">
                                                {{$i++ .'. '.$permissions_category->name}}
                                        </label>
                                        <div class="row col-md-12">
                                            @foreach($permissions_category->getPermissions as $permission)
                                                <div class="col-md-3">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" name="permissions[]" value="{{$permission->id}}" id="{{'permission_'.$permission->id}}"   {{!empty($assign_permission) && in_array($permission->id ,$assign_permission) ? 'checked' : ''}}>
                                                        <label class="custom-control-label" for="{{'permission_'.$permission->id}}">{{$permission->permission_title}}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                       
                        <div class="row">
                            <div class="form-group col-md-12 mt-5">
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    {{!empty($data->id) ? 'Update' : 'Submit' }}
                                </button>
                                <a href="{{route('admin.role.index')}}">
                                    <button type="button" class="btn btn-secondary waves-effect m-l-5">
                                        Cancel
                                    </button>
                                </a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div> <!-- end col -->
    </div>
</div>
<!-- container fluid End -->
@endsection
@section('script')
@endsection