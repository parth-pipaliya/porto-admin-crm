@extends('admin_layout.app')

@if(!empty($data->id))
    @section('title','Admin Panel | Edit Admin Details')
@else
    @section('title','Admin Panel | Add Admin Details')
@endif
@section('breadcrumbs_title','User List')
@section('MenuDashbaord','current')

@section('css')
@endsection

@section('content')
<!-- container fluid Start -->
<div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body panel-body">
       
                    <form method="POST" action="{{route('admin.user.store')}}" id="user_form" name="user_form"  enctype="multipart/form-data">
                        @csrf
                        <input id="id" type="hidden" name="id" value="{{ !empty($data->id) ? $data->id : '' }}">
                        <div class="row">
                            <dt class="col-sm-5"><label>Profile Image</label></dt>
                            <dd class="col-sm-7 mb-3"> 
                                <input type="file" name="profile_image" class="form-control" id="profile_image" accept="image/*" onchange="return fileValidation('profile_image')">
                                <div id="profile_imagePreview">
                                    @if(!empty($data->profile_image)) 
                                    <img src="{{$data->profile_image}}"  style="max-width: 100%;height:100px;display:block;">
                                    @endif
                                </div>
                            </dd>
                        </div>
                        <div class="border border-light rounded mb-3">
                            <div class="card-detail-view">
                                <h4 class="mt-0 mb-0 header-title mb-5">User Details</h4>
                                 <div class="card-detail-list">
                                    <div class="row mb-3">
                                        <dt class="col-sm-5"><label>Maintype</label></dt>
                                        <dd class="col-sm-7"> 
                                            <select id="category_id"  type="text" required class="form-control" name="category_id" >
                                                <option value="">Select Maintype</option>
                                                @foreach($categories as $category)
                                                    <option value="{{$category->id}}"  {{ !empty($data->category_id) && $category->id == $data->category_id ? 'selected' : '' }}>{{$category->name}}</option>
                                                @endforeach
                                            </select>
                                        </dd>
                                    </div>
                                    <div class="row mb-3">
                                        <dt class="col-sm-5"><label>Subtype</label></dt>
                                        <dd class="col-sm-7"> 
                                            <select id="subcategory_id"  type="text" required class="form-control" name="subcategory_id" >
                                                <option value="">Select Subtype</option>   
                                                @foreach($categories as $category)
                                                    <option value="{{$category->id}}"  {{ !empty($data->subcategory_id) && $category->id == $data->subcategory_id ? 'selected' : '' }}>{{$category->name}}</option>
                                                @endforeach
                                            </select>
                                        </dd>
                                    </div>
                                    <div class="row mb-3">
                                        <dt class="col-sm-5"><label>First Name</label></dt>
                                        <dd class="col-sm-7"> 
                                           <input type="text" required class="form-control" name="first_name" value="{{$data->first_name}}"> 
                                        </dd>
                                    </div>
                                    <div class="row mb-3">
                                        <dt class="col-sm-5"><label>Last Name</label></dt>
                                        <dd class="col-sm-7"> 
                                           <input type="text" class="form-control" name="last_name" value="{{$data->last_name}}"> 
                                        </dd>
                                    </div>
                                    <div class="row mb-3">
                                        <dt class="col-sm-5"><label>Email</label></dt>
                                        <dd class="col-sm-7"> 
                                          <input type="text" required class="form-control" name="email" value="{{$data->email}}"> 
                                        </dd>
                                    </div>
                                    <div class="row mb-3">
                                        <dt class="col-sm-5"><label>Country Code</label></dt>
                                        <dd class="col-sm-7"> 
                                          <input type="text" required class="form-control" name="country_code" value="{{$data->country_code}}"> 
                                        </dd>
                                    </div>
                                    <div class="row mb-3">
                                        <dt class="col-sm-5"><label>Mobile No</label></dt>
                                        <dd class="col-sm-7"> 
                                          <input type="text" required class="form-control" name="mobile_no" value="{{$data->mobile_no}}"> 
                                        </dd>
                                    </div>
                                    <div class="row mb-3">
                                        <dt class="col-sm-5"><label>Gender</label></dt>
                                        <dd class="col-sm-7"> 
                                            <input class="mr-1" type="radio" id="gender_male" name="gender" value="0" {{($data->gender == '0') ? 'checked' :''}}><label for="gender_male">Male</label>
                                            <input class="ml-3 mr-1" type="radio" id="gender_female" name="gender" value="1" {{($data->gender == '1') ? 'checked' :''}}><label for="gender_female">Female</label>
                                        </dd>
                                    </div>
                                    <div class="row mb-3">
                                        <dt class="col-sm-5"><label>Status</label></dt>
                                        <dd class="col-sm-7"> 
                                            <input type="radio" class="mr-1" id="status_active" name="status" value="1" {{($data->status == '1') ? 'checked' :''}}><label for="status_active">Active</label>
                                            <input type="radio" class="ml-3 mr-1" id="gender_inactive" name="status" value="2" {{($data->status == '2') ? 'checked' :''}}><label for="gender_inactive">Inactive</label>
                                            <input type="radio" class="ml-3 mr-1" id="gender_pending" name="status" value="3" {{($data->status == '3') ? 'checked' :''}}><label for="gender_pending">Verify Pending</label>
                                        </dd>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row mt-5">
                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    {{!empty($data->id) ? 'Update' : 'Submit' }}
                                </button>
                                <a href="{{route('admin.user.index')}}">
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
@endsection

@section('script')
<script>
function fileValidation(id_name) {
    var fileInput = document.getElementById(id_name);
    var filePath = fileInput.value;
    // Allowing file type
    var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
    if (!allowedExtensions.exec(filePath)) {
        alert('Invalid file type');
        fileInput.value = '';
        return false;
    } else {
        // Image preview
        if (fileInput.files && fileInput.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#' + id_name + 'Preview').empty();
                document.getElementById(
                    id_name + 'Preview').innerHTML =
                    '<img src="' + e.target.result
                    + '" height="100px" width="100px"/>';
            };

            reader.readAsDataURL(fileInput.files[0]);
        }
    }
}
</script>
@endsection