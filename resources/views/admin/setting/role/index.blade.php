@extends('admin_layout.app')

@section('title','Admin Panel | Dashbaord')
@section('breadcrumbs_title','Role')
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
                    @if(!empty(Auth::user()) && (Auth::user()->hasPermissionTo('role-add') || Auth::user()->is_superadmin == 1))
                    <div class="block-options-item mb-3 mr-3 float-right">
                        <a href="{{route('admin.role.create')}}" class="btn btn-info">Add Role</a>
                    </div>
                    @endif
                    <div class="table-responsive panel-body">
                        <table id="role_datatable"  class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Role Name</th>
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
    $('#role_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        // responsive: true,
        ajax: {
            url: "{{route('admin.role.index')}}",
            type: 'get',
            dataType: "json",
            async: true,
        },
        columns: [
            { data: 'id', name: 'id', searchable: false },
            { data: 'role_name', name: 'role_name' },
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
        var deleteUrl = '{{ route("admin.role.destroy", ":id") }}';
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
                        var oTable = $('#role_datatable').dataTable();
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