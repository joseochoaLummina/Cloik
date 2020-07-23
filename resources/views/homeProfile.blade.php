@extends('layouts.app')
@section('content') 
<!-- Header start --> 
@include('includes.header_verifi') 
<!-- Header end --> 
@push('styles')
    <link href="{{ asset('css/video.css') }}" rel="stylesheet">
@endpush
@push('scripts')
    <script src="https://sdk.amazonaws.com/js/aws-sdk-2.528.0.min.js"></script>
@endpush

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
<!-- Inner Page Title start --> 
@include('includes.inner_page_title_profile', ['page_title'=>__('Dashboard')]) 
<!-- Inner Page Title end -->
<div class="listpgWraper">
    <div class="alert alert-danger alertWebcam" role="alert" id="alertWebcam">
        {{__("It does not record, please, verify you allow your browser to use your camera and microphone, to know how, please click ")}}
        <a href="javascript:webCam()" class="alert-link">{{__('HERE')}}</a>
    </div>
    <div class="container">@include('flash::message')
            <h1>{{__('Complete your profile')}}</h1>       
            <div id="firstVideoRecord" style="height: 600px !important;">
                <div id="newVideoPre" style="position: relative; width: 100%; height: 100%; display:flex; justify-content:center; flex-wrap: wrap;">
                    <div style="width: 100%; height: 5%">
                        <div class="videoModal">
                            <!-- <div></div> -->
                            <h4 class="modal-title" style="color: white">{{__('Record Your Video Presentation')}}</h4>
                            <div class="videoModal-time">
                                <label id="timer"></label>
                                <label style="color: white"> / 01:00</label>
                            </div>
                            <!-- <div></div> -->
                        </div>
                    </div>
                    <div class="recom">
                        <div class="header-recom">{{__('Recommendations')}}</div>
                        <div class="body-recom">
                            @if ($recommendation)
                                <ul>
                                    @foreach (explode("(/&/)", $recommendation) as $item)
                                        <li><h6>{{$item}}</h6></li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                    <div class="videoPreviewContainer">
                        <div id="videoUpload">
                            <div class="spin"></div>                    
                            <span class="text-spin">{{__('Uploading Video')}}...</span>
                        </div>
                        <div id="conteoRegresivo" style="width: 70% !important;">OK</div>
                        <video muted="muted" id="videoPreview" style="height: 100%;"></video>
                    </div>
                    <div class="hover-row2">
                        <div class="col-md-8 col-sm-8 dropsContent">
                            <div class="my-button" id="btnComenzarGrabacion">
                                <div class="place-button btn-record">
                                    <!-- <i class="fa fa-play"></i> -->
                                    {{__('Start Recording')}}
                                </div>
                            </div>
                            <div class="my-button" id="btnDetenerGrabacion">
                                <div class="place-button btn-stop-record">
                                    <!-- <i class="fa fa-stop"></i> -->
                                    {{__('Stop Recording')}}
                                </div>
                            </div>
                            <div class="my-button" id="btnGuardarGrabacion">
                                <div class="place-button btn-record">
                                    <!-- <i class="fa fa-save"></i> -->
                                    {{__('Save Video')}}
                                </div>
                            </div>
                            <div class="my-button" id="btnReiniciarGrabacion">
                                <div class="place-button btn-stop-record">
                                    <!-- <i class="fa fa-undo"></i> -->
                                    {{__('Repeat Recording')}}
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
{{-- start css --}}
@push('styles')
<style>
    .videoModal{
        width:100%;
        display: grid; 
        grid-template-columns: 50% 50%;
        align-items: center;
        justify-items: center;
    }

    .videoModal-time{
        grid-column: -2/-1;
        justify-self: end;
        padding: 0 5% 0 0;
    }

    .modal-title{
        justify-self: start;
        padding: 0 0 0 7%;
    }

    .alertWebcam{
        display:none; 
        position:relative; 
        bottom:13px;
    }

    .recom {
        display: flex;
        justify-content: center;
        background-color: black;
        flex-wrap: wrap;
    }

    .header-recom {
        width: 90%;
        height: 8%;
        background-image: linear-gradient(to bottom, #607d8b, #052d46);
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: bold;
    }

    .body-recom {
        background-color: white;
        width: 90%;
        height: 92%;
    }

    .hover-row2 {
        position: relative;
        margin-top: -5rem;
        width: 40%;
        margin-left: 30%;
        /* margin-right: 10% !important; */
        z-index: 10;
        opacity: 0.3;
        transition-duration: 0.5s;
        transition-delay: 0.2s;
    }

    
    .hover-row2:hover {
        opacity: 1;
        transition-duration: 0.5s;
        transition-delay: 0.2s;
    }
    .userccount {
        border: 0px !important;
    }

    .formpanel::-webkit-scrollbar {
        width: 5px !important;
    }

    .userccount p
    { 
        text-align:left !important;
    }

    /* Style the form */
    #regForm {
    background-color: #ffffff;
    margin: 30px auto;
    width: 100%;
    min-width: 300px;
    }

    /* Style the input fields */
    input {
    padding: 10px;
    width: 100%;
    font-size: 17px;
    font-family: Raleway;
    border: 1px solid #aaaaaa;
    }

    /* Mark input boxes that gets an error on validation: */
    input.invalid {
    background-color: #ffdddd;
    }

    /* Hide all steps by default: */
    .tab {
    display: none;
    }

    /* Make circles that indicate the steps of the form: */
    .step {
    height: 15px;
    width: 15px;
    margin: 0 2px;
    background-color: #bbbbbb;
    border: none;
    border-radius: 50%;
    display: inline-block;
    opacity: 0.5;
    }

    /* Mark the active step: */
    .step.active {
    opacity: 1;
    }

    /* Mark the steps that are finished and valid: */
    .step.finish {
    background-color: #4CAF50;
    }
</style>
@endpush
{{-- end css --}}

{{-- start script --}}
@push('scripts')
<script>
    $('.video').on('mouseenter',function toggleControls() {
                this.setAttribute("controls", "controls");
            });
    $('.video').on('mouseleave',function toggleControls() {
        this.removeAttribute("controls");
    });
</script>
{{-- end script --}}
@include('video_recorder2_js')
@include('includes.immediate_available_btn')
@endpush