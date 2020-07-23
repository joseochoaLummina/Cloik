@extends('layouts.app')
@section('content') 
<!-- Header start --> 
@include('includes.header') 
<!-- Header end --> 
<!-- Inner Page Title start --> 
@push('styles')
    <link href="{{ asset('css/video.css') }}" rel="stylesheet">
@endpush
@push('scripts')
    <script src="https://sdk.amazonaws.com/js/aws-sdk-2.528.0.min.js"></script>
@endpush
@include('includes.inner_page_title', ['page_title'=>__('Job Details')]) 
<!-- Inner Page Title end -->
<div class="modal" id="notification" style="display:none">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="icon">
                <i class="fa fa-check"></i>
            </div>
            <div id="modal-message">{{__('Video saved successfully')}}</div>
        </div>
    </div>
</div>

<div class="modal" id="setVideoName" style="display:none">
    <div class="modal-dialog">
        <div class="modal-content">
            <div id="modal-message">{{__('Please enter video name')}}</div>
            <div>
                <input type="text" required placeholder="{{__('Please enter video name')}}" id="videoName">
            </div>
            <div>
                <button id="saveVideoName">OK</button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="newVideo">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
    
        <!-- Modal Header -->
        <div class="modal-header">
            <div style="width:90%; display: grid; grid-template-columns: 85% 15%;">
                <h4 class="modal-title">{{__('Record New Video')}}</h4>
                <div>
                    <label id="timer"></label>
                    <label style="color: white"> / 01:00</label>
                </div>
            </div>
            <button type="button" class="close close-modal" id="close-modal-new-video" data-dismiss="modal">&times;</button>
        </div>
    
        <!-- Modal body -->
        <div class="modal-body">
            <div class="recom">
                <div class="header-recom">{{__('Recommendations')}}</div>
                <div class="body-recom">
                   <!-- Agregar recomendaciones-->
                </div>
            </div>
            <div class="videoPreviewContainer">
                <div id="videoUpload">
                    <div class="spin"></div>                    
                    <span class="text-spin">{{__('Uploading Video')}}...</span>
                </div>
                <div id="conteoRegresivo">OK</div>
                <video muted="muted" id="videoPreview"></video>
            </div>
            <div class="hover-row">
                <div class="col-md-8 col-sm-8 dropsContent">
                    <div class="my-button" id="btnComenzarGrabacion">
                        <div class="place-button btn-record">
                            {{__('Start Recording')}}
                        </div>
                    </div>
                    <div class="my-button" id="btnDetenerGrabacion">
                        <div class="place-button btn-stop-record">
                            {{__('Stop Recording')}}
                        </div>
                    </div>
                    <div class="my-button" id="btnGuardarGrabacion">
                        <div class="place-button btn-record">
                            {{__('Save Video')}}
                        </div>
                    </div>
                    <div class="my-button" id="btnReiniciarGrabacion">
                        <div class="place-button btn-stop-record">
                            {{__('Repeat Recording')}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Modal footer -->
        <div class="modal-footer">
        </div>
    
        </div>
    </div>
</div>
@push('scripts')
    @include('job.video_recorder_js')
@endpush
<div class="listpgWraper">
    <div class="container">
        <div class="row">
            
            @if (Auth::guard('company')->check())
            @include('includes.company_dashboard_menu')
            @elseif(Auth::guard('recruiter')->check())
            @include('includes.recruiter_dashboard_menu')
            @endif
            <div class="col-md-9 col-sm-8"> 
                <div class="row">
                    <div class="col-md-12">
                        <div class="userccount">
                            <div class="formpanel"> @include('flash::message') 
                                <!-- Personal Information -->
                                @include('job.inc.job')
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
</style>
@endpush