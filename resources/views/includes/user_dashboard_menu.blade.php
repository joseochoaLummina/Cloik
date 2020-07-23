<div class="col-md-3 col-sm-4">
    <div class="switchbox">
        <div class="txtlbl">{{__('Immediate Available')}} <i class="fa fa-info-circle" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="{{__('Are you immediate available')}}?" data-original-title="{{__('Are you immediate available')}}?" title="{{__('Are you immediate available')}}?"></i>
        </div>
        <div class="pull-right">
            <label class="switch switch-green"> @php
                $checked = ((bool)Auth::user()->is_immediate_available)? 'checked="checked"':'';
                @endphp
                <input type="checkbox" name="is_immediate_available" id="is_immediate_available" class="switch-input" {{$checked}} onchange="changeImmediateAvailableStatus({{Auth::user()->id}}, {{Auth::user()->is_immediate_available}});">
                <span class="switch-label" data-on="On" data-off="Off"></span> <span class="switch-handle"></span> </label>
        </div>
        <div class="clearfix"></div>
    </div>
    <ul class="usernavdash" id="menu-nav">
        <li id="home" class="active"><a href="{{route('home')}}"><i class="fa fa-tachometer" aria-hidden="true"></i> {{__('Dashboard')}}</a>
        </li>
        <li id="myprofile"><a href="{{ route('my.profile') }}"><i class="fa fa-user" aria-hidden="true"></i> {{__('My Profile')}}</a>
        </li>
        <li><a href="{{ route('view.public.profile', Auth::user()->id) }}"><i class="fa fa-eye" aria-hidden="true"></i> {{__('View Public Profile')}}</a>
        </li>
        <li id="myjobapplications"><a href="{{ route('my.job.applications') }}"><i class="fa fa-desktop" aria-hidden="true"></i> {{__('My Job Applications')}}</a>
        </li>
        <li id="myfavouritejobs"><a href="{{ route('my.favourite.jobs') }}"><i class="fa fa-heart" aria-hidden="true"></i> {{__('My Favourite Jobs')}}</a>
        </li>
        <li><a href="{{url('my-profile#cvs')}}"><i class="fa fa-file-text" aria-hidden="true"></i> {{__('Manage Resume')}}</a>
        </li>
        <li id="myvideosapply"><a href="{{route('my.videos.apply', ['video_id'=>0])}}"><i class="fa fa-film" aria-hidden="true"></i> {{__('My Videos')}}</a>
        </li>
        <li id="mycentermessage">
            <a href="{{route('my.center.message', ['messageType'=>0])}}">
                <i class="fa fa-envelope-o" aria-hidden="true"></i> 
                {{__('Message Center')}} 
                @if(Auth::user()->countMessageNotRead() > 0)<div class="pend pMessage">{{Auth::user()->countMessageNotRead()}}</div>@endif
            </a>
        </li>
        <!-- <li id="myfollowings"><a href="{{route('my.followings')}}"><i class="fa fa-user-o" aria-hidden="true"></i> {{__('My Followings')}}</a>
        </li> -->
        <li id="mylanguagetest"><a href="{{route('my.language.test')}}"><i class="fa fa-language" aria-hidden="true"></i> {{__('Language Test')}}</a>
        </li>
        <li id="myusermeetings">
            <a href="{{route('my.user.meetings')}}">
                <i class="fa fa-handshake-o" aria-hidden="true"></i> 
                {{__('My Meetings')}}
                @if(Auth::user()->countPendingMeetings() > 0)<div class="pend pMessage">{{Auth::user()->countPendingMeetings()}}</div>@endif
            </a>
        </li>
        <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-sign-out" aria-hidden="true"></i> {{__('Logout')}}</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </li>
        @push('scripts')
        <script>
            $("#menu-nav").ready(function() {
                var url = {!! json_encode(Request::route()->getName()) !!};
                var routes=["home", "my.profile", "", "my.job.applications", "my.favourite.job", 
                "", "my.videos.apply", "my.center.message", "my.followings", "my.language.test", "my.user.meetings"];

                routes.forEach(route => {
                    if(route != ""){$("#"+route).removeClass("active");}
                });

                apply = "#"+url.split('.').join('');
                $(apply).addClass("active");
            });
        </script>
        @endpush
    </ul>
    <div class="row">
        <div class="col-md-12">{!! $siteSetting->dashboard_page_ad !!}</div>
    </div>
</div>

@push('styles')
<style>
#mycentermessage > a, #myusermeetings > a {
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