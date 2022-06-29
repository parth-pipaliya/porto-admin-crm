@extends('admin_layout.app')

@section('title','Admin Panel | Dashbaord')
@section('breadcrumbs_title','Permission Categories')
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
                    @if(!empty(Auth::user()) && (Auth::user()->hasPermissionTo('permission-add') || Auth::user()->is_superadmin == 1))
                    <div class="block-options-item mb-3 mr-3 float-right">
                        <a href="javascript:void(0)" onclick="addRow()" class="btn btn-info">Add Permission</a>
                    </div>                            
                    @endif           
                    <div class="table-responsive panel-body">
                        <table id="permission_datatable"  class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <!-- <th>Id</th> -->                                
                                    <th>Permission Category Name</th>
                                    <th>Permission Name</th>
                                    <th>Permission Value</th>
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
<!-- Add modal -->
<div id="addPermission" class="modal-block modal-block-md mfp-hide">
    <section class="panel">        
        <form method="POST" id="permission_form" name="permission_form">
            <header class="panel-heading">
                <h2 class="panel-title modal-title"></h2>
            </header>
            <div class="panel-body">
                <div class="modal-wrapper">
                    @csrf
                    <input id="permission_id" type="hidden" name="id" >

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>Permission Category</label>
                            <select  required id="permission_category_id" class="form-control" name="permission_category_id" >
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>Permission Name</label>
                            <input type="text" required placeholder="Permission Name" class="form-control" id="permission_title" name="permission_title" >
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>Permission Value</label>
                            <input type="text" required placeholder="Permission Value" class="form-control" id="permission_name" name="permission_name" >
                        </div>
                    </div>
                </div>
            </div>
            <footer class="panel-footer">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" id="submit_btn" class="btn btn-primary modal-confirm">Confirm</button>
                        <button class="btn btn-default modal-dismiss">Cancel</button>
                    </div>
                </div>
            </footer>
        </form>
    </section>
</div><!-- /.modal -->
<!-- End modal -->
<!-- container fluid End -->
@endsection
@section('script')
<script>    
$(function () {
    $('#permission_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        // responsive: true,
        ajax: {
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: "{{route('admin.permission.index')}}",
            type: 'get',
            dataType: "json",
            async: true,
        },
        columns: [
            // { data: 'id', name: 'id', searchable: false },
            { data: 'permission_category', name: 'permission_category' },
            { data: 'permission_title', name: 'permission_title' },
            { data: 'permission_name', name: 'permission_name' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        //  order: [[0, 'desc']],
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            var showColumn = false;
        }
    });

    $(document).on('submit', '#permission_form', function (event) {
        $.ajax({
            type: 'post',
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: "{{route('admin.permission.store')}}",
            data: $('#permission_form').serialize(),
            success: function (response) {
                $('#permission_id').val('');
                $('#permission_category_id').val('');
                $('#permission_title').val('');
                $('#permission_name').val('');
                $("form[name='permission_form']").trigger("reset");
                var magnificPopup = $.magnificPopup.instance; 
                magnificPopup.close();
                var oTable = $('#permission_datatable').dataTable();
                oTable.fnDraw(true);
                toastr.success(response.msg, App_name_global);
                return false;
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var myArr = JSON.parse(jqXHR.responseText);
                $.each(myArr.errors, function (index, value) {
                    toastr.error(value, App_name_global);
                });
                return false;
            },
        });
        return false;
    });

    
	$(document).on('click', '.modal-dismiss', function (e) {
		e.preventDefault();
		$.magnificPopup.close();
	});

});


function addRow() {
    $.ajax({
        type: 'get',
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        url: "{{route('admin.permission.create')}}",
        success: function (response) {
            if (response.status) {
                $("form[name='permission_form']").trigger("reset");
                $('.modal-title').text('Add Permission Details');
                $('#submit_btn').text('Add');
                $('#permission_id').val('');
                $('#permission_category_id').val('');
                $('#permission_title').val('');
                $('#permission_name').val('');
                console.log(response.permission_cats);
                if (response.permission_cats) {
                    $("#permission_category_id option").remove();
                    response.permission_cats.forEach(element => {
                        $('#permission_category_id').append(new Option(element.name, element.id));
                    });
                }
                setTimeout(function () {
                    $('#permission_title').focus();
                }, 1000);
                $.magnificPopup.open({
                        items: {
                            src: '#addPermission'
                        },
                        type: 'inline',
                        preloader: true,
                        overflowY: 'auto'
                    });
            }
            else {
                toastr.error(error.responseJSON.msg, App_name_global);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            toastr.error(jqXHR.responseJSON.msg, App_name_global);
            return false;
        },
    });
}

function editRow(id) {
    var editUrl = '{{ route("admin.permission.edit", ":id") }}';
    editUrl = editUrl.replace(':id', id);
    $.ajax({
        type: 'get',
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        url: editUrl,
        success: function (response) {
            if (response.status) {
                $("form[name='permission_form']").trigger("reset");
                $('.modal-title').text('Edit Permission Details');
                $('#submit_btn').text('Update');
                if (response.permission_cats) {
                    $("#permission_category_id option").remove();
                    response.permission_cats.forEach(element => {
                        $('#permission_category_id').append(new Option(element.name, element.id));
                    });
                }
                if (response.data) {
                    $('#permission_id').val(response.data.id);
                    $('#permission_category_id').val(response.data.permission_category_id);
                    $('#permission_title').val(response.data.permission_title);
                    $('#permission_name').val(response.data.permission_name);
                }
                setTimeout(function () {
                    $('#permission_title').focus();
                }, 1000);
                $.magnificPopup.open({
                        items: {
                            src: '#addPermission'
                        },
                        type: 'inline',
                        preloader: true,
                        overflowY: 'auto'
                    });
            }
            else {
                toastr.error(error.responseJSON.msg, App_name_global);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            toastr.error(jqXHR.responseJSON.msg, App_name_global);
            return false;
        },
    });
}

function deleteRow(row_id) {
    if (row_id) {
        var deleteUrl = '{{ route("admin.permission.destroy", ":id") }}';
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
                        var oTable = $('#permission_datatable').dataTable();
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