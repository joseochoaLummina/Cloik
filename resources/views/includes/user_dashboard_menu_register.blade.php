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
        <li id="myprofile"><a href="{{ route('my.profile') }}"><i class="fa fa-user" aria-hidden="true"></i> {{__('My Profile')}}</a>
        </li>
        <li id="myvideosapply"><a href="{{route('my.videos.apply', ['video_id'=>0])}}"><i class="fa fa-film" aria-hidden="true"></i> {{__('My Videos')}}</a>
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
                "", "my.videos.apply", "my.messages", "my.followings", "my.language.test", "my.user.meetings"];

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