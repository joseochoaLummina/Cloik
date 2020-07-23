@extends('layouts.app')
@section('content') 
<!-- Header start --> 
@include('includes.header') 
<!-- Header end --> 
<!-- Inner Page Title start --> 
@include('includes.inner_page_title', ['page_title'=>__('Dashboard')]) 
<!-- Inner Page Title end -->
<div class="listpgWraper">
    <div class="container">@include('flash::message')
        <div class="row">
            @include('includes.recruiter_dashboard_menu')
            <div class="col-md-9 col-sm-8"> 
                @if(!Auth::guard('recruiter')->user()->isVerifiedCompany())
                    <diV style="width:100%; background-color: red; color: white; padding: 1rem; margin: 0.5rem 0; border-radius: 3px;">
                    {{__('Please check your email and verify your account by email')}}</div>
                @endif
                <div class="col-md-8 col-sm-12">
                    <ul class="row profilestat">
                        <li class="col-md-6 col-sm-4 col-xs-6">
                            <a href="{{route('recruiter.posted.jobs')}}">
                                <div class="inbox">
                                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                                    <h6>{{Auth::guard('recruiter')->user()->countOpenJobs()}}</h6>
                                    <strong>{{__('Open Jobs')}}</strong>
                                </div>
                            </a>
                        </li>
                        <li class="col-md-6 col-sm-4 col-xs-6">
                            <a href="{{route('recruiter.company.followers')}}">
                                <div class="inbox">
                                    <i class="fa fa-user-o" aria-hidden="true"></i>
                                    <h6>{{Auth::guard('recruiter')->user()->countFollowers()}}</h6>
                                    <strong>{{__('Followers')}}</strong>
                                </div>
                            </a>
                        </li>
                        {{--<li class="col-md-6 col-sm-4 col-xs-6">
                            <a href="{{route('company.messages')}}">
                                <div class="inbox">
                                    <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                    <h6>{{Auth::guard('recruiter')->user()->countRecruiterMessages()}}</h6>
                                    <strong>{{__('Messages')}}</strong>
                                </div>
                            </a>
                        </li> --}}
                    </ul>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="more-visited">
                        <div>{{__('More visited Jobs')}}</div>
                        <div>
                            @foreach($array as $key => $value)
                            <div class="visited-card">
                                <div>
                                    <div class="visited-card-title"><a href="{{route('job.detail', [$value->slug])}}" title="{{$value->title}}">{{$value->title}}</a></div>
                                    <div class="visited-card-duration">{{str_replace('-', '/',substr($value->created_at,0,10))}} - {{str_replace('-', '/',substr($value->expiry_date,0,10))}}</div>
                                </div>
                                <div class="visited-card-value">
                                    <div>{{$value->cantidad}}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('includes.footer')
@endsection
@push('scripts')
@endpush
