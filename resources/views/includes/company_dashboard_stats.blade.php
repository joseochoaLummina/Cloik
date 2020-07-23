@if(!Auth::guard('company')->user()->isVerified(Auth::guard('company')->user()->id))
<diV style="width:100%; background-color: red; color: white; padding: 1rem; margin: 0.5rem 0; border-radius: 3px;">
{{__('Please check your email and verify your account by email')}}</div>
@endif
<ul class="row profilestat">
    <li class="col-md-6 col-sm-4 col-xs-6">
        <a href="{{route('posted.jobs')}}">
            <div class="inbox">
                <i class="fa fa-clock-o" aria-hidden="true"></i>
                <h6>{{Auth::guard('company')->user()->countOpenJobs()}}</h6>
                <strong>{{__('Open Jobs')}}</strong>
            </div>
        </a>
    </li>
    <li class="col-md-6 col-sm-4 col-xs-6">
        <a href="{{route('company.followers')}}">
            <div class="inbox">
                <i class="fa fa-user-o" aria-hidden="true"></i>
                <h6>{{Auth::guard('company')->user()->countFollowers()}}</h6>
                <strong>{{__('Followers')}}</strong>
            </div>
        </a>
    </li>
    <li class="col-md-6 col-sm-4 col-xs-6">
        <a href="{{route('company.center.message', ['messageType'=>0])}}">
            <div class="inbox">
                <i class="fa fa-envelope-o" aria-hidden="true"></i>
                <h6>{{Auth::guard('company')->user()->countMessageNotRead()}}</h6>
                <strong>{{__('Messages')}}</strong>
            </div>
        </a>
    </li>
    <li class="col-md-6 col-sm-4 col-xs-6">
        <a href="{{route('company.recruiters')}}">
            <div class="inbox">
                <i class="fa fa-users" aria-hidden="true"></i>
                <h6>{{Auth::guard('company')->user()->countMasterRecruiter()}} / {{Auth::guard('company')->user()->limitMasterRecruiter()}}</h6>
                <strong>{{__('Master Recruiters')}}</strong>
            </div>
        </a>
    </li>
    <li class="col-md-6 col-sm-4 col-xs-6">
        <a href="{{route('company.recruiters')}}">
            <div class="inbox">
                <i class="fa fa-users" aria-hidden="true"></i>
                <h6>{{Auth::guard('company')->user()->countJrRecruiter()}} / {{Auth::guard('company')->user()->limitJrRecruiter()}}</h6>
                <strong>{{__('Jr Recruiters')}}</strong>
            </div>
        </a>
    </li>
</ul>