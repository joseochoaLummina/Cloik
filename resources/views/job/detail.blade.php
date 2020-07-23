@extends('layouts.app')
@section('content') 
<!-- Header start --> 
@include('includes.header') 
<!-- Header end --> 
<!-- Inner Page Title start --> 
@include('includes.inner_page_title', ['page_title'=>__('Job Detail')]) 
<!-- Inner Page Title end -->
@php
$company = $job->getCompany();
@endphp
@push('styles')
    <link href="{{ asset('css/verify_apply.css') }}" rel="stylesheet">
@endpush

<div class="listpgWraper">
    @if ((bool)Auth::user() === true || (bool)Auth::guard('company')->check() === true || (bool)Auth::guard('recruiter')->check() ===true )
        <div class="container"> 
            @include('flash::message')
        

        <!-- Job Detail start -->
        <div class="row">
            <div class="col-md-8"> 
        
            <!-- Job Header start -->
        <div class="job-header">
            <div class="jobinfo row pt-0 pb-0" style="margin:0%">
                    <div class="col-md-8 mt-4 mb-4">
                        <h2>{{$job->title}} - {{$company->name}}</h2>
                        <div class="ptext">{{__('Date Posted')}}: {{$job->created_at->format('M d, Y')}}</div>
                        <!-- @if(!(bool)$job->hide_salary)
                        <div class="salary">{{__('Monthly Salary')}}: <strong>{{$job->salary_from.' '.$job->salary_currency}} - {{$job->salary_to.' '.$job->salary_currency}}</strong></div>
                        @endif -->
                        <!-- @if (Auth::user())
                            @if(!$aplica && !$job->isJobExpired() && !Auth::user()->isAppliedOnJob($job->id))
                                <p class="cantApplyMsg">{{__("You can't apply to this job, you don't have the required skills") }}</p>
                            @endif
                        @endif -->
                        @if(Auth::user())
                            @if(!$haveVideos && !Auth::user()->isAppliedOnJob($job->id))
                                <p class="cantApplyMsg">{{__("Sorry, but you must have at least one video to apply on the jobs") }}</p>
                            @elseif(!$haveMainVideo && !Auth::user()->isAppliedOnJob($job->id))
                                <p class="cantApplyMsg">{{__("Sorry, but you must have a main video to apply on the jobs") }}</p>
                            @endif
                        @endif
                    </div>                    
                <div class="col-md-4 mt-4 mb-4">
                    <div class=" jobButtons row" style="padding-top: 5px; padding-bottom: 5px;" id="buttons">
                        @if($job->isJobExpired())
                        <span class="jbexpire btn-block"><i class="fa fa-paper-plane" aria-hidden="true"></i> {{__('Job is expired')}}</span>
                        @elseif(Auth::user())
                            @if (Auth::user()->isAppliedOnJob($job->id))
                                <!-- <a href="javascript:;" class="btn apply btn-block"><i class="fa fa-paper-plane" aria-hidden="true"></i> {{__('Already Applied')}}</a> -->
                                <a href="{{route('delete.job.apply', [$job->id])}}" class="btn apply delete-btn"><i class="fa fa-ban" aria-hidden="true"></i> {{__('Cancel Application')}}</a>  
                            @elseif(!Auth::user()->isAppliedOnJob($job->id))
                                @if($haveVideos)
                                    @if($haveMainVideo)
                                        <a href="{{route('my.videoApply.apply', $job->slug)}}" class="btn apply btn-block" id="btn_aplicar" data-toggle="modal" data-target="#aply_modal">
                                            <i class="fa fa-paper-plane" aria-hidden="true"></i> {{__('Apply Now')}}
                                        </a>
                                    @else
                                        <a href="{{ route('my.videos.apply', ['video_id'=>0, 'slug'=>$job->slug]) }}" class="btn apply delete-btn">{{__("Set main video")}}</a>
                                    @endif
                                @else
                                <a href="{{ route('my.videos.apply', ['video_id'=>0, 'slug'=>$job->slug]) }}" class="btn apply delete-btn">{{__("Record video")}}</a>
                                @endif
                            @endif                                                
                        @endif
                    </div>  
                    <div class="row mt-0 mb-0">
                        <div class="col-md-3">
                            <label class="btn-floating btn-lg"><i class="fa fa-share-alt"></i></label>
                        </div>
                        <div id="social_buttons" class="inbox col-md-9" style="display: flex; justify-content: space-around;" onload="prueba();" >
                            {{-- hay que llamar el metodo que carga los botones de compartir en redes sociales --}}
                            {{-- hay que mandar la url mas el view --}}
                        </div>
                        @push('scripts')        
                            @include('includes.share_profile')
                            <script type="text/javascript">                
                                $("#social_buttons").ready(function(){
                                    var url = {!! json_encode(Request::fullUrl()) !!};
                                    loadButtons(url);
                                });
                            </script>
                        @endpush
                    </div>                  
                </div>
            </div>
            <div class="jobButtons row" style="display: flex; justify-content: center;">
                
                <div>
                    <a href="{{route('email.to.friend', $job->slug)}}" class="btn btn-secondary" style="margin: 0">
                        <i class="fa fa-envelope" aria-hidden="true"></i> {{__('Email to Friend')}}
                    </a>
                    @if(!(Auth::guard('company')->user() || Auth::guard('recruiter')->user()))    
                        @if(Auth::check() && Auth::user()->isFavouriteJob($job->slug)) 
                            <a href="{{route('remove.from.favourite', $job->slug)}}" class="btn btn-secondary" style="margin: 0">
                                <i class="fa fa-floppy-o" aria-hidden="true"></i> {{__('Favourite Job')}} 
                            </a> 
                        @else 
                            <a href="{{route('add.to.favourite', $job->slug)}}" class="btn btn-secondary" style="margin: 0">
                                <i class="fa fa-floppy-o" aria-hidden="true"></i> {{__('Add to Favourite')}}
                            </a> 
                        @endif
                    @endif
                    
                    @if( (Auth::guard('company')->check() || Auth::guard('recruiter')->check()) && $invite===true)               
                        <a href="{{route('show.invite.candidate', [$job->id] )}}" class="btn btn-secondary" data-toggle="modal" data-target="#invite_modal"><i class="fa fa-address-book" aria-hidden="true"></i> {{__('Invite Candidate')}}</a>
                    @endif
                    
                    <a href="{{route('report.abuse', $job->slug)}}" class="btn btn-secondary report" style="margin: 0">
                        <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> {{__('Report Abuse')}}
                    </a>
                    

                </div>
            </div>
        </div>
            @if (!empty($job->videoJobURL))
            <div>
                <div class="videobox">
                    <h3>{{__('Video Job Description')}}</h3>
                    <video width="100%" height="300px" class="video" style="max-height: 300px; background-color: black;" src="https://filescloik.s3.us-east-2.amazonaws.com/videoscompany/{{$job->videoJobURL}}" controls type='video/mp4 codecs="avc1.42E01E, mp4a.40.2"'>
                    </video>
                </div>
            </div>
            @endif
                <!-- Job Description start -->
                <div class="job-header">
                    <div class="contentbox">
                        <h3>{{__('Job Description')}}</h3>
                        
                        <p>{!! str_replace("\"", "'",$job->description) !!}</p>
                        <!-- <p>{{ $job->description }}</p>  -->

                        <hr>
                        <h3>{{__('Skills Required')}}</h3>
                        <ul class="skillslist">
                            {!!$job->getJobSkillsList()!!}
                        </ul>
                    </div>
                </div>
                <!-- Job Description end --> 

                <!-- related jobs start -->
                <div class="relatedJobs">
                    <h3>{{__('Related Jobs')}}</h3>
                    <ul class="searchList">
                        @if(isset($relatedJobs) && count($relatedJobs))
                        @foreach($relatedJobs as $relatedJob)
                        <?php $relatedJobCompany = $relatedJob->getCompany(); ?>
                        @if(null !== $relatedJobCompany)
                        <!--Job start-->
                        <li>
                            <div class="row">
                                <div class="col-md-8 col-sm-8">
                                    <div class="jobimg"><a href="{{route('job.detail', [$relatedJob->slug])}}" title="{{$relatedJob->title}}">
                                            {{$relatedJobCompany->printCompanyImage()}}
                                        </a></div>
                                    <div class="jobinfo">
                                        <h3><a href="{{route('job.detail', [$relatedJob->slug])}}" title="{{$relatedJob->title}}">{{$relatedJob->title}}</a></h3>
                                        <div class="companyName"><a href="{{route('company.detail', $relatedJobCompany->slug)}}" title="{{$relatedJobCompany->name}}">{{$relatedJobCompany->name}}</a></div>
                                        <div class="location">
                                            <label class="fulltime">{{$relatedJob->getJobType('job_type')}}</label>
                                            <label class="partTime">{{$relatedJob->getJobShift('job_shift')}}</label>   - <span>{{$relatedJob->getCity('city')}}</span></div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="col-md-4 col-sm-4">
                                    <div class="listbtn"><a href="{{route('job.detail', [$relatedJob->slug])}}">{{__('View Detail')}}</a></div>
                                </div>
                            </div>
                            <p>{{str_limit(strip_tags($relatedJob->description), 150, '...')}}</p>
                        </li>
                        <!--Job end--> 
                        @endif
                        @endforeach
                        @endif

                        <!-- Job end -->
                    </ul>
                </div>            </div>
            <!-- related jobs end -->

            <div class="col-md-4"> 
        
                @if ($job->confidential==0)
                <div class="companyinfo">
                    <div class="companylogo"><a href="{{route('company.detail',$company->slug)}}">{{$company->printCompanyImage()}}</a></div>
                    <div class="title"><a href="{{route('company.detail',$company->slug)}}">{{$company->name}}</a></div>
                    <div class="ptext">{{$company->getLocation()}}</div>
                    <div class="opening">
                        <a href="{{route('company.detail',$company->slug)}}">
                            {{App\Company::countNumJobs('company_id', $company->id)}} {{__('Current Jobs Openings')}}
                        </a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                @else
                    
                @endif
                <!-- Job Detail start -->
                <div class="job-header">
                    <div class="jobdetail">
                        <h3>{{__('Job Detail')}}</h3>
                        <ul class="jbdetail">
                            <li class="row">
                                <div class="col-md-4 col-xs-5">{{__('Location')}}</div>
                                <div class="col-md-8 col-xs-7">
                                    @if((bool)$job->is_freelance)
                                    <span>Freelance</span>
                                    @else
                                    <span>{{$job->getLocation()}}</span>
                                    @endif
                                </div>
                            </li>
                            @if ($job->confidential==0)
                                <li class="row">
                                    <div class="col-md-5 col-xs-5">{{__('Company')}}</div>
                                    <div class="col-md-7 col-xs-7"><a href="{{route('company.detail', $company->id)}}">{{$company->name}}</a></div>
                                </li>    
                            @else
                                
                            @endif
                            
                            <li class="row">
                                    <div class="col-md-5 col-xs-5">{{__('Type')}}</div>
                                    <div class="col-md-7 col-xs-7"><span class="permanent">{{$job->getJobType('job_type')}}</span></div>
                            </li>
                            <li class="row">
                                <div class="col-md-5 col-xs-5">{{__('Shift')}}</div>
                                <div class="col-md-7 col-xs-7"><span class="freelance">{{$job->getJobShift('job_shift')}}</span></div>
                            </li>
                            <li class="row">
                                <div class="col-md-5 col-xs-5">{{__('Career Level')}}</div>
                                <div class="col-md-7 col-xs-7"><span>{{$job->getCareerLevel('career_level')}}</span></div>
                            </li>
                            <li class="row">
                                <div class="col-md-5 col-xs-5">{{__('Positions')}}</div>
                                <div class="col-md-7 col-xs-7"><span>{{$job->num_of_positions}}</span></div>
                            </li>
                            <li class="row">
                                <div class="col-md-5 col-xs-5">{{__('Experience')}}</div>
                                <div class="col-md-7 col-xs-7"><span>{{$job->getJobExperience('job_experience')}}</span></div>
                            </li>
                            <li class="row">
                                <div class="col-md-5 col-xs-5">{{__('Gender')}}</div>
                                <div class="col-md-7 col-xs-7"><span>{{$job->getGender('gender')}}</span></div>
                            </li>
                            <li class="row">
                                <div class="col-md-5 col-xs-5">{{__('Degree')}}</div>
                                <div class="col-md-7 col-xs-7"><span>{{$job->getDegreeLevel('degree_level')}}</span></div>
                            </li>
                            <li class="row">
                                @if ($job->expiry_date)
                                <div class="col-md-5 col-xs-5">{{__('Apply Before')}}</div>
                                <div class="col-md-7 col-xs-7"><span>{{$job->expiry_date->format('M d, Y')}}</span></div>
                                @endif
                                
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Google Map start -->
                <div class="job-header">
                    <div class="jobdetail">
                        <h3>{{__('Google Map')}}</h3>
                        <div class="gmap">
                            {!!$company->map!!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
        <div class="modal fade" id="aply_modal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document" style="width: 80%;">
                <div>
                    <div class="modal-content">
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal end -->
        <!-- Modal -->
        <div class="modal fade" id="invite_modal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document" style="width: 40%;">
                <div>
                    <div class="modal-content">
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal end -->
    @else
        <div class="notLoginJob">
            <div>{{$company->printCompanyImage()}}</div>
            <div class="notLoginBtn">
                <div class="notLoginCompany">                    
                    <label>{{__('View the work published by')}}</label><br>
                    {{$company->name}}
                </div>
                <div>
                    <a href="{{route('login')}}" class="btn btn-primary btn-block">{{__('Sign in')}}</a>
                    <a href="{{route('register')}}" class="btn btn-secondary btn-block">{{__('Register')}}</a>
                </div>
            </div>
        </div>
    @endif
</div>
@include('includes.footer')
@endsection
@push('styles')
<style type="text/css">
    .cantApplyMsg{
        color: red;
    }
    .notLoginJob {
        position: relative;
        width: 40%;
        margin: 0% 30%;
        height: 300px;
        padding: 1rem;
        display: flex;
        justify-content: center;
    }

    .notLoginJob > div:first-child {
        position: absolute;
        height: 80px;
        width: 80px;
        background-color: white;
        border: solid #05236C;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
    }

    .notLoginJob > div:first-child > img {
        border-radius: 50%
    }

    .notLoginBtn {
        width: 100%;
        padding: 1rem;
        padding-top: 50px;
        background-color: white;
        border-radius: 3px;
        margin-top: 40px;
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        align-content: space-around;
    }
    .notLoginBtn > div {
        width: 100% !important;
        text-align: center;
    }

    .notLoginCompany {
        font-size: large;
    }

    .notLoginCompany > label {
        font-size: x-small;
    }

    .view_more{display:none !important;}

  .videobox {
      padding: 35px;
      background-color: white;
      margin: 35px 0;
      border: 1px solid #e4e4e4;
  }

  .videobox h3 {
      font-size: 24px;
      font-weight: 700;
      color: #18a7ff;
      margin-bottom: 10px;
  }
</style>
@endpush
@push('scripts') 
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> --}}
<script>
  $(document).ready(function ($) {
      $("form").on('click',function () {
          $(this).find(":input").filter(function () {
              return !this.value;
          }).attr("disabled", "disabled");
          return true;
      });
      $("form").find(":input").prop("disabled", false);

      $(".view_more_ul").each(function () {
          if ($(this).height() > 100)
          {
              $(this).css('height', 100);
              $(this).css('overflow', 'hidden');
              //alert($( this ).next());
              $(this).next().removeClass('view_more');
          }
      });
              
  });
</script> 
@endpush