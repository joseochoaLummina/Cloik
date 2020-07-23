@extends('layouts.app')
@section('content') 
<!-- Header start --> 
@include('includes.header') 
<!-- Header end --> 
<!-- Inner Page Title start --> 
@include('includes.inner_page_title', ['page_title'=>__('Company Followers')]) 
<!-- Inner Page Title end -->
@push('styles')
    <link href="{{ asset('css/meeting.css') }}" rel="stylesheet">
@endpush
<div class="listpgWraper">
    <div class="container">
        <div class="row">
            @if(Auth::guard('company')->user())
                @include('includes.company_dashboard_menu')
            @elseif(Auth::guard('recruiter')->user())
                @include('includes.recruiter_dashboard_menu')
            @endif

            <div class="col-md-9 col-sm-8">
                <div class="myads">
                    <h3>{{__('My Meetings')}}</h3>
                    @if (Auth::guard('recruiter')->user())
                        @if (Auth::guard('recruiter')->user()->countPendingMeetings()===0)
                            <h4>{{__('You have no assigned meetings')}}</h4>
                        @endif
                    @endif
                    <div id="meetContainer">
                        @if (Count($meetings) > 0)
                            @foreach ( $meetings as $meet)
                                <div class="company-meeting">
                                    <!-- {{$meet->image}} -->
                                    <img src="{{asset('/user_images').'/'.$meet->image}}">
                                    <div>
                                        <div>
                                            <div class="meetTitle">{{$meet->name}}</div>
                                            <div class="meetSubTitle">{{$meet->title}}</div>
                                            <div class="meetLocation">{{$meet->email}}</div>
                                            <div class="meetDate">
                                                <?php
                                                    $dateNow = date('Y-m-d', time());
                                                    $m = $meet->planned_time;
                                                    $hora = substr($m, 0, 2);
                                                    $min = substr($m, 3, 2);
                                                    $jor = 'AM';
                                                    if ($hora == 24 || $hora == 0) {
                                                        $hora = 12;
                                                    }
                                                    else if ($hora == 12) {                                                        
                                                        $jor = 'PM';
                                                    }
                                                    else if ($hora > 12 && $hora < 24) {
                                                        $hora = $hora - 12;
                                                        $jor = 'PM';
                                                    }
                                                    $d = substr($meet->planned_date, 0, 10);
                                                    echo $d.' '.$hora.':'.$min.' '.$jor;
                                                ?>
                                            </div>
                                        </div>
                                        <div class="meetlinks">
                                            @if ($d == $dateNow)
                                                <a id="llamar" href="{{ route('meetings.call', ['id'=>$meet->id]) }}" class="btn enterCall">
                                                    {{__('Enter Call')}}
                                                </a>
                                            @endif
                                            
                                            @if ($d != $dateNow)
                                                @if (Auth::guard('company')->user() || Auth::guard('recruiter')->user()->recruiterType())
                                                    <a class="btn cancelCall" id="btnCancelCall" onclick="eliminar( {{$meet->id}} )">
                                                        <i class="fa fa-close"></i>
                                                    </a>
                                                @elseif(!Auth::guard('recruiter')->user()->recruiterType())
                                                    <a class="btn btn-primary" onclick="buttonSend( {{$meet->id}} )" data-toggle="modal" data-target="#changeMeetingModal">
                                                        <i class="fa fa-envelope-o" aria-hidden="true"></i> {{__('Request a change')}}
                                                    </a>
                                                @endif
                                            @endif
                                            
                                        </div>
                                    </div>
                                </div>                            
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div id="changeMeetingModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{__('Ask for a meeting change')}}</h4>
            </div>
            <div class="modal-body">
                <textarea rows=10 class="changeMeetingBox" id="changeMeetingBox" placeholder="Type your request"></textarea>
            </div>
            <div class="modal-footer" id="footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{__('Close')}}</button>
            </div>
        </div>                                            
    </div>
</div>

@include('includes.footer')
@endsection

@push('styles')
<style type="text/css">
    .changeMeetingBox{
        width:100%;
        height:100%;
    }
</style>                                                   
@endpush
@push('scripts')
@include('includes.immediate_available_btn')
<script>
    function eliminar(_id) {
        $('#btnCancelCall').attr("disabled", true);
        document.getElementById('btnCancelCall').innerHTML='{{__("Canceling meeting")}}';

        $.post('<?php  if( Auth::guard("recruiter")->check() ){ echo( route('recruiter.delete.meeting') ); } elseif(Auth::guard("company")->check()){ echo( route('delete.meeting') ); }  ?>', {id:_id, _method: 'DELETE', _token: '{{ csrf_token() }}'})   
            .done(function (response) {                
                window.location.reload();                            
                        
            }).fail(function(e){
                    console.log(e);                        
            })
        ;
    }

    function send(_id){
        var msgText = document.getElementById('changeMeetingBox').value;
        
        $.post("{{ route('recruiter.change.meeting') }}", {/*selected:selected,*/ id:_id, msg:msgText, _method: 'POST', _token: '{{ csrf_token() }}'})   
            .done(function (response) {                
                window.location.reload();                            
                        
            }).fail(function(e){
                    console.log(e);                        
            })
        ;
    }

    function buttonSend(_id){
        var footer=document.getElementById("footer");
        if(!document.getElementById("sendButton")){
            footer.innerHTML+=`
                <button type="button" class="btn btn-primary" id="sendButton" data-dismiss="modal" onclick="send(${_id})">{{__('Send')}}</button>
            `;
        }        
    }
</script>
@endpush