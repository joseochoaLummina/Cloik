@extends('layouts.app')
@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end -->
<!-- Inner Page Title end -->
@include('includes.inner_page_title', ['page_title'=>__('Message Center')]) 

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
    <div class="container containerMSG">@include('flash::message')
        <div class="row"> @include('includes.company_dashboard_menu')
            <div class="col-md-9 col-sm-8">
                <div class="myads">
                    <!-- <h3>{{__('Message Center')}}</h3> -->
                    <div class="col-md-12 col-sm-8">
                        <div class="userccount">
                            <div class="formpanel">
                                <div id="head-msg-panel">
                                    <div></div>
                                    <div>
                                    @if(Request::get('messageType') == 0)
                                        {{__('Received messages')}}
                                    @elseif(Request::get('messageType') == 1)  
                                        {{__('Notifications')}}                                  
                                    @elseif(Request::get('messageType') == 3)  
                                        {{__('System alerts')}}                                  
                                    @elseif(Request::get('messageType') == 100)
                                        {{__('Outbox')}}
                                    @endif
                                    </div>
                                </div>
                                <div id="messages-panel">
                                    <div id="messages-bar">
                                        <div id="msg-bar-buttons">
                                            @if(Request::get('messageType') == 1)
                                            <button type="button" style="background-color: green;" data-toggle="tooltip" data-placement="bottom" onclick="changeMessageType(1)"><i class="fa fa-bell"></i>{{__('Notifications')}}</button>
                                            @else
                                            <button type="button" data-toggle="tooltip" data-placement="bottom" onclick="changeMessageType(1)"><i class="fa fa-bell"></i>{{__('Notifications')}}</button>
                                            @endif

                                            @if(Request::get('messageType') == 0)
                                            <button type="button" style="background-color: green;" data-toggle="tooltip" data-placement="bottom" onclick="changeMessageType(0)"><i class="fa fa-inbox"></i>{{__('Messages')}}</button>
                                            @else
                                            <button type="button" data-toggle="tooltip" data-placement="bottom" onclick="changeMessageType(0)"><i class="fa fa-inbox"></i>{{__('Messages')}}</button>
                                            @endif

                                            @if(Request::get('messageType') == 3)
                                            <button type="button" style="background-color: green;" data-toggle="tooltip" data-placement="bottom" onclick="changeMessageType(3)"><i class="fa fa-certificate"></i>{{__('Alerts')}}</button>
                                            @else
                                            <button type="button" data-toggle="tooltip" data-placement="bottom" onclick="changeMessageType(3)"><i class="fa fa-certificate"></i>{{__('Alerts')}}</button>
                                            @endif

                                            @if(Request::get('messageType') == 100)
                                            <button type="button" style="background-color: green;" data-toggle="tooltip" data-placement="bottom" onclick="changeMessageType(100)"><i class="fa fa-paper-plane-o"></i>{{__('Sent')}}</button>
                                            @else
                                            <button type="button" data-toggle="tooltip" data-placement="bottom" onclick="changeMessageType(100)"><i class="fa fa-paper-plane-o"></i>{{__('Sent')}}</button>
                                            @endif
                                        </div>
                                        <div id="buzon">{{__('Box')}}</div>
                                        <div id="messages">
                                            @if(Request::get('messageType') != 100)
                                                @foreach($messages as $message)
                                                    @if($message->state == 0)
                                                    <div id="message{{$message->id}}" class="itemMessages-no-read itemMessages" onclick="viewMessage({{json_encode($message, TRUE)}})">
                                                        <div>{{__('From: ')}} {{$message->emisor}}</div>
                                                        <div class="limit-messages">{{$message->message}}</div>
                                                    </div>
                                                    @else
                                                    <div id="message{{$message->id}}" class="itemMessages-read itemMessages" onclick="viewMessage({{json_encode($message, TRUE)}})">
                                                        <div>{{__('From: ')}} {{$message->emisor}}</div>
                                                        <div class="limit-messages">{{$message->message}}</div>
                                                    </div>
                                                    @endif
                                                @endforeach
                                            @else
                                                @foreach($messages as $message)
                                                    <div id="message{{$message->id}}" class="itemMessages-read itemMessages" onclick="viewMessage({{json_encode($message, TRUE)}})">
                                                        <div>{{__('From: ')}} {{$message->emisor}}</div>
                                                        <div class="limit-messages">{{$message->message}}</div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                    <div id="messages-details">
                                        <div></div>
                                        <div id="details-panel">
                                            <label id="titleMessages">{{__('No messages selected')}}</label>
                                            <div></div>
                                            <button class="btn fa fa-reply options-btn" id="btn-reply" data-toggle="modal" data-target="#replyMsgModal" onclick="limpiarTextArea()"></button>
                                            <button class="btn fa fa-trash options-btn" id="btn-trash" data-toggle="modal" data-target="#deleteMsgModal"></button>
                                        </div>
                                        <div id="messagesContent"></div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>@include('includes.footer')
@push('styles')
<style type="text/css">
    .containerMSG {
        display: grid;
        height: auto;
        padding: 0 3rem;
        grid-template-columns: 100%;
    }

    .containerMSG div {
        height: 100%;
    }

    .containerMSG::before, .containerMSG::after {
        display: none;
    }
    .userccount p {
        text-align: left !important;
    }

    .userccount {
        width: 100%;
        position: absolute;
        padding: 0;
        /* background-color: #333333;
        color: white; */
    }

    .formpanel {
        float: left;
        width: 100%;
        margin: 0;
        min-height: 400px;
        display: grid;
        grid-template-rows: 10% 90%;
    }

    #head-msg-panel {
        display: grid;
        grid-template-columns: 30% 70%;
        grid-template-rows: 100%;
    }

    #head-msg-panel > div:first-child {
        background-color: #2D83BC;
    }

    #head-msg-panel > div:last-child {
        background-color: #54ABF9;
        display: flex;
        justify-content: flex-end;
        color: white;
        font-weight: bold;
        font-size: xx-large;
        align-items: center;
        padding: 1rem;
    }


    #messages-panel {
        width: 100%;
        margin: 0;
        display: grid;
        grid-template-columns: 30% 70%;
        grid-template-rows: 100%;
        min-height: 100%;
        height: 100% !important;
        top: 0;
        bottom: 0;
    }

    #messages-details {
        width: 100%;
        display: grid;
        grid-template-rows: 5% 10% 85%;
        height: 100%;
    }

    #details-panel {
        margin: 0 1rem;
        display: grid;
        grid-template-columns: 5fr 7fr 0.5fr 0.5fr 0.5fr;
        column-gap: 3%;
        border: solid 1px #80808070;
        font-weight: bold;
    }

    #details-panel > label:first-child {
        display: flex;
        align-items: center;
        padding: 0 1rem;
    }

    #messagesContent {
        margin: 1rem;
        border-radius: 5px;
        border: solid 1px #80808057;
        height: Calc(100% - 2rem);
        overflow-y: auto;
        padding: 1rem;
        scrollbar-color: #03236e #9878b7;
        scrollbar-width: thin;
    }

    #messages-bar {
        width: 100%;
        height: 100%;
        display: grid;
        grid-template-columns: 100%;
        grid-template-rows: 20% 5% 75%;
        background-color: #B5BAC7;
    }

    .options-btn {
        background-color: transparent !important;
        border: none !important;
        color: #2D83BC !important;
    }

    #msg-bar-buttons {
        display: grid;
        grid-template-columns: 100%;
        grid-template-rows: 25%;
        grid-auto-rows: 25%;
    }

    #msg-bar-buttons button {
        border-radius: 0;
        padding: 0.5rem;
        text-align: left;
        background-color: #80808070;
        color: white;
        border: none;
    }

    #msg-bar-buttons button:hover {
        background-color: #353535;        
        /* border: solid 1px #ffffff50; */
    }

    #msg-bar-buttons button i {
        margin: 0 10px;
    }

    #buzon {
        background-color: aliceblue;
        border-top: solid 3px #2D83BC;
        display: flex;
        align-items: center;
        padding: 0 1rem;
        font-weight: bold;
    }

    #messages {
        margin: 0;
        border: solid 1px #80808045;
        height: 100%;
        overflow-x: hidden;
        overflow-y: auto;
        scrollbar-color: #03236e #9878b7;
        scrollbar-width: thin;
        display: grid;
        grid-template-columns: 100%;
        grid-auto-rows: 50px;
    }

    .itemMessages {
        border-bottom: solid 1px #aeacac;
        padding: 0.5rem;
        display: grid;
        color: black;
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
        background-color: #2D83BC;
        color: white;
    }

    .itemMessages-selected:hover {
        background-color: #54ABF9;
        color: white;
    }

    .limit-messages {
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .listpgWraper {
        min-height: 550px;
    }


    #format-notification {
        display: grid;
        grid-template-columns: 100%;
        grid-template-rows: 10% 90%;
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

    #format-btn-notification a {
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
            data: {
                id: messageId
            },
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
            data: {
                id: messageId,
                msg: msgText
            },
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
            url: "{{ route('company.center.message') }}",
            data: {
                messageType: changetype
            },
            success: function(data) {
                mytype = changetype;
                window.location.href = "company-center-message?messageType=" + changetype;
            }
        });
    }

    function viewMessage(message) {
        messageId = message['id'];

        $('#titleMessages').html(message['emisor']);
        if (selectedMessageId !== undefined) {
            $(selectedMessageId).removeClass("itemMessages-selected");
        }
        //al obtener el id hay que mandar a cambiar el estado y recobrar los mensajes para volver a crear la lista

        selectedMessageId = "#message" + message['id'];
        
        var urlMSGType = {!! json_encode(Request::get('messageType')) !!};
        if (urlMSGType != 100 ) {
            $.ajax({
                type: 'GET',
                url: "{{ route('change.to.read') }}",
                data: {
                    typeMessage: message['type'],
                    id: message['id'],
                    selected: selectedMessageId
                },
                success: function(data) {
                    newHtml = "";
                    data.forEach(msg => {
                        if ("#message" + msg['id'] == selectedMessageId) {
                            addClass1 = " itemMessages-selected";
                        } else {
                            addClass1 = "";
                        }

                        if (msg['state'] == 0) {
                            newHtml += '<div id="message' + msg['id'] + '" class="itemMessages-no-read itemMessages' + addClass1 + '" onclick=\'(viewMessage(' + msg['json'] + '))\'>' +
                                '<div>{{__("From: ")}} ' + msg['emisor'] + ' </div>' +
                                '<div class="limit-messages"> ' + msg['message'] + '</div>' +
                                '</div>';
                        } else {
                            newHtml += '<div id="message' + msg['id'] + '" class="itemMessages-read itemMessages' + addClass1 + '" onclick=\'(viewMessage(' + msg['json'] + '))\'>' +
                                '<div>{{__("From: ")}} ' + msg['emisor'] + ' </div>' +
                                '<div class="limit-messages"> ' + msg['message'] + '</div>' +
                                '</div>';
                        }
                    });
                    $('#messages').html(newHtml);
                }
            });

            if (message['type'] == 0) {
                btnReply.style.display = "block";
            } else {
                btnReply.style.display = "none";
            }
        }
        $(selectedMessageId).addClass("itemMessages-selected");

        btnTrash.style.display = "block";

        if (message['type'] == 1) {
            var msgHtml = `<div id="format-notification">
                                <div id="format-title-notification">{{__('Cloik Notification')}}</div>
                                <div id="format-message-notification">` + message['message'] + `</div>`;
            msgHtml += `</div>`;
            $("#messagesContent").html(msgHtml);
        } else {
            $("#messagesContent").html(message['message']);
        }
    }
</script>
@endpush