<div class="col-md-3 col-sm-4">
    <ul class="usernavdash" id="menu-nav">
        <li id="companyhome"><a href="{{route('company.home')}}"><i class="fa fa-tachometer" aria-hidden="true"></i> {{__('Dashboard')}}</a></li>
        <li id="companyprofile"><a href="{{ route('company.profile') }}"><i class="fa fa-user" aria-hidden="true"></i> {{__('Company Profile')}}</a></li>
        <li id="companydetail"><a href="{{ route('company.detail', Auth::guard('company')->user()->slug) }}"><i class="fa fa-user" aria-hidden="true"></i> {{__('Company Public Profile')}}</a></li>
        <li id="postjob"><a href="{{ route('post.job') }}"><i class="fa fa-desktop" aria-hidden="true"></i> {{__('Post a job')}}</a></li>
        <li id="postedjobs"><a href="{{ route('posted.jobs') }}"><i class="fa fa-desktop" aria-hidden="true"></i> {{__('Company Jobs')}}</a></li>

        <!-- <li id="companymessages"><a href="{{route('company.messages')}}"><i class="fa fa-envelope-o" aria-hidden="true"></i> {{__('Company Messages')}}</a></li> -->
        <li id="companyRecruiters"><a href="{{route('company.recruiters')}}"><i class="fa fa-users" aria-hidden="true"></i> {{__('Company Recruiters')}}</a></li>
        <li id=""><a href="{{route('job.seeker.list')}}"><i class="fa fa-users" aria-hidden="true"></i> {{__("Candidates")}}</a></li>
        <li id="companyfavourites"><a href="{{route('company.favourites')}}"><i class="fa fa-address-book" aria-hidden="true"></i> {{__('Company Favourites')}}</a></li>
        <li id="companyblacklist"><a href="{{route('company.blacklist')}}"><i class="fa fa-address-book" aria-hidden="true"></i> {{__('Company Blacklist')}}</a></li>
        <li id="companyfollowers"><a href="{{route('company.followers')}}"><i class="fa fa-user-o" aria-hidden="true"></i> {{__('Company Followers')}}</a></li>
        <li id="companycentermessage">
            <a href="{{route('company.center.message', ['messageType'=>0])}}">
                <i class="fa fa-envelope-o" aria-hidden="true"></i> 
                {{__('Message Center')}}
                @if(Auth::guard('company')->user()->countMessageNotRead() > 0)<div class="pend pMessage">{{Auth::guard('company')->user()->countMessageNotRead()}}</div>@endif
            </a>
        </li>
        <li id="companymeetings">
            <a href="{{route('company.meetings')}}">
                <i class="fa fa-handshake-o" aria-hidden="true"></i> 
                {{__('Company Meetings')}}
                @if(Auth::guard('company')->user()->countPendingMeetings() > 0)<div class="pend pMessage">{{Auth::guard('company')->user()->countPendingMeetings()}}</div>@endif
            </a>
        </li>
        <li id=""><a href="{{ route('company.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-sign-out" aria-hidden="true"></i> {{__('Logout')}}</a>
            <form id="logout-form" action="{{ route('company.logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
        </li>
    </ul>
    
    @push('scripts')
        <script>
            $("#menu-nav").ready(function() {
                var url = {!! json_encode(Request::route()->getName()) !!};
                var routes=["company.home", "company.profile", "company.detail", "post.job", 
                "posted.jobs", "company.center.message", "company.favourites", "company.blacklist", "company.followers", "company.meetings"];

                routes.forEach(route => {
                    if(route != ""){$("#"+route).removeClass("active");}
                });

                apply = "#"+url.split('.').join('');
                $(apply).addClass("active");
            });
        </script>
    @endpush
    <div class="row">
        <div class="col-md-12">{!! $siteSetting->dashboard_page_ad !!}</div>
    </div>
</div>
@push('styles')
<style>
#companycentermessage > a, #companymeetings > a {
    display: flex;
    flex-wrap: nowrap;
    align-items: center;
}

.pend {
    width: 20px;
    height: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 50px;
    padding: 0.2rem;
    margin: 0 0.5rem;
}

.pMessage {
    background-color: #00335e;
    color: white;
}

.pNotification {
    background-color: #15600b;
    color: white;
}

.pInvitation {
    background-color: #600b5b;
    color: white;
}

.pAlerts {
    background-color: #b90707;
    color: white;
}
</style>
@endpush