@extends('layouts.app')
@section('content') 
<!-- Header start --> 
@include('includes.header') 
<!-- Header end --> 
<!-- Inner Page Title start --> 
@include('includes.inner_page_title', ['page_title'=>__('My Meetings')]) 
<!-- Inner Page Title end -->
@push('styles')
    <link href="{{ asset('css/meeting.css') }}" rel="stylesheet">
    <link href="{{ asset('css/video.css') }}" rel="stylesheet">
@endpush
<div class="listpgWraper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="room-id-input-div" class="formrow {!! APFrmErrHelp::hasError($errors, 'room-id-input') !!}"> {!! Form::hidden('room-id-input', $room, array('class'=>'form-control','id'=>'room-id-input')) !!}
                    {!! APFrmErrHelp::showErrors($errors, 'room-id-input') !!} </div>
            </div>
        </div>
        <div class="row">
            <div class="video-call" id="videoEntrevista">
                
            </div>             
        </div>
    </div>
</div>
@include('includes.footer')
@endsection
@push('scripts')
    @include('includes.immediate_available_btn')
    <script>
    window.onload = function() {
        var dominio = "meet.jit.si";
        var room = {!! json_encode($room) !!};
                var opciones = {
                    roomName: room,
                    parentNode: document.querySelector('#videoEntrevista'),
                    configOverwrite: {
                        constraints: {
                            video: {
                                height: {
                                    ideal: 720,
                                    max: 720,
                                    min: 240
                                }
                            }
                        },
                        useNicks: false,
                        requireDisplayName: false,
                    },
                    interfaceConfigOverwrite: {
                        DEFAULT_BACKGROUND: '#000',
                        filmStripOnly: false,
                        SHOW_JITSI_WATERMARK: false,
                        SHOW_WATERMARK_FOR_GUESTS: false,
                        SHOW_BRAND_WATERMARK: false,
                        DISPLAY_WELCOME_PAGE_CONTENT: false,
                        DEFAULT_LOCAL_DISPLAY_NAME: 'Hola',
                        TOOLBAR_BUTTONS: ['microphone', 'camera', 'fullscreen', 'hangup'],
                        VERTICAL_FILMSTRIP: false,
                        LOCAL_THUMBNAIL_RATIO: 4 / 3,
                        VIDEO_QUALITY_LABEL_DISABLED: true,
                        GENERATE_ROOMNAMES_ON_WELCOME_PAGE: true,
                        SHOW_POWERED_BY: false,
                        DISPLAY_WELCOME_PAGE_TOOLBAR_ADDITIONAL_CONTENT: false,
                        INVITATION_POWERED_BY: false,
                        CLOSE_PAGE_GUEST_HINT: true,
                        SHOW_PROMOTIONAL_CLOSE_PAGE: false,
                        DISABLE_JOIN_LEAVE_NOTIFICATIONS: false,
                    }
                    }

                    this.api = new JitsiMeetExternalAPI(dominio, opciones);
    };
    </script>
@endpush