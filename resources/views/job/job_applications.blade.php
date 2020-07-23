@extends('layouts.app')
@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end --> 
<!-- Inner Page Title start -->
@include('includes.inner_page_title', ['page_title'=>__('Job Applications')])
<!-- Inner Page Title end -->

<div class="modal fade" id="replyMsgModal" tabindex="-1" role="dialog" aria-labelledby="replyMsgModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="replyMsgModalLabel">{{__('Send Message')}}</h5>
      </div>
      <div class="modal-body">
        <textarea id="msgReplyText" rows="8"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{_('Cancel')}}</button>
        <button type="button" class="btn btn-success" onclick="sendMessage()">{{__('Send')}}</button>
      </div>
    </div>
  </div>
</div>


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
                    <h3>{{__('Job Applications')}}</h3>
                    <div style="margin:1%;">

                        @if(Auth::guard('company')->user() || Auth::guard('recruiter')->user()->recruiterType())
                            <a href="#" class="btn btn-info">Top 10</a>
                            <a href="{{route('list.applied.users', [$job_id])}}" class="btn btn-info">{{__('All applicants')}}</a>
                            <a href="{{route('list.favourite.applied.users', [$job_id])}}" class="btn btn-info" >{{__('Favourites Candidates List')}}</a>
                            <a href="#" class="btn btn-info">{{__('Suggested')}}</a>
                        @else
                            <a href="#" class="btn btn-info">Top 10</a>
                            <a href="{{route('recruiter.list.applied.users', [$job_id])}}" class="btn btn-info">{{__('All applicants')}}</a>
                            <a href="{{route('recruiter.list.favourite.applied.users', [$job_id])}}" class="btn btn-info" >{{__('Favourites Candidates List')}}</a>
                            <a href="#" class="btn btn-info">{{__('Suggested')}}</a>
                        @endif

                    </div>
                    <ul class="searchList">
                        <!-- job start --> 
                        @if(isset($job_applications) && count($job_applications))
                        @foreach($job_applications as $job_application)
                        @php
                            $user = $job_application->getUser();
                            $job = $job_application->getJob();
                            $company = $job->getCompany();             
                            $profileCv = $job_application->getProfileCv();
                        @endphp
                        @if(null !== $job_application && null !== $user && null !== $job && null !== $company)
                        <li>
                            <div class="row">
                                <div class="col-md-5 col-sm-5">
                                    <div class="jobimg">{{$user->printUserImage(100, 100)}}</div>
                                    <div class="jobinfo">
                                        @if(Auth::guard('company')->user() || Auth::guard('recruiter')->user())                                            
                                            @if (Auth::guard('recruiter')->user())
                                            <h3><a href="{{route('recruiter.applicant.profile', $job_application->id)}}">{{$user->getName()}}</a></h3>
                                            @else
                                            <h3><a href="{{route('applicant.profile', $job_application->id)}}">{{$user->getName()}}</a></h3>
                                            @endif
                                        @endif
                                        <div class="location"> {{$user->getLocation()}}</div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="col-md-4 col-sm-4">
                                    <div class="minsalary"> <span></span></div>
                                </div>
                                <div class="col-md-3 col-sm-3">
                                    
                                    @if(Auth::guard('company')->user() || Auth::guard('recruiter')->user()->recruiterType())
                                        @if (Auth::guard('recruiter')->user())
                                            <div class="listbtn"><a href="{{route('recruiter.applicant.profile', $job_application->id)}}">{{__('View Profile')}}</a></div>
                                        @else
                                            <div class="listbtn"><a href="{{route('applicant.profile', $job_application->id)}}">{{__('View Profile')}}</a></div>
                                        @endif
                                        <div class="listbtn">
                                        
                                            @if(Auth::guard('recruiter')->user())
                                                <a data-controls-modal="meeting_modal" data-backdrop="static" 
                                                data-keyboard="false" href="{{route('recruiter.get.schedule.meeting',[$user->id, $job->id,$company->id])}}" 
                                                class="btn apply btn-block" id="btn_meeting" data-toggle="modal" data-target="#meeting_modal">
                                                    <i class="fa fa-paper-plane" aria-hidden="true"></i> {{__('Schedule meeting')}}
                                                </a>                                                
                                            @else
                                                <a data-controls-modal="meeting_modal" data-backdrop="static" 
                                                data-keyboard="false" href="{{route('get.schedule.meeting',[$user->id, $job->id,$company->id])}}" 
                                                class="btn apply btn-block" id="btn_meeting" data-toggle="modal" data-target="#meeting_modal">
                                                    <i class="fa fa-paper-plane" aria-hidden="true"></i> {{__('Schedule meeting')}}
                                                </a>
                                            @endif

                                        </div>

                                        <div class="listbtn">
                                            <a data-controls-modal="meeting_modal" data-backdrop="static" data-keyboard="false" class="btn apply btn-block" data-toggle="modal" data-target="#replyMsgModal" onclick="limpiarTextArea({{$user->id}})">
                                                <i class="fa fa-envelope-o" aria-hidden="true"></i> {{__('Send message')}}
                                            </a>
                                        </div>
                                    @else
                                        <div class="listbtn"><a href="{{route('recruiter.applicant.profile', $job_application->id)}}">{{__('View Profile')}}</a></div>
                                    @endif

                                </div>
                            </div>
                            <p>{{str_limit($user->getProfileSummary('summary'),150,'...')}}</p>
                        </li>
                        <!-- job end --> 
                        @endif
                        @endforeach
                        @else
                        <div>
                            <h1>{{__('They have not yet applied')}}</h1>
                        </div>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
  <!-- Modal -->
<div class="modal fade" id="meeting_modal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div>
            <div class="modal-content">
                    
            </div>
        </div>
    </div>
</div>
<!-- Modal end -->

@include('includes.footer')
@push('styles')
<style type="text/css">
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
    var uId;
    function limpiarTextArea(id) {
        uId = id;
        document.getElementById('msgReplyText').value = "";
    }

    function sendMessage() {
        var msgText = document.getElementById('msgReplyText').value;

        $.ajax({
            type: 'GET',
            url: "{{ route('send.message.to.candidate') }}",
            data: {msg: msgText, user_id: uId},
            success: function(data) {
                if (data.status == 'ok') {
                    window.location.reload(true);
                }
            }
        });
    }
</script>
@endpush
@endsection
