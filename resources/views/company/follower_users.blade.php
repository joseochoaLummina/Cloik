@extends('layouts.app')
@section('content') 
<!-- Header start --> 
@include('includes.header') 
<!-- Header end --> 
<!-- Inner Page Title start --> 
@include('includes.inner_page_title', ['page_title'=>__('Company Followers')]) 
<!-- Inner Page Title end -->
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
                    <h3>{{__('Company Followers')}}</h3>
                    <ul class="searchList">
                        <!-- job start --> 
                        @if(isset($users) && count($users))
                        @foreach($users as $user)
                        <li>
                            <div class="row">
                                <div class="col-md-9 col-sm-9">
                                    <div class="jobimg">{{$user->printUserImage(100, 100)}}</div>
                                    <div class="jobinfo">
                                        @if(Auth::guard('company')->user() )
                                            <h3><a href="{{route('user.profile', $user->id)}}">{{$user->getName()}}</a></h3>
                                        @else
                                            <h5><a href="{{route('recruiter.user.profile', $user->id)}}">{{$user->getName()}}</a></h5>
                                        @endif
                                        <div class="location"> {{$user->getLocation()}}</div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="col-md-3 col-sm-3">
                                    @if(Auth::guard('company')->user())
                                        <div class="listbtn"><a href="{{route('user.profile', $user->id)}}">{{__('View Profile')}}</a></div>
                                    @else
                                        <div class="listbtn"><a href="{{route('recruiter.user.profile', $user->id)}}">{{__('View Profile')}}</a></div>
                                    @endif
                                </div>
                            </div>
                            <p>{{str_limit($user->getProfileSummary('summary'),150,'...')}}</p>
                        </li>
                        <!-- job end --> 
                        @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@include('includes.footer')
@endsection