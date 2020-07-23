<div class="col-md-3 col-sm-4">
    <ul class="usernavdash" id="menu-nav">
        <li id="recruiterhome"><a href="{{route('recruiter.home')}}"><i class="fa fa-tachometer" aria-hidden="true"></i> {{__('Dashboard')}}</a></li>
        <li id="recruiterprofile"><a href="{{ route('recruiter.profile') }}"><i class="fa fa-user" aria-hidden="true"></i> {{__('Edit recruiter profile')}}</a></li>
        @if (Auth::guard('recruiter')->user()->recruiterType())
        {{-- <li id="companyprofile"><a href="{{ route('recruiter.company.profile') }}"><i class="fa fa-user" aria-hidden="true"></i> {{__('Company Profile')}}</a></li> --}}
        <li id="postjob"><a href="{{ route('post.job') }}"><i class="fa fa-desktop" aria-hidden="true"></i> {{__('Post a job')}}</a></li>
        @endif
        <li id="postedjobs"><a href="{{ route('recruiter.posted.jobs') }}"><i class="fa fa-desktop" aria-hidden="true"></i> {{__('Company Jobs')}}</a></li>{{-- Validacion de reclutador --}}
        <li id=""><a href="{{route('job.seeker.list')}}"><i class="fa fa-users" aria-hidden="true"></i> {{__("Candidates")}}</a></li>
        {{-- <!-- <li id="companymessages"><a href="{{route('recruiter.messages')}}"><i class="fa fa-envelope-o" aria-hidden="true"></i> {{__('Recruiter Messages')}}</a></li> --> --}}
        <li id="companyfavourites"><a href="{{route('recruiter.company.favourites')}}"><i class="fa fa-address-book" aria-hidden="true"></i> {{__('Company Favourites')}}</a></li>
        <li id="companyblacklist"><a href="{{route('recruiter.show.blacklist')}}"><i class="fa fa-address-book" aria-hidden="true"></i> {{__('Company Blacklist')}}</a></li>{{-- Validacion de reclutador --}}
        <li id="companyfollowers"><a href="{{route('recruiter.company.followers')}}"><i class="fa fa-user-o" aria-hidden="true"></i> {{__('Company Followers')}}</a></li>
        {{-- <li id="companycentermessage">
            <a href="{{route('company.center.message', ['messageType'=>0])}}">
                <i class="fa fa-envelope-o" aria-hidden="true"></i> 
                {{__('Message Center')}}
                @if(Auth::guard('company')->user()->countMessageNotRead() > 0)<div class="pend pMessage">{{Auth::guard('company')->user()->countMessageNotRead()}}</div>@endif
            </a>
        </li> --}}
        <li id="companymeetings">
            <a href="{{route('recruiter.company.meetings')}}">
                <i class="fa fa-handshake-o" aria-hidden="true"></i> 
                {{__('Company Meetings')}}
                @if(Auth::guard('recruiter')->user()->countPendingMeetings() > 0)<div class="pend pMessage">{{Auth::guard('recruiter')->user()->countPendingMeetings()}}</div>@endif
            </a>{{-- Validacion de reclutador --}}
        </li>
        <li id=""><a href="{{ route('recruiter.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-sign-out" aria-hidden="true"></i> {{__('Logout')}}</a>
            <form id="logout-form" action="{{ route('recruiter.logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
        </li>
    </ul>
    
    @push('scripts')
        <script>
            $("#menu-nav").ready(function() {
                var url = {!! json_encode(Request::route()->getName()) !!};
                var routes=["recruiter.home", "recruiter.profile", "recruiter.post.job", 
                "posted.jobs", "company.center.message",  "company.favourites", "company.blacklist", "company.followers", "company.meetings"];

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