@extends('layouts.app')
@section('content')
@php
    
@endphp
{{-- Contenido del modal:meeting_modal --}}
<div id="meeting-content">
    <div class="modal-content" >
            <div class="modal-header">
            {{-- Titulo del modal y boton de cierre --}}
                <h5 class="modal-title" id="modalTitle">{{__('video of:')}}{{$datos->name}}
                <button type="button" onclick="" class="close" id="close-apply-modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </h5>
            </div>
        <div class="modal-body row">
            <video class="video" id="video" controls="controls" type='video/mp4 codecs="avc1.42E01E, mp4a.40.2"' style="background-color: black" src="https://filescloik.s3.us-east-2.amazonaws.com/{{$datos->dir.'/'.$datos->marca.$datos->video.'.'.$datos->ext}}" type='video/mp4 codecs="avc1.42E01E, mp4a.40.2"'>
            </video>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="{{ asset('css/video.css') }}" rel="stylesheet">
<style type="text/css">
    
</style>
@endpush

@push('scripts')
<script src="https://sdk.amazonaws.com/js/aws-sdk-2.528.0.min.js"></script>
<script>
   
</script>
@include('includes.immediate_available_btn')
@endpush