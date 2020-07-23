<div>
    @if (!Auth::user()->verified)
        <div class="danger">
            <h5 style="color: #ffff">{{__('Verify your account in your email')}}</h5>
        </div>
    @endif
</div>
<ul class="row profilestat">
    <li class="col-md-4 col-sm-4 col-xs-6">
        <a href="{{ route('view.public.profile', Auth::user()->id) }}">
            <div class="inbox">
                <i class="fa fa-eye" aria-hidden="true"></i>
                <h6>{{Auth::user()->num_profile_views}}</h6>
                <strong>{{__('Profile Views')}}</strong>
            </div>
        </a>
    </li>
    <li class="col-md-4 col-sm-4 col-xs-6">
        <a href="{{route('my.followings')}}">
            <div class="inbox">
                <i class="fa fa-user-o" aria-hidden="true"></i>
                <h6>{{Auth::user()->countFollowings()}}</h6>
                <strong>{{__('Followings')}}</strong>
            </div>
        </a>
    </li>
    <li class="col-md-4 col-sm-4 col-xs-6">
        <a href="{{route('my.user.meetings')}}">
            <div class="inbox">
                <i class="fa fa-handshake-o" aria-hidden="true"></i>
                <h6>{{Auth::user()->countPendingMeetings()}}</h6>
                <strong>{{__('Pending meetings')}}</strong>
            </div>
        </a>
    </li>
    <li class="col-md-4 col-sm-4 col-xs-6">
        <a href="{{route('my.center.message', ['messageType'=>0])}}">
            <div class="inbox">
                <i class="fa fa-envelope-o" aria-hidden="true"></i>
                <h6>{{Auth::user()->countMessageNotRead()}}</h6>
                <strong>{{__('Messages')}}</strong>
            </div>
        </a>
    </li>
    <li class="col-md-4 col-sm-4 col-xs-6">
        <a href="{{ route('my.job.applications') }}">
            <div class="inbox">
                <i class="fa fa-desktop" aria-hidden="true"></i>
                <h6>{{Auth::user()->currentApplications()}}</h6>
                <strong>{{__('Mis Aplicaciones')}}</strong>
            </div>
        </a>
    </li>
    <li class="col-md-4 col-sm-4 col-xs-6">
        <a href="{{route('my.videos.apply', ['video_id'=>0])}}">
            <div class="inbox">
                <i class="fa fa-film" aria-hidden="true"></i>
                <h6>{{Auth::user()->countMyVideoCV()}} {{__('of')}} 4</h6>
                <strong>{{__('Videos Cv')}}</strong>
            </div>
        </a>
    </li>
    <li class="col-md-12 col-sm-12 col-xs-12 invite" id="openInvite">
        <div class="inbox"> 
            <i class="fa fa-envelope-o" aria-hidden="true"></i>
            <h6>¡Invita a tus amigos!</h6>
            <strong>{{__('Share')}}</strong>
        </div>
    </li>
</ul>
@push('scripts')
    <script>
        $('#openInvite').on('click', function(){
            $('#shareModal').modal({
                show: true
            });
        });
    </script>
@endpush