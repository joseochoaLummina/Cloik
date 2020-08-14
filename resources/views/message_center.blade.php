@extends('layouts.app')
@section('content') 
<!-- Header start --> 
@include('includes.header') 
<!-- Header end --> 
<!-- Inner Page Title start --> 
@include('includes.inner_page_title', ['page_title'=>__('Language Test')]) 
<!-- Inner Page Title end -->
<div class="modal fade" id="deleteMsgModal" tabindex="-1" role="dialog" aria-labelledby="deleteMsgModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteMsgModalLabel">{{__('Delete Message')}}</h5>
      </div>
      <div class="modal-body">
        {{__('Sure you want to delete this message?')}}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{_('Cancel')}}</button>
        <button type="button" class="btn btn-danger" onclick="deleteMessage()">{{__('Delete')}}</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="replyMsgModal" tabindex="-1" role="dialog" aria-labelledby="replyMsgModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="replyMsgModalLabel">{{__('Reply Message')}}</h5>
      </div>
      <div class="modal-body">
        <textarea id="msgReplyText" rows="8"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{_('Cancel')}}</button>
        <button type="button" class="btn btn-success" onclick="replyMessage()">{{__('Send')}}</button>
      </div>
    </div>
  </div>
</div>

<div class="listpgWraper">
    <div class="container">
        <div class="row">
            @include('includes.user_dashboard_menu')

            <div class="col-md-9 col-sm-8"> 
                <div class="row">
                    <div class="col-md-12">
                        <div class="userccount">
                            <div class="formpanel"> @include('flash::message') 
                            <div id="messages-panel">
                                <div id="messages-details">
                                    <div id="details-panel">
                                        <label id="titleMessages">{{__('No messages selected')}}</label>
                                        <div></div>
                                        <button class="btn fa fa-reply" style="background-color: #d9534f; border-color: #d9534f " id="btn-reply" data-toggle="modal" data-target="#replyMsgModal" onclick="limpiarTextArea()"></button>
                                        <button class="btn fa fa-trash"  style="background-color: #d9534f; border-color: #d9534f " id="btn-trash" data-toggle="modal" data-target="#deleteMsgModal"></button>
                                    </div>
                                    <div id="messagesContent"></div>
                                </div>
                                <div id="messages-bar">
                                    <div id="msg-bar-buttons">
                                        @if(Request::get('messageType') == 1)
                                            <button type="button" class="btn fa fa-bell" style="background-color: green;" data-toggle="tooltip" data-placement="bottom" title="{{__('Notifications')}}" onclick="changeMessageType(1)"></button>
                                        @else
                                            <button type="button" class="btn fa fa-bell" data-toggle="tooltip" data-placement="bottom" title="{{__('Notifications')}}" onclick="changeMessageType(1)"></button>
                                        @endif
                                        
                                        @if(Request::get('messageType') == 2)
                                            <button type="button" class="btn fa fa-envelope-open" style="background-color: green;" data-toggle="tooltip" data-placement="bottom" title="{{__('Invitations')}}" onclick="changeMessageType(2)"></button>
                                        @else
                                            <button type="button" class="btn fa fa-envelope-open" data-toggle="tooltip" data-placement="bottom" title="{{__('Invitations')}}" onclick="changeMessageType(2)"></button>
                                        @endif

                                        @if(Request::get('messageType') == 0)
                                            <button type="button" class="btn fa fa-comments" style="background-color: green;" data-toggle="tooltip" data-placement="bottom" title="{{__('Messages')}}" onclick="changeMessageType(0)"></button>
                                        @else
                                            <button type="button" class="btn fa fa-comments" data-toggle="tooltip" data-placement="bottom" title="{{__('Messages')}}" onclick="changeMessageType(0)"></button>
                                        @endif

                                        @if(Request::get('messageType') == 3)
                                            <button type="button" class="btn fa fa-certificate" style="background-color: green;" data-toggle="tooltip" data-placement="bottom" title="{{__('Alerts')}}" onclick="changeMessageType(3)"></button>
                                        @else
                                            <button type="button" class="btn fa fa-certificate" data-toggle="tooltip" data-placement="bottom" title="{{__('Alerts')}}" onclick="changeMessageType(3)"></button>
                                        @endif
                                    </div>
                                    <div id="messages">
                                    @foreach($messages as $message)
                                        @if($message->state == 0)
                                            <div id="message{{$message->id}}" class="itemMessages-no-read itemMessages" onclick="viewMessage({{json_encode($message, TRUE)}})">
                                            @else
                                            <div id="message{{$message->id}}" class="itemMessages-read itemMessages" onclick="viewMessage({{json_encode($message, TRUE)}})">     
                                        @endif
                                                <div>{{__('From: ')}} {{$message->emisor}}</div>
                                                @if($message->type != 2)
                                                    <div class="limit-messages">{{$message->message}}</div>
                                                @else
                                                <div class="limit-messages">{{__('Apply to')}} {!! strtoupper(str_replace('-',' ', str_replace('https://www.cloik.com/job/','',$message->message))) !!}</div>
                                                @endif
                                            </div>
                                    @endforeach
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
</div>
@include('includes.footer')
@endsection
@push('styles')
<style type="text/css">
    .userccount p{ text-align:left !important;}

    .userccount {
        width: 100%;
        position: absolute;
    }

    .formpanel {
        float: left;
        width: 100%;
        margin-bottom: 20px;
        min-height: 400px;
    }

    #messages-panel {
        width: 100%;
        margin: 0;
        display: grid;
        grid-template-columns: 70% 30%;
        min-height: 380px;
        height: 380px !important;
        max-height: 380px !important;
        top: 0;
        bottom: 0;
    }

    #messages-details {
        width: 100%;
    }

    #details-panel {
        margin: 0 1rem 2rem 1rem;
        height: 10%;
        display: grid;
        grid-template-columns: 5fr 3fr 1fr 1fr;
        column-gap: 3%;
    }

    #messagesContent {
        margin: 1rem;
        border-radius: 5px;
        border: solid 1px #80808057;
        height: 20rem;
        overflow-y: auto;
        padding: 1rem;
        scrollbar-color: #03236e #9878b7;
        scrollbar-width: thin;
    }

    #messages-bar {
        width: 100%;
    }

    #msg-bar-buttons {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 1fr;        
        column-gap: 3%;
        
    }

    #messages {
        margin: 1rem 0;
        margin-top: 2rem;
        border: solid 1px #80808057;
        height: 20rem;
        overflow-x: hidden;
        overflow-y: auto;
        scrollbar-color: #03236e #fff;
        scrollbar-width: thin;
    }

    .itemMessages {        
        margin: 0.1rem;
        border: solid 1px #aeacac;
        padding: 0.5rem;
        border-radius: 3px;
    }

    .itemMessages:hover {
        cursor: pointer;
        background-color: #2f2e2e;
        color: white;
    }

    .itemMessages-read {
        background-color: #fff;
    }

    .itemMessages-no-read {
        background-color: #626262;
        color: white;
    }

    .itemMessages-selected {
        background-color: #03236e;
        color: white;
    }

    .itemMessages-selected:hover {
        background-color: #011031;
        color: white;
    }

    .limit-messages {
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    #format-notification {
        display: grid;
        grid-template-columns: 100%;
    }

    #format-title-notification {
        width: 100%;
        background-color: #03236e;
        color: white;
        text-align: center;
        font-size: x-large;
        padding: 0.5rem;
    }

    #format-message-notification {
        padding: 0.5rem;
        font-size: small;
        font-weight: 300;
    }

    #format-message-notification b {
        font-weight: bold;
        font-style: italic;
    }

    #format-btn-notification {
        display: grid;
        grid-template-columns: 1fr 1fr;
        padding: 1rem 2rem 0.5rem 2rem;
        grid-gap: 0.5rem;
    }

    #format-btn-notification a{
        width: 80%;
        padding: 0.3rem;
        font-size: 12px;
        font-weight: 500;
    }

    .modal-header {
        display: flex;
        justify-content: center;
    }

    #msgReplyText {
        width: 100%;
        resize: none;
        border: solid 1px #ccc;
        border-radius: 5px;
        padding: 1rem;
    }
</style>
@endpush
@push('scripts')
@include('includes.immediate_available_btn')
<script>
    var selectedMessageId;
    var messageId;
    var mytype = 0;
    var btnReply = document.getElementById('btn-reply');
    var btnTrash = document.getElementById('btn-trash');
    btnReply.style.display = "none";
    btnTrash.style.display = "none";

    function limpiarTextArea() {
        document.getElementById('msgReplyText').value = "";
    }

    function deleteMessage() {
        $.ajax({
            type: 'GET',
            url: "{{ route('delete.message') }}",
            data: {id: messageId},
            success: function(data) {
                if (data == 1) {
                    window.location.reload(false);
                }
            }
        });
    }

    function replyMessage() {
        var msgText = document.getElementById('msgReplyText').value;

        $.ajax({
            type: 'GET',
            url: "{{ route('reply.message') }}",
            data: {id: messageId, msg: msgText},
            success: function(data) {
                if (data.status == 'ok') {
                    window.location.reload(false);
                }
            }
        });
    }

    function changeMessageType(changetype) {
        $.ajax({
            type: 'GET',
            url: "{{ route('my.center.message') }}",
            data: {messageType: changetype},
            success: function(data) {
                mytype = changetype;
                window.location.href="center-message?messageType="+changetype;
            }
        });
        
    }

    function acceptMeeting(meeting_id,message_id) {
        $.ajax({
            type: 'GET',
            url: "{{route('accept.meeting')}}",
            data: {
                id_meeting: meeting_id , 
                id_message:message_id
            },
            success: function() {
                window.location.reload();
            }
        });
    }

    function denyMeeting(meeting_id,message_id) {
        $.ajax({
            type: 'GET',
            url: "{{route('deny.meeting')}}",
            data: {
                id_meeting: meeting_id , 
                id_message:message_id
            },
            success: function() {
                window.location.reload();
            }
        });
    }

    function viewMessage(message) {
        messageId = message['id'];

        $('#titleMessages').html("{{__('Message from ')}}" + message['emisor']);
        if (selectedMessageId !== undefined) {
            $(selectedMessageId).removeClass("itemMessages-selected");
        }
        
        selectedMessageId = "#message"+message['id'];
        $.ajax({
            type: 'GET',
            url: "{{ route('change.to.read') }}",
            data: {typeMessage: message['type'], id: message['id'], selected: selectedMessageId},
            success: function(data) {
                newHtml = "";
                data.forEach(msg => {
                    if ("#message"+msg['id'] == selectedMessageId) {
                        addClass1 = " itemMessages-selected";
                    }
                    else {
                        addClass1 = "";
                    }

                    if (msg['state'] == 0) {
                        newHtml +=  '<div id="message' + msg['id'] +'" class="itemMessages-no-read itemMessages'+ addClass1 +'" onclick=\'(viewMessage('+ msg['json']+'))\'>';
                    }
                    else {
                        newHtml +=  '<div id="message' + msg['id'] +'" class="itemMessages-read itemMessages'+ addClass1 +'" onclick=\'(viewMessage('+msg['json']+'))\'>';
                    }

                    if(msg['type']!=2) {
                        newHtml +=      '<div>{{__("From: ")}} '+ msg['emisor'] +' </div>'+
                                        '<div class="limit-messages"> ' + msg['message'] +'</div>' +
                                    '</div>';
                    }
                    else {
                        var mensaje = msg['message'].replace('https://www.cloik.com/job/','').replace('-',' ').toUpperCase();
                        newHtml +=      '<div>{{__("From: ")}} '+ msg['emisor'] +' </div>'+
                                        '<div class="limit-messages">{{__("Apply to")}} '+mensaje+'</div>'+
                                    '</div>';
                    }
                });
                $('#messages').html(newHtml);
            }
        });
        $(selectedMessageId).addClass("itemMessages-selected");
        
        if(message['type'] == 0) {
            btnReply.style.display = "block";
        }
        else {
            btnReply.style.display = "none";
        }
        btnTrash.style.display = "block";

        if(message['type'] == 1) {
            var msgHtml = `<div id="format-notification">
                                <div id="format-title-notification">{{__('Cloik Notification')}}</div>
                                <div id="format-message-notification">`+ message['message'] + `</div>`;
                                if (message['accepted'] == 0) {
                                    msgHtml += `<div id="format-btn-notification">
                                                    <a class="btn" href="javascript:acceptMeeting(${message['meeting_id']},${messageId});">{{__('Accept Meeting')}}</a>
                                                    <a class="btn" href="javascript:denyMeeting(${message['meeting_id']},${messageId});">{{__('Deny Meeting')}}</a>
                                                </div>`;
                                }
                                else if (message['accepted'] == 1){
                                    msgHtml +=`<div style="padding: 2rem 0rem; text-align: center; font-weigth: 300;">{{__('This meeting has already been accepted')}}</div>`;
                                }
                                else if (message['accepted'] == 2){
                                    msgHtml +=`<div style="padding: 2rem 0rem; text-align: center; font-weigth: 300;">{{__('This meeting has already been deny/canceled')}}</div>`;
                                }
                            msgHtml += `</div>`;
            $("#messagesContent").html(msgHtml);
        }
        else if(message['type'] == 2) {
            var msgHtml = `<div id="format-notification">
                                <div id="format-title-notification">{{__('Cloik Invitation')}}</div>
                                <div id="format-message-notification">{{__('We are pleased to notify you that you have been invited to submit your job application ')}}<b>`+ message['message'].replace('https://www.cloik.com/job/', '').replace('-',' ').toUpperCase() + `</b></div>`;
                msgHtml += `<div id="format-btn-notification">
                                <a class="btn" href="`+ message['message']+`">{{__('See job details')}}</a>
                            </div>`;
                msgHtml += `</div>`;
            $("#messagesContent").html(msgHtml);
        }
        else {
            $("#messagesContent").html(message['message']);
        }
    }
</script>
@endpush