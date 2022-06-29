@extends('admin_layout.app')

@if(!empty($data->id))
    @section('title','Admin Panel | Edit Static Page Details')
@else
    @section('title','Admin Panel | Add Static Page Details')
@endif
@section('breadcrumbs_title','Static Page Details')
@section('MenuDashbaord','current')

@section('css')
@endsection

@section('content')
<div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body panel-body">
       
                    <form method="POST" action="{{route('admin.static_pages.store')}}" id="static_page_form" name="static_page_form">
                        @csrf
                        <input id="id" type="hidden" name="id" value="{{ !empty($data->id) ? $data->id : '' }}">
                        
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Page Name</label>
                                <input type="text" required placeholder="Page Name" class="form-control @error('page_name') is-invalid @enderror" name="page_name" value="{{!empty($data->page_name) ? $data->page_name : old('page_name') }}" autocomplete="page_name" autofocus>
                                @error('page_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                             <div class="form-group col-md-6">
                                <label>Status</label>
                                <select  type="text" class="form-control @error('status') is-invalid @enderror" name="status" >
                                    @foreach($status as $key => $value)
                                        <option value="{{$key}}" {{ isset($data->status) && $key == $data->status ? 'selected' : '' }}>{{$value}}</option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-12">
                                <textarea class="summernote" name="page_description">{{!empty($data->page_description) ? $data->page_description : old('page_description') }}</textarea>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    {{!empty($data->id) ? 'Update' : 'Submit' }}
                                </button>
                                <a href="{{route('admin.static_pages.store')}}">
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
    $('.summernote').summernote({
        height: 300,                 // set editor height
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        focus: true                 // set focus to editable area after initializing summernote
    });
</script>

@endsection