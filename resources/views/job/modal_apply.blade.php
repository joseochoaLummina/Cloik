@extends('layouts.app')
@section('content')
{{-- Contenido del modal:recomendaciones y video a presentar --}}
@push('styles')
    <link href="{{ asset('css/verify_apply.css') }}" rel="stylesheet">
@endpush
@push('styles')
    <link href="{{ asset('css/video.css') }}" rel="stylesheet">
@endpush
@push('scripts')
    <script src="https://sdk.amazonaws.com/js/aws-sdk-2.528.0.min.js"></script>
@endpush

<div id="modal-videos">
    <div class="modal-content" >
            <div class="modal-header">
            {{-- Titulo del modal y boton de cierre --}}
                <h5 class="modal-title" id="modalTitle">{{__('Choose the video for apply or create a new one')}}
                <button type="button" class="close" id="close-apply-modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </h5>
            </div>
        <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="recom">
                            <h6 class="header-recom" style="margin: 0;">
                                {{__("Recommendations")}}
                            </h6>
                            <ul class="body-recom">
                                @if ( $recomendaciones!=null )
                                    @foreach(explode("(/&/)",$recomendaciones) as $valor)
                                        <br>
                                        <li>
                                            &nbsp;{{$valor}}
                                        </li>
                                    @endforeach
                                @else
                                    <br>
                                    <li class="no-available">
                                    <h6>{{__('No recommendations available')}}</h6>
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <div class="aplicar-de">
                            <div class="videoOrigen" >
                            @php
                                $cantidad=Count($videos);
                            @endphp
                            @if ($cantidad > 0)
                            {{-- Si el usuario tiene videos en su perfil--}}
                                @foreach ($videos as $videoMain)
                                    @if ($videoMain->is_main==1)
                                    {{-- si el usuario tiene video principal --}}
                                    <video class="video" id="videoMain" controls="controls" type='video/mp4 codecs="avc1.42E01E, mp4a.40.2"' style="background-color: black" src="https://filescloik.s3.us-east-2.amazonaws.com/{{$videoMain->dir.'/'.$videoMain->marca.$videoMain->video.'.'.$videoMain->ext}}" type='video/mp4 codecs="avc1.42E01E, mp4a.40.2"'>
                                    </video>
                                    <div class="btn-videoApply">
                                        @if (true)
                                        {{-- se comprueba que el usuario aplique --}}
                                        <a onclick="aplicar({{$videoMain->id}})" id="apply" class="btn apply btn-success subir"><i class="fa fa-check-square-o" aria-hidden="true"></i> {{__('Apply on this Job')}}</a>
                                        <a href="{{route('my.videos.apply', ['video_id'=>0, 'slug'=>$slug])}}" class="btn btn-new-video btn-danger nuevo">
                                            <i class="fa fa-plus-square" aria-hidden="true" ></i>&nbsp;{{__('new video')}}
                                        </a>
                                        @else
                                        {{-- si no aplica --}}
                                        <button class="btn btn-success noSubir" disabled="disabled">{{__('Your profile does not meet to apply to this job')}}</button>
                                        <a href="{{route('my.videos.apply', ['video_id'=>0, 'slug'=>$slug])}}" class="btn btn-new-video btn-danger nuevo">
                                            <i class="fa fa-plus-square" aria-hidden="true" ></i>&nbsp;{{__('new video')}}
                                        </a>
                                        @endif
                                    </div>
                                    @break
                                    @else
                                    {{-- si no tiene video principal --}}
                                    <div class="row" style="justify-content: center;">
                                        <div style="background-color: red; padding: 1em; margin: 15% 1em; color: white;">
                                            {{__('You have not selected a main video')}}
                                        </div>
                                    </div>
                                    <div class="btn-videoApply">
                                        <button class="btn btn-success noSubir" disabled="disabled">{{__('Your profile does not meet to apply to this job')}}</button>
                                        <button type="button" class="btn btn-new-video btn-danger nuevo">
                                            <i class="fa fa-plus-square" aria-hidden="true" ></i>&nbsp;{{__('new video')}}
                                        </button>
                                    </div>
                                    @break
                                    @endif
                                @endforeach
                            @else
                            {{-- Si el usuario no tinee videos --}}
                                <div class="row" style="justify-content: center;">
                                    <div style="background-color: red; padding: 1em; margin: 15% 1em; color: white;">
                                        {{__('You have no videos on your profile')}}
                                    </div>
                                </div>
                                <div class="btn-videoApply">
                                    <button class="btn btn-success noSubir" disabled="disabled">{{__('Your profile does not meet to apply to this job')}}</button>
                                    <button type="button" class="btn btn-new-video  btn-danger nuevo">
                                        <i class="fa fa-plus-square" aria-hidden="true" ></i>&nbsp;{{__('new video')}}
                                    </button>
                                </div>
                            @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="video-more" >
                    @php
                        $i=0;
                    @endphp
                    @if ($cantidad)
                        @foreach ($videos as $video)
                        <div class="contentVideos" id="{{$video->id}}">
                            <div style="background-color: black; color: white;">{{$video->video}}</div>
                            <video class="videos" id="{{$video->id}}" src="https://filescloik.s3.us-east-2.amazonaws.com/{{$video->dir.'/'.$video->marca.$video->video.'.'.$video->ext}}" type='video/mp4 codecs="avc1.42E01E, mp4a.40.2"'>
                            </video>
                            <div onclick="iad({{$i}})" class="divVideos" name="{{$video->id}}" id="{{$video->id}}" value="{{$video->id}}"></div>
                        </div>
                        @php
                            $i++;
                        @endphp
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
</div>
@endsection
@push('scripts')

<script>
    $(document).on('click','#close-apply-modal',function(e){
        e.preventDefault();
        $('#newVideo').remove();
    })
    var videos=@json($videos);
    var job=@json($job);
    function iad(idv){
        document.getElementById("apply").setAttribute('onclick',`aplicar(${videos[idv].id})`);
        document.getElementById('videoMain').setAttribute('src',`https://filescloik.s3.us-east-2.amazonaws.com/${videos[idv].dir}/${videos[idv].marca}${videos[idv].video}.${videos[idv].ext}`);
    }
    
    function aplicar(idVideo){
        var id = idVideo;
        var link = document.createElement('a');
        link.style = "display:none";
        link.id = "applyJobVideo";
        var url = '{{route("apply.job", ["job_s" => $job->slug,"video" => ":id"])}}';
        url = url.replace(':id', id);
        link.setAttribute('href', url);
        document.body.appendChild(link);
        link.click();
    }
    $('.video').on('mouseenter',function toggleControls() {
            this.setAttribute("controls", "controls");
        });
    $('.video').on('mouseleave',function toggleControls() {
        this.removeAttribute("controls");
    });
</script>
@include('includes.immediate_available_btn')
@endpush