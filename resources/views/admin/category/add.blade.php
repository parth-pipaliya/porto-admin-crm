@extends('admin_layout.app')

@if(!empty($data->id))
    @section('title','Admin Panel | Edit Category Details')
@else
    @section('title','Admin Panel | Add Category Details')
@endif
@section('breadcrumbs_title','Category Type')
@section('MenuDashbaord','current')

@section('css')
@endsection

@section('content')
<div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body panel-body">
       
                    <form method="POST" action="{{route('admin.category.store')}}" id="category_form" name="category_form">
                        @csrf
                        <input id="id" type="hidden" name="id" value="{{ !empty($data->id) ? $data->id : '' }}">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>Category Name</label>
                                <input id="name" type="text" required class="form-control @error('name') is-invalid @enderror" name="name" value="{{!empty($data->name) ? $data->name : old('name') }}" autocomplete="name" autofocus/>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>Parent Category</label>
                                <select id="parent_id"  type="text" class="form-control @error('parent_id') is-invalid @enderror" name="parent_id" >
                                    <option value="">Select Parent Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}"  {{ !empty($data->parent_id) && $category->id == $data->parent_id ? 'selected' : '' }}>{{$category->name}}</option>
                                    @endforeach
                                </select>
                                @error('parent_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                       
                        <div class="row mt-5">
                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    {{!empty($data->id) ? 'Update' : 'Submit' }}
                                </button>
                                <a href="{{route('admin.category.index')}}">
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
@endsection