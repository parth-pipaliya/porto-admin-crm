@extends('admin_layout.app')

@section('title','Admin Panel | Support Request')
@section('breadcrumbs_title','Support Request')
@section('MenuDashbaord','current')

@section('css')
@endsection

@section('content')
<!-- container fluid Start -->
<div>
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <!-- @if(!empty(Auth::user()) && (Auth::user()->hasPermissionTo('support_request-add') || Auth::user()->is_superadmin == 1))
                        <div class="block-options-item mb-3 mr-3 float-right">
                            <a href="{{route('admin.support_request.create')}}" class="btn btn-info">Add Support Request</a>
                        </div>  
                    @endif                                         -->
                    <div class="table-responsive panel-body">
                        <table id="support_request_datatable"  class="table table-bordered table-striped" >
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Support Id</th>
                                    <th>User Details</th>
                                    <th>Subject</th>
                                    <th>Description</th>
                                    <th>Created Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>                        
                        </table>
                    </div>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
</div>
<!-- container fluid End -->
@endsection
@section('script')
<script>  
$(function () {
    $('#support_request_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        // responsive: true,
        ajax: {
            url: "{{route('admin.support_request.index')}}",
            type: 'get',
            dataType: "json",
            async: true,
        },
        columns: [
            { data: 'id', name: 'id', searchable: false },
            { data: 'support_id', name: 'support_id', searchable: false },
            { data: 'userDetails', name: 'User Details' },
            { data: 'title', name: 'Subject' },
            { data: 'description', name: 'description' },
            { data: 'created_at', name: 'created_at' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        order: [[0, 'desc']],
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            var showColumn = false;
            api.columns([0]).visible(showColumn);
        }
    });

});

function deleteRow(row_id) {
    if (row_id) {
        var deleteUrl = '{{ route("admin.support_request.destroy", ":id") }}';
        deleteUrl = deleteUrl.replace(':id', row_id);
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger m-l-10',
            confirmButtonText: 'Yes, delete it!'
        }).then(function () {
            if (row_id) {
                $.ajax({
                    headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                    url: deleteUrl,
                    type: "delete",
                    dataType: 'json',
                    success: function (data) {
                        swal(
                            'Deleted!',
                            data.msg,
                            'success'
                        )
                        toastr.success(data.msg, App_name_global);
                        var oTable = $('#support_request_datatable').dataTable();
                        oTable.fnDraw(true);
                    },
                    error: function (error) {
                        toastr.error(error.responseJSON.msg, App_name_global);
                    }
                });
            }
        });
    }
}

function closeTicketRow(row_id) {
    if (row_id) {
        var closeUrl = '{{ route("admin.support_request_close", ":id") }}';
        closeUrl = closeUrl.replace(':id', row_id);
        swal({
            title: 'Are you sure?',
            text: "You won't be able to close this support request!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger m-l-10',
            confirmButtonText: 'Yes, delete it!'
        }).then(function () {
            if (row_id) {
                $.ajax({
                    headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                    url: closeUrl,
                    type: "get",
                    dataType: 'json',
                    success: function (data) {
                        swal(
                            'Status Close!',
                            data.msg,
                            'success'
                        )
                        toastr.success(data.msg, App_name_global);
                        var oTable = $('#support_request_datatable').dataTable();
                        oTable.fnDraw(true);
                    },
                    error: function (error) {
                        toastr.error(error.responseJSON.msg, App_name_global);
                    }
                });
            }
        });
    }
}
</script>
@endsection