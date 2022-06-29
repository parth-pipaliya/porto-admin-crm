@extends('admin_layout.app')

@section('title','Admin Panel | User List')
@section('breadcrumbs_title','User List')
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
                       <!-- Custom Filter -->
                    @if(!empty(Auth::user()) && (Auth::user()->hasPermissionTo('admin-add') || Auth::user()->is_superadmin == 1))
                    <!-- <div class="block-options-item mb-3 mr-3 float-right">
                        <a href="{{route('admin.user.create')}}" class="btn btn-info">Add User </a>
                    </div>                    -->
                    @endcan

                    <div id="AdvanceFiletrShow" class="mb-3 mt-3 card-body panel-body">
                        <label>Advanced Filter</label>
                        <div class="row mb-3">  
                            <div class="col-md-3">
                                <div className="form-group">
                                    <label>Type</label>
                                    <select id="searchByHcpType" name="subcategory_id" class="form-control">
                                        <option value=''>Select Type</option>
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>                                
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div className="form-group">
                                    <label>Date Range</label>
                                    <input type="text" class="form-control" name="date_range" id="user-date-range"  />
                                    <input type="hidden" class="form-control" id="user_start_date" name="start_date" />
                                    <input type="hidden" class="form-control" id="user_end_date" name="end_date"  />     
                                </div>
                            </div>                       
                            <div class="col-md-3">
                                <div className="form-group">
                                    <label>Status</label>
                                    <select id="searchByStatus" name="status" class="form-control">
                                        <option value=''>Select Status</option>
                                        <option value="1">Active</option>
                                        <option value="2">Inactive</option>
                                        <option value="3">Pending Verify</option>
                                    </select>       
                                </div>
                            </div>
                        </div>
                    </div>                

                    <div class="table-responsive panel-body">
                        <table id="user_datatable"  class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>Mobile No.</th>
                                    <th>Type</th>
                                    <th>Date of Joining</th>
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
var data_status = '';
var data_category_id = '';
$(function () {
    $('#user_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        ajax: {
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: "{{route('admin.user.index')}}",
            type: 'get',
            dataType: "json",
            async: true,
            data: {
                status : data_status, 
                category_id : data_category_id, 
                filter_status: function () { return $('#searchByStatus').val() },               
                subcategory_id: function () { return $('#searchByHcpType').val() }, 
                start_date: function () { return $('#user_start_date').val() },
                end_date: function () { return $('#user_end_date').val() }                  
            }
        },
        columns: [
            { data: 'id', name: 'users.id', searchable: false },
            { data: 'user_name', name: 'user_name' },
            { data: 'email', name: 'users.email' },
            { data: 'mobile_no', name: 'mobile_no' },
            { data: 'category_type', name: 'category_type' },
            { data: 'created_at', name: 'users.created_at', searchable: false },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        order: [[0, 'desc']],
        createdRow: function (row, data, dataIndex) {
           
        },
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            var showColumn = false;
            api.columns([0]).visible(showColumn);         
        },
        drawCallback: function (settings) {
           
        }
    });
    
    $('#searchByHcpType, #searchByStatus').on('change', function (ev, picker) {
        var oTable = $('#user_datatable').dataTable();
        oTable.fnDraw(true);
    });

    $('#user-date-range').daterangepicker({
        startDate: moment().subtract(1, 'years'),
        endDate: moment(),
        maxDate: moment(),
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        },
        alwaysShowCalendars: true,
        opens: "right",
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    });

    $('#user-date-range').on('apply.daterangepicker', function (ev, picker) {
        $('#user_start_date').val(picker.startDate.format('YYYY-MM-DD'));
        $('#user_end_date').val(picker.endDate.format('YYYY-MM-DD'));
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        var oTable = $('#user_datatable').dataTable();
        oTable.fnDraw(true);
    });
});

function deleteRow(row_id) {
    if (row_id) {
        var deleteUrl = '{{ route("admin.user.destroy", ":id") }}';
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
                        var oTable = $('#user_datatable').dataTable();
                        oTable.fnDraw(true);
                        toastr.success(data.msg, App_name_global);
                    },
                    error: function (error) {
                        toastr.error(error.responseJSON.msg, App_name_global);
                    }
                });
            }
        });
    }
}

function changeStatusRow(row_id, status) {
    if (row_id) {
        swal({
            title: 'Are you sure?',
            text: "You won't be able to Change User Status!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger m-l-10',
            confirmButtonText: 'Yes, Change Status it!'
        }).then(function () {
            if (row_id) {
                $.ajax({
                    headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                    url: user_url + "/change_status",
                    type: "post",
                    data: { 'user_id': row_id, 'status': status },
                    dataType: 'json',
                    success: function (data) {
                        swal(
                            'Status!',
                            data.msg,
                            'success'
                        )
                        var oTable = $('#user_datatable').dataTable();
                        oTable.fnDraw(true);
                        toastr.success(data.msg, App_name_global);
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