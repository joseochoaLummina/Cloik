@extends('layouts.app')
@section('content') 
<!-- Header start --> 
@include('includes.header') 
<!-- Header end --> 
<!-- Inner Page Title start --> 
@include('includes.inner_page_title', ['page_title'=>__('My Videos')]) 
<!-- Inner Page Title end -->
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

<div class="modal" id="setVideoName" style="display:none" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div id="modal-message">{{__('Please enter video name')}}</div>
            <div>
                <input type="text" required placeholder="{{__('Please enter video name')}}" id="videoName">
            </div>
            <div>
                <button id="saveVideoName">OK</button>
                <button id="closeVideoName" >Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="newVideo">
    <div class="modal-dialog">
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
            <div id="footer-timer-parent">
                <label id="footer-timer"></label>
                <label style="color: white"> / 01:00</label>
            </div>
        </div>
    
        </div>
    </div>
</div>
@push('scripts')
    @include('user.video_recorder_js')
@endpush
<div class="listpgWraper">
    <div class="alert alert-danger alertWebcam" role="alert" id="alertWebcam">
        {{__("It does not record, please, verify you allow your browser to use your camera and microphone, to know how, please click ")}}
        <a href="javascript:webCam()" class="alert-link">{{__('HERE')}}</a>
    </div>
    <div class="container">
        <div class="row"> @include('includes.user_dashboard_menu')
            <div class="col-md-9 col-sm-8">
                <div class="myads">
                    <h3>{{__('My Videos')}}</h3>
                    <div>
                        @if (Count($mainVideo) > 0)
                            <div>
                                <div class="row">
                                    <div class="head_video_section">
                                        @if(Count($videos + $mainVideo) < 4)
                                            <h4>{{__('Main Video')}}</h4>
                                            <button type="button" class="btn btn-new-video" data-toggle="modal">
                                                {{__('New Video')}}
                                            </button>
                                        @else
                                            <label>{{__('Main Video')}}</label>
                                            <button type="button" class="btn btn-max-video" >
                                                {{__('You have reached the maximum number of videos allowed')}}
                                            </button>
                                        @endif
                                        
                                    </div>
                                </div>
                                <div class="row" style="background-color: black;">
                                    @foreach ( $mainVideo as $mvideo)
                                        <video width="100%" height="500px" class="video" src="https://filescloik.s3.us-east-2.amazonaws.com/{{$mvideo->dir.'/'.$mvideo->marca.$mvideo->video.'.'.$mvideo->ext}}" type='video/mp4 codecs="avc1.42E01E, mp4a.40.2"'>
                                        </video>
                                    @endforeach
                                </div>
                            </div>
                        @else
                        <div class="row">
                                <div style="background-color: red; padding: 1em; margin: 0% 1em; color: white;">
                                    {{__('You have not selected a main video')}}
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="videos-extra">
                            @if (Count($videos + $mainVideo) > 0)
                                <div class="video_grid">
                                    @foreach ($videos as $video)
                                        <div>
                                            <div class="video_nav"> 
                                                <span>{{$video->title}}</span>    
                                                <div class="dropdown">
                                                    <a type="button" class=" dropdown-toggle glyphicon" data-toggle="dropdown">
                                                        &#xe235;
                                                    </a>
                                                    <div class="dropdown-menu">
                                                        <a href="{{route('my.videos.apply', ['video_id'=>$video->id, 'Met'=>'PUT'])}}" class="dropdown-item">{{__('Become main video')}}</a>
                                                        <a class="dropdown-item" onclick="eliminarVideo({{$video->id}})">{{__('Delete video')}}</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <video class="video video-bk" src="https://filescloik.s3.us-east-2.amazonaws.com/{{$video->dir.'/'.$video->marca.$video->video.'.'.$video->ext}}" type='video/mp4 codecs="avc1.42E01E, mp4a.40.2"'>
                                            </video>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <br>
                                <div class="row">
                                    <div style="background-color: red; padding: 1em; margin: 0% 1em; color: white;">
                                        {{__('You dont have registered videos. Please record a new one')}}
                                        <button type="button" class="btn btn-new-video" style="background-color: red;" data-toggle="modal">
                                            {{__('New Video')}}
                                        </button>
                                    </div>
                                </div>
                            @endif
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
<style>
.alertWebcam{
    display:none; 
    position:relative; 
    bottom:30px;
}
</style>
@endpush
@push('scripts')
<script>
    $('.video').on('mouseenter',function toggleControls() {
        this.setAttribute("controls", "controls");
    });
    $('.video').on('mouseleave',function toggleControls() {
        this.removeAttribute("controls");
    });
    function webCam(){
        console.log('Mensaje de ayuda para activar camara');
    }
</script>
@include('includes.immediate_available_btn')
@endpush