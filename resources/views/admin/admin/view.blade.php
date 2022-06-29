@extends('admin_layout.app')

@section('title','Admin Panel | View Admin Details')

@section('breadcrumbs_title','Admin Details')
@section('MenuDashbaord','current')

@section('css')
@endsection

@section('content')
<div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body panel-body">
       
                    <form method="POST" action="" id="user_form" name="user_form">
                        @csrf
                        <input id="id" type="hidden" name="id" value="{{ !empty($data->id) ? $data->id : '' }}">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Name</label>
                                <input type="text" disabled placeholder="Name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{!empty($data->name) ? $data->name : old('name') }}" autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label>Email</label>
                                <input id="email"  disabled parsley-type="email" type="email" placeholder="Email"  class="form-control @error('email') is-invalid @enderror" name="email" value="{{!empty($data->email) ? $data->email : old('email') }}" autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Password</label>
                                <input id="password" disabled data-parsley-minlength="6" placeholder="Password" type="password" class="form-control @error('password') is-invalid @enderror" value="{{!empty($data->password) ? '**********' : '' }}"  name="password" autocomplete="password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="form-group col-md-6">
                                <label>Confirm Password</label>
                                <input id="password-confirm" disabled data-parsley-equalto="#password" placeholder="Confirm Password" type="password" class="form-control" name="password_confirmation" value="{{!empty($data->password) ? '**********' : '' }}" autocomplete="new-password">
                                @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6 custom-multi-select">
                                <label>Role</label>
                                <select id="roles" disabled name="roles[]" placeholder="Role" class="form-control @error('roles') is-invalid @enderror" multiple="multiple" 
                                    data-plugin-multiselect 
                                    data-plugin-options='{ "maxHeight": 200, "enableCaseInsensitiveFiltering": true, "includeSelectAllOption": true }'>
                                    @foreach($roles as $role)
                                        <option value="{{$role->id}}"  {{ !empty($assign_role) && in_array($role->id, $assign_role) ? 'selected' : '' }}>{{$role->role_name}}</option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>  
                            <div class="form-group col-md-6">
                                <label>Status</label>
                                <select  type="text" disabled class="form-control @error('is_active') is-invalid @enderror" name="is_active" >
                                    <option value="1" {{ isset($data->is_active) && $data->is_active == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="2" {{ isset($data->is_active) && $data->is_active == '2' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('is_active')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>                          
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Superadmin</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" disabled class="custom-control-input" name="is_superadmin" id="is_superadmin"   {{isset($data->is_superadmin) && $data->is_superadmin == '1' ? 'checked' : ''}}>
                                    <label class="custom-control-label" for="is_superadmin">Superadmin</label>
                                </div>
                                @error('is_superadmin')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>                          
                        </div>



                        <div class="row mt-5">
                            <div class="form-group col-md-12">
                                <a href="{{route('admin.admin_user.index')}}">
                                    <button type="button" class="btn btn-secondary waves-effect m-l-5">
                                        Back
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
@endsection

@section('script')
@endsection