@extends('admin_layout.app')

@if(!empty($data->id))
    @section('title','Admin Panel | Edit Support Details')
@else
    @section('title','Admin Panel | Add Support Details')
@endif
@section('breadcrumbs_title','Support Details')
@section('MenuDashbaord','current')

@section('css')
@endsection

@section('content')
<div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body panel-body">
       
                    <form method="POST" action="{{route('admin.support_request.store')}}" id="support_request_form" name="user_form">
                        @csrf
                        <input id="support_id" type="hidden" name="id" value="{{ !empty($data->id) ? $data->id : '' }}">
                        <input id="id" type="hidden" name="user_id" value="{{ !empty($data->user_id) ? $data->user_id : '' }}">
                       
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Subject</label>
                                <input readonly type="text"  class="form-control" name="title" value="{{ !empty($data->title) ? $data->title : old('title') }}" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>Status</label>
                                <select  class="form-control @error('status') is-invalid @enderror" name="status" >
                                    <option value="">Select Status</option>
                                    @foreach($status as $key => $value)
                                        <option value="{{$key}}"  {{ isset($data->status) && $key == $data->status ? 'selected' : '' }}>{{$value}}</option>
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
                                <label>Description</label>
                                <textarea readonly rows="2" class="form-control" name="description">{{$data->description}}</textarea>
                            </div>
                        </div>      
                        
                        <div class="row mt-5">
                            <dt class="col-sm-5"><label>Attachment File</label></dt>
                            <dd class="col-sm-7"> 
                                <img src="{{$data->attachment}}" style="max-width: 100%;height:100px;display:block;">
                                <a href="{{$data->attachment}}" download>
                                    Click Here to Download
                                </a>
                            </dd>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    {{!empty($data->id) ? 'Update' : 'Submit' }}
                                </button>
                                <a href="{{ url('support_request') }}">
                                    <button type="button" class="btn btn-secondary waves-effect m-l-5">
                                        Cancel
                                    </button>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(!empty($data->chatSupport) && count($data->chatSupport) > 0)
    <div class="row mt-5">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body panel-body">
                    <h4 class="text-center">Support Chat</h4>
                    <div class="order-chat-section">
                        <div class="order-chat-header">
                            <div class="order-store-detail">
                                <div class="order-store-img bg-dark-shop">
                                    @if(!empty($data->userDetails))
                                    <img src="{{$data->userDetails->profile_image}}" alt="" style="width:50px;height:50px">
                                    @endif
                                </div>
                                <div class="order-store-text-head">
                                    @if(!empty($data->userDetails))
                                    <h3>{{$data->userDetails->user_name}}</h3>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div id="chat_window" class="mCSB_container"></div> 
                        @if(isset($data->status) && ($data->status != '3'))
                        <div class="order-chat-footer">
                            <div class="order-chat-enter-area">                                    
                                <textarea id="reply_message" row="2" name="message" placeholder="Type a message" class="form-control"></textarea>
                                <div class="chat-actions-button">
                                    <button type="button" class="chat-msg-send-btn" onclick="sendReply()"><i class="fa fa-paper-plane-o"></i></button>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div>
    @endif
</div>
<!-- Add modal -->
<div id="editSupportChatMsg" class="modal-block modal-block-md mfp-hide">
    <section class="panel">        
        <form method="POST" id="support_chat_msg_form" name="support_chat_msg_form">
            <header class="panel-heading">
                <h2 class="panel-title modal-title">Chat Message</h2>
            </header>
            <div class="panel-body">
                <div class="modal-wrapper">
                    @csrf
                    <input id="edit_chat_id" type="hidden" name="id" >
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label id="fees_percentage_label">Message</label>
                            <textarea required rows="5" placeholder="Chat Message" class="form-control" id="edit_chat_message" name="message"> 
                            </textarea>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="panel-footer">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" id="submit_btn" class="btn btn-primary modal-confirm">Update</button>
                        <button class="btn btn-default modal-dismiss">Cancel</button>
                    </div>
                </div>
            </footer>
        </form>
    </section>
</div><!-- /.modal -->
<!-- End modal -->
@endsection

@section('script')
<script>
    $(document).ready(function() {
        getChatMessage();

        $(document).on('submit', '#support_chat_msg_form', function (event) {
            var reply_msg = $('#edit_chat_message').val();
            var chat_id = $('#edit_chat_id').val();
            var support_id = $("#support_id").val();
            if (reply_msg != '' && chat_id != '') {
                $.ajax({
                    headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                    url: "{{route('admin.support_chat.store')}}",
                    type: "post",
                    data: { 'message': reply_msg, 'chat_id': chat_id, 'support_id': support_id },
                    dataType: 'json',
                    success: function (data) {
                        toastr.success(data.msg, App_name_global);
                        $('#edit_chat_message').val('');
                        $('#edit_chat_id').val('');                    
                        var magnificPopup = $.magnificPopup.instance; 
                        magnificPopup.close();
                        getChatMessage();
                        return false;
                    },
                    error: function (error) {
                        toastr.error(error.responseJSON.msg, App_name_global);
                    }
                });
            }else{
                toastr.success('Message is not update', App_name_global);
            }
            return false;
        });

        $(document).on('click', '.modal-dismiss', function (e) {
            e.preventDefault();
            $.magnificPopup.close();
        });

    });
    var timerID = setInterval(function() {
                 getChatMessage();
            }, 60 * 1000);

function getChatMessage() {
    var support_id = $("#support_id").val();
    if (support_id != '') {
        var chatUrl = '{{ route("admin.support_request_chat", ":id") }}';
        chatUrl = chatUrl.replace(':id', support_id);
        $.ajax({
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: chatUrl,
            type: "get",
            success: function (data) {
                $('body').find('#chat_window').empty();
                $('body').find('#chat_window').html(data);
                if ($('.chat-scrollbar').length) {
                    $(".chat-scrollbar").stop().animate({ scrollTop: $(".chat-scrollbar")[0].scrollHeight}, 1000);
                }
            },
            error: function (error) {
                toastr.error(error.responseJSON.msg, App_name_global);
            }
        });
    }
}

function editChatMessage(chatId) {
    if (chatId != '') {
        var editUrl = '{{ route("admin.support_chat.edit", ":id") }}';
        editUrl = editUrl.replace(':id', chatId);
        $.ajax({
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: editUrl,
            type: "get",
            dataType: 'json',
            success: function (data) {
                $('#edit_chat_message').val(data.data.message);
                $('#edit_chat_id').val(data.data.id);
                $.magnificPopup.open({
                        items: {
                            src: '#editSupportChatMsg'
                        },
                        type: 'inline',
                        preloader: true,
                        overflowY: 'auto'
                    });
            },
            error: function (error) {
                toastr.error(error.responseJSON.msg, App_name_global);
            }
        });
    }
}

function deleteChatMessage(messageId) {
    if (messageId) {
        var deleteUrl = '{{ route("admin.support_chat.destroy", ":id") }}';
        deleteUrl = deleteUrl.replace(':id', messageId);
        swal({
            title: 'Are you sure?',
            text: "You won't be able to delete this chat message!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger m-l-10',
            confirmButtonText: 'Yes, delete it!'
        }).then(function () {
            if (messageId) {
                $.ajax({
                    headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                    url: deleteUrl,
                    type: "delete",
                    dataType: 'json',
                    success: function (data) {
                        swal(
                            'Status Close!',
                            data.msg,
                            'success'
                        )
                        toastr.success(data.msg, App_name_global);
                        getChatMessage();
                    },
                    error: function (error) {
                        toastr.error(error.responseJSON.msg, App_name_global);
                    }
                });
            }
        });
    }
}

function sendReply() {
    var reply_msg = $('#reply_message').val();
    var support_id = $('#support_id').val();
    if (reply_msg != '' && support_id != '') {
        $.ajax({
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: "{{route('admin.support_chat.store')}}",
            type: "post",
            data: { 'message': reply_msg, 'support_id': support_id },
            dataType: 'json',
            success: function (data) {
                toastr.success(data.msg, App_name_global);
                $('#reply_message').val('');
                getChatMessage();
            },
            error: function (error) {
                toastr.error(error.responseJSON.msg, App_name_global);
            }
        });
    }
}


</script>
@endsection