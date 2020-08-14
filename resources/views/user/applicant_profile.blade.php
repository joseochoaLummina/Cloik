@extends('layouts.app')
@section('content') 
<!-- Header start --> 
@include('includes.header') 
<!-- Header end --> 
<!-- Inner Page Title start --> 
@include('includes.inner_page_title', ['page_title'=>__($page_title)]) 
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

<div class="modal fade" id="blacklistCommentModal" tabindex="-1" role="dialog" aria-labelledby="blacklistCommentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="blacklistCommentModalLabel">{{__('Add Comment')}}</h5>
      </div>
      <div class="modal-body">
        <textarea id="blacklistComment" rows="8"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{_('Cancel')}}</button>
        <button type="button" class="btn btn-success" onclick="addToBlacklist()">{{__('Add to blacklist')}}</button>
      </div>
    </div>
  </div>
</div>

<div class="listpgWraper">
    <div class="container">  
        @include('flash::message')  
        <!-- Job Header start -->
        <div class="job-header">
            <div class="jobinfo">
                <div class="row">
                    <div class="col-md-8 col-sm-8"> 
                        <!-- Candidate Info -->
                        <div class="candidateinfo">
                            <div class="userPic">{{$user->printUserImage()}}</div>
                            <div class="title">
                                {{$user->getName()}}
                                @if((bool)$user->is_immediate_available)
                                <br>
                                <sup style="font-size:12px; color:#090;">{{__('Immediate Available For Work')}}</sup>
                                @endif
                            </div>
                            @if(Auth::guard('company')->check() && !Auth::guard('company')->user()->isBlackList($user->id, $company->id))
                            @else
                                @if(Auth::guard('company')->check())
                                    <div style="background-color: #990000b3; margin: 1% 0% 1% 15%; padding: 0.5em; color: white; border-radius: 0.15em;">{{__('This user is blacklisted by the company')}}</div>
                                @endif
                            @endif
                            @if(Auth::guard('recruiter')->check() )
                                @if(Auth::guard('recruiter')->user()->isBlackList($user->id, $company->id))
                                    <div style="background-color: #990000b3; margin: 1% 0% 1% 15%; padding: 0.5em; color: white; border-radius: 0.15em;">{{__('This user is blacklisted by the company')}}</div>
                                @endif
                            @endif
                            <div class="desi">{{$user->getLocation()}}</div>
                            <div class="loctext"><i class="fa fa-history" aria-hidden="true"></i> {{__('Member Since')}}, {{$user->created_at->format('M d, Y')}}</div>
                            <div class="loctext subtitle"><i class="fa fa-map-marker" aria-hidden="true"></i> {{$user->street_address}}</div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="jobButtons">
               @if(isset($company))
                    {{-- company --}}
                    @if (Auth::guard('company')->check())
                        @if(Auth::guard('company')->user()->isFavouriteApplicant($user->id, $company->id))
                        <a href="{{route('remove.from.favourite.applicant', [$application_id, $user->id,$type, $company->id])}}" class="btn" style="border-color: red"><i class="fa fa-floppy-o" aria-hidden="true"></i> {{__('Remove from Favourites')}} </a>
                        @else
                        <a href="{{route('add.to.favourite.applicant', [$application_id, $user->id,$type, $company->id])}}" class="btn" style="border-color: green"><i class="fa fa-floppy-o" aria-hidden="true"></i> {{__('Add to Favourite')}}</a>
                        @endif
                        {{-- Black list --}}
                        @if(!Auth::guard('company')->user()->isBlackList($user->id, $company->id))
                        <a data-controls-modal="meeting_modal" data-backdrop="static" data-keyboard="false" class="btn" data-toggle="modal" data-target="#blacklistCommentModal" onclick="limpiarComment({{$user->id}})">
                            <i class="fa fa fa-shield" aria-hidden="true"></i> {{__('Add to blacklist')}}
                        </a>
                        @else
                        <a href="{{route('remove.black.list', [$application_id,$user->id,$type, $company->id])}}" class="btn btn-danger"><i class="fa fa-shield" aria-hidden="true"></i> {{__('Remove to blacklist')}}</a>
                        @endif
                    @endif
                    {{-- recruiter --}}
                    @if (Auth::guard('recruiter')->check())
                        @if(Auth::guard('recruiter')->user()->isFavouriteApplicant($user->id, $company->id))
                            @if (Auth::guard('recruiter')->user()->recruiterType())
                            <a href="{{route('recruiter.remove.from.favourite.applicant', [$application_id, $user->id,$type, $company->id])}}" class="btn" style="border-color: red"><i class="fa fa-floppy-o" aria-hidden="true"></i> {{__('Remove from Favourites')}} </a>
                            @else
                            <a disabled class="btn" style="border-color: red"><i class="fa fa-floppy-o" aria-hidden="true"></i> {{__('Remove from Favourites')}} </a>
                            @endif
                        @else
                        <a href="{{route('recruiter.add.to.favourite.applicant', [$application_id, $user->id,$type, $company->id])}}" class="btn" style="border-color: green"><i class="fa fa-floppy-o" aria-hidden="true"></i> {{__('Add to Favourite')}}</a>
                        @endif
                        {{-- Black list --}}
                        @if(!Auth::guard('recruiter')->user()->isBlackList($user->id, $company->id))
                        <a data-controls-modal="meeting_modal" data-backdrop="static" data-keyboard="false" class="btn" data-toggle="modal" data-target="#blacklistCommentModal" onclick="limpiarComment({{$user->id}})">
                            <i class="fa fa fa-shield" aria-hidden="true"></i> {{__('Add to blacklist')}}
                        </a>
                        @elseif(Auth::guard('recruiter')->user()->recruiterType() )
                        <a href="{{route('recruiter.remove.black.list', [$application_id,$user->id,$type, $company->id])}}" class="btn btn-danger"><i class="fa fa-shield" aria-hidden="true"></i> {{__('Remove to blacklist')}}</a>
                        @else
                        <a disabled class="btn btn-danger"><i class="fa fa-shield" aria-hidden="true"></i> {{__('Remove to blacklist')}}</a>
                        @endif
                    @endif
                    
                @endif
                @if(null !== $profileCv)
                    <a href="{{asset('cvs/'.$profileCv->cv_file)}}" class="btn"><i class="fa fa-download" aria-hidden="true"></i> {{__('Download CV')}}</a>
                @else
                    <a class="btn"><i class="fa fa-download" aria-hidden="true"></i> {{__('None CV')}}</a>
                @endif
                <!-- <a href="#contact_applicant" class="btn"><i class="fa fa-envelope" aria-hidden="true"></i> {{__('Send Message')}}</a> -->
                @if (Auth::guard('company')->check() || Auth::guard('recruiter')->check() )
                    @if(Auth::guard('company')->check())
                    <a data-controls-modal="meeting_modal" data-backdrop="static" data-keyboard="false" class="btn apply" data-toggle="modal" data-target="#replyMsgModal" onclick="limpiarTextArea({{$user->id}})">
                        <i class="fa fa-envelope-o" aria-hidden="true"></i> {{__('Send message')}}
                    </a>
                    @elseif(Auth::guard('recruiter')->user()->recruiterType())
                    <a data-controls-modal="meeting_modal" data-backdrop="static" data-keyboard="false" class="btn apply" data-toggle="modal" data-target="#replyMsgModal" onclick="limpiarTextArea({{$user->id}})">
                        <i class="fa fa-envelope-o" aria-hidden="true"></i> {{__('Send message')}}
                    </a>
                    @else
                    @endif
                @endif
            </div>
        </div>
        <!-- Job Detail start -->
        <div class="row">
            <div class="col-md-8"> 
                @if ((Auth::guard('company')->check()||Auth::guard('recruiter')->check()) && $type==='company')                
                    @if ($datos!=null)
                    <div class="job-header">
                        <div class="contentbox videobox">
                            <div><h3>{{__('Applicant Video')}}</h3></div>
                            <div>
                                <video width="100%" height="300px" class="video" style="max-height: 300px; background-color: black;" id="video" controls="controls" type='video/mp4 codecs="avc1.42E01E, mp4a.40.2"' src="https://filescloik.s3.us-east-2.amazonaws.com/{{$datos[0]->dir.'/'.$datos[0]->marca.$datos[0]->video.'.'.$datos[0]->ext}}" type='video/mp4 codecs="avc1.42E01E, mp4a.40.2"'>
                                </video>
                            </div>
                        </div>
                    </div>
                    @else
                        
                    @endif
                @endif
                <!-- About Employee start -->
                <div class="job-header">
                    <div class="contentbox">
                        <h3>{{__('About me')}}</h3>
                        <p>{{$user->getProfileSummary('summary')}}</p>
                    </div>
                </div>

                <!-- Education start -->
                <div class="job-header">
                    <div class="contentbox">
                        <h3>{{__('Education')}}</h3>
                        <div class="" id="education_div"></div>            
                    </div>
                </div>

                <!-- Experience start -->
                <div class="job-header">
                    <div class="contentbox">
                        <h3>{{__('Experience')}}</h3>
                        <div class="" id="experience_div"></div>            
                    </div>
                </div>
                <!-- Portfolio start 
                <div class="job-header">
                    <div class="contentbox">
                        <h3>{{__('Portfolio')}}</h3>
                        <div class="" id="projects_div"></div>            
                    </div>
                </div> -->

                <!-- Comments -->
                <div class="job-header">
                    <div class="contentbox">
                        <h3>{{__('Comments')}}</h3>
                        <div class="" id="newComments_div">
                            @if ($type=='company')
                                @if (Auth::guard('recruiter')->check())
                                    {!! Form::model($user, array('method' => 'post', 'route' => array('recruiter.new.comment',$application_id,$from='recruiter'), 'class' => 'form', 'files'=>true)) !!}
                                    {!! Form::textarea('comments', null, array('class'=>'form-control','rows'=>"5" ,'cols'=>"40",'id'=>'newComment', 'placeholder'=>__('Comments'))) !!}
                                    <div class="formrow" style="margin:1%;">                                    
                                        <button type="submit" class="btn" >{{__('Save comments')}}
                                            <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    {!! Form::close() !!}
                                @elseif(Auth::guard('company')->check())
                                    {!! Form::model($user, array('method' => 'post', 'route' => array('new.comment',$application_id,$from='company'), 'class' => 'form', 'files'=>true)) !!}
                                    {!! Form::textarea('comments', null, array('class'=>'form-control','rows'=>"5" ,'cols'=>"40",'id'=>'newComment', 'placeholder'=>__('Comments'))) !!}
                                    <div class="formrow" style="margin:1%;">                                    
                                        <button type="submit" class="btn">{{__('Save comments')}}
                                            <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    {!! Form::close() !!}
                                @endif
                            @endif
                        </div>
                        <div class="contentbox" id="Comments_div"></div>
                    </div>
                </div> 
            </div>
            <div class="col-md-4"> 
                <!-- Candidate Detail start -->
                <div class="job-header">
                    <div class="jobdetail">
                        <h3>{{__('Candidate Detail')}}</h3>
                        <ul class="jbdetail">
                            <!-- EMAIL VERIFICADO
                            <li class="row">
                                <div class="col-md-6 col-xs-6">{{__('Is Email Verified')}}</div>
                                <div class="col-md-6 col-xs-6"><span>{{((bool)$user->verified)? 'Yes':'No'}}</span></div>
                            </li> -->
                            <!-- Immediate
                            <li class="row">
                                <div class="col-md-6 col-xs-6">{{__('Immediate Available')}}</div>
                                <div class="col-md-6 col-xs-6"><span>{{((bool)$user->is_immediate_available)? 'Yes':'No'}}</span></div>
                            </li> -->
                            <li class="row">
                                <div class="col-md-6 col-xs-6">{{__('Phone')}}</div>
                                <div class="col-md-6 col-xs-6"><span><a href="tel:{{$user->phone}}">{{$user->phone}}</a></span></div>
                            </li>
                            <li class="row">
                                <div class="col-md-6 col-xs-6">{{__('Mobile phone')}}</div>
                                <div class="col-md-6 col-xs-6"><span><a href="tel:{{$user->mobile_num}}">{{$user->mobile_num}}</a></span></div>
                            </li>
                            <li class="row">
                                <div class="col-md-6 col-xs-6">{{__('Email')}}</div>
                                <div class="col-md-6 col-xs-6"><span><a href="mailto:{{$user->email}}">{{$user->email}}</a></span></div>
                            </li>
                            <li class="row">
                                <div class="col-md-6 col-xs-6">{{__('Age')}}</div>
                                <div class="col-md-6 col-xs-6"><span>{{$user->getAge()}} {{__('Years')}}</span></div>
                            </li>
                            <li class="row">
                                <div class="col-md-6 col-xs-6">{{__('Gender')}}</div>
                                <div class="col-md-6 col-xs-6"><span>{{$user->getGender('gender')}}</span></div>
                            </li>
                            <li class="row">
                                <div class="col-md-6 col-xs-6">{{__('Marital Status')}}</div>
                                <div class="col-md-6 col-xs-6"><span>{{$user->getMaritalStatus('marital_status')}}</span></div>
                            </li>

                            <!-- Experiencia
                            <li class="row">
                                <div class="col-md-6 col-xs-6">{{__('Experience')}}</div>
                                <div class="col-md-6 col-xs-6"><span>{{$user->getJobExperience('job_experience')}}</span></div>
                            </li> -->

                            <!-- Career 
                            <li class="row">
                                <div class="col-md-6 col-xs-6">{{__('Career Level')}}</div>
                                <div class="col-md-6 col-xs-6"><span>{{$user->getCareerLevel('career_level')}}</span></div>
                            </li> -->

                            <!--
                            <li class="row">
                                <div class="col-md-6 col-xs-6">{{__('Current Salary')}}</div>
                                <div class="col-md-6 col-xs-6"><span class="permanent">{{$user->current_salary}} {{$user->salary_currency}}</span></div>
                            </li> -->
                            <li class="row">
                                <div class="col-md-6 col-xs-6">{{__('Expected salary')}}</div>
                                <div class="col-md-6 col-xs-6"><span class="freelance">{{$user->expected_salary}} {{$user->salary_currency}}</span></div>
                            </li>              
                        </ul>
                    </div>
                </div>

                <div class="job-header">
                        <div class="jobdetail">
                            <h3>{{__('Languages')}}</h3>
                            <div id="language_div"></div>            
                        </div>
                    </div>

                <!-- Google Map start -->
                <div class="job-header">
                    <div class="jobdetail">
                        <h3>{{__('Skills')}}</h3>
                        <div id="skill_div"></div>            
                    </div>
                </div>

            </div>
        </div>
    </div>    
    @php
        $routeRecruiter='recruiter.add.black.list';
        $routeRecruiterComment='recruiter.show.comment.company';
        $routeRecruiterDeleteComment='recruiter.delete.comment.company';
        $routecompany='add.black.list';
        $routecompanyComment='show.comment.company';
        $routecompanyDeleteComment='delete.comment.company';
    @endphp
</div>
@include('includes.footer')
@endsection
@push('styles')
<style type="text/css">
    .formrow iframe {
        height: 78px;
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

    #blacklistComment {
        width: 100%;
        resize: none;
        border: solid 1px #ccc;
        border-radius: 5px;
        padding: 1rem;
    }
</style>
@endpush
@push('scripts')
<script src="https://sdk.amazonaws.com/js/aws-sdk-2.528.0.min.js"></script>
<script type="text/javascript">

    var uId;

    function limpiarComment(id) {
        uId = id;
        document.getElementById('blacklistComment').style.backgroundColor = "#fff";
        document.getElementById('blacklistComment').value = "";
    }

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
                    window.location.reload(false);
                }
            }
        });
    }

    function addToBlacklist() {
        
        var aId = {!! json_encode($application_id) !!};
        var uId = {!! json_encode($user->id) !!};
        var tp = {!! json_encode($type) !!};
        var cId = ('<?php if(isset($company)) {echo $company->id;} else { echo 0;}; ?>');
        var comment = document.getElementById('blacklistComment').value;
        if (comment.length > 0) {
            document.getElementById('blacklistComment').style.backgroundColor = "#fff";
            $.ajax({
                type: 'GET',
                url: '<?php  if( Auth::guard("recruiter")->check() ){ echo( route($routeRecruiter) ); } elseif(Auth::guard("company")->check()){ echo( route($routecompany) ); }  ?>',
                data: {
                    application_id: aId,
                    user_id: uId,
                    type: tp,
                    company_id: cId,
                    comment: comment
                },
                success: function(data) {
                    window.location = data.redirect_to;
                }
            });
        }
        else {
            document.getElementById('blacklistComment').style.backgroundColor = "#ffd1cfab";
        }

    }

    $(document).ready(function () {
        $(document).on('click', '#send_applicant_message', function () {
        var postData = $('#send-applicant-message-form').serialize();
        $.ajax({
        type: 'POST',
                url: "{{ route('contact.applicant.message.send') }}",
                data: postData,
                //dataType: 'json',
                success: function (data)
                {
                response = JSON.parse(data);
                var res = response.success;
                if (res == 'success')
                {
                var errorString = '<div role="alert" class="alert alert-success">' + response.message + '</div>';
                $('#alert_messages').html(errorString);
                $('#send-applicant-message-form').hide('slow');
                $(document).scrollTo('.alert', 2000);
                } else
                {
                var errorString = '<div class="alert alert-danger" role="alert"><ul>';
                response = JSON.parse(data);
                $.each(response, function (index, value)
                {
                errorString += '<li>' + value + '</li>';
                });
                errorString += '</ul></div>';
                $('#alert_messages').html(errorString);
                $(document).scrollTo('.alert', 2000);
                }
                },
        });
        });
        showEducation();
        showProjects();
        showExperience();
        showSkills();
        showLanguages();
        showCommentsCompany();
        
    });
    
    function showProjects()
    {
    $.post("{{ route('show.applicant.profile.projects', $user->id) }}", {user_id: {{$user->id}}, _method: 'POST', _token: '{{ csrf_token() }}'})
            .done(function (response) {
            $('#projects_div').html(response);
            });
    }
    function showExperience()
    { 
    $.post("{{ route('show.applicant.profile.experience', $user->id) }}", {user_id: {{$user->id}}, _method: 'POST', _token: '{{ csrf_token() }}'})
            .done(function (response) {
            $('#experience_div').html(response);
            });
    }
    function showEducation()
    {
    $.post("{{ route('show.applicant.profile.education', $user->id) }}", {user_id: {{$user->id}}, _method: 'POST', _token: '{{ csrf_token() }}'})
            .done(function (response) {
            $('#education_div').html(response);
            });
    }
    function showLanguages()
    {
    $.post("{{ route('show.applicant.profile.languages', $user->id) }}", {user_id: {{$user->id}}, _method: 'POST', _token: '{{ csrf_token() }}'})
            .done(function (response) {
            $('#language_div').html(response);
            });
    }
    function showSkills()
    {
    $.post("{{ route('show.applicant.profile.skills', $user->id) }}", {user_id: {{$user->id}}, _method: 'POST', _token: '{{ csrf_token() }}'})
            .done(function (response) {
            $('#skill_div').html(response);
            });
    }
    function showCommentsCompany()
    {
        $.ajax({
                type: 'POST',
                url: '<?php  if( Auth::guard("recruiter")->check() ){ echo( route($routeRecruiterComment) ); } elseif(Auth::guard("company")->check()){ echo( route($routecompanyComment) ); }  ?>',
                data: {
                    application_id: {{$application_id}},
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#Comments_div').html(response);
                }
            });
    }
    function deleteComment(id)
    {
        $.ajax({
                type: 'POST',
                url: '<?php  if( Auth::guard("recruiter")->check() ){ echo( route($routeRecruiterDeleteComment) ); } elseif(Auth::guard("company")->check()){ echo( route($routecompanyDeleteComment) ); }  ?>',
                data: {
                    application_id: {{$application_id}},
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#Comments_div').html(response);
                }
            });
    }
</script> 
@endpush

