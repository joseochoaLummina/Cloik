<div class="header">
    <div class="container">
        <div class="row">
            <div class="col-md-2 col-sm-3 col-xs-12"> <a href="{{url('/home-profile')}}" class="logo"><img src="{{ asset('/') }}sitesetting_images/thumb/{{ $siteSetting->site_logo }}" alt="{{ $siteSetting->site_name }}" /></a>
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="col-md-10 col-sm-12 col-xs-12"> 

                <!-- Nav start -->
                <div class="navbar navbar-default" role="navigation">
                    <div class="navbar-collapse collapse" id="nav-main">
                        <ul class="nav navbar-nav">
                            @if(Auth::check())
                            <li class="{{ Request::url() == route('contact.us') ? 'active' : '' }}"><a href="{{ route('contact.us') }}">{{__('Contact us')}}</a> </li>
                            <li class="dropdown userbtn"><a href="">{{Auth::user()->printUserImage()}}</a>
                                <ul class="dropdown-menu">
                                    
                                    <li><a href="{{url('/home-profile')}}"><i class="fa fa-user" aria-hidden="true"></i> {{__('Complete your profile')}}</a> </li>
                                    
                                    <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();"><i class="fa fa-sign-out" aria-hidden="true"></i> {{__('Logout')}}</a> </li>
                                    <form id="logout-form-header" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </ul>
                            </li>
                            @endif @if(Auth::guard('company')->check())
                            <li class="{{ Request::url() == route('contact.us.company') ? 'active' : '' }}"><a href="{{ route('contact.us.company') }}">{{__('Contact us')}}</a> </li>
                            <li class="dropdown userbtn"><a href="">{{Auth::guard('company')->user()->printCompanyImage()}}</a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{route('company.home.profile')}}"><i class="fa fa-user" aria-hidden="true"></i> {{__('Complete your profile')}}</a> </li>
                                    <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();"><i class="fa fa-sign-out" aria-hidden="true"></i> {{__('Logout')}}</a> </li>
                                    <form id="logout-form-header" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </ul>
                            </li>
                            @endif @if(!Auth::user() && !Auth::guard('company')->user())
                            <li class="postjob dropdown"><a href="{{route('login')}}">{{__('Sign in')}} <span class="caret"></span></a> 

                                <!-- dropdown start -->

                                <ul class="dropdown-menu">
                                    <li><a href="{{route('login')}}">{{__('Sign in')}}</a> </li>
                                    <li><a href="{{route('register')}}">{{__('Register')}}</a> </li>
                                </ul>

                                <!-- dropdown end --> 

                            </li>
                            @endif
                            <li class="dropdown userbtn"><a href="{{url('/')}}"><img src="{{asset('/')}}images/lang.png" alt="" class="userimg" /></a>
                                <ul class="dropdown-menu">
                                    @foreach($siteLanguages as $siteLang)
                                    <li><a href="javascript:;" onclick="event.preventDefault(); document.getElementById('locale-form-{{$siteLang->iso_code}}').submit();">{{$siteLang->native}}</a>
                                        <form id="locale-form-{{$siteLang->iso_code}}" action="{{ route('set.locale') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="locale" value="{{$siteLang->iso_code}}"/>
                                            <input type="hidden" name="return_url" value="{{url()->full()}}"/>
                                            <input type="hidden" name="is_rtl" value="{{$siteLang->is_rtl}}"/>
                                        </form>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                        </ul>

                        <!-- Nav collapes end --> 

                    </div>
                    <div class="clearfix"></div>
                </div>

                <!-- Nav end --> 

            </div>
        </div>

        <!-- row end --> 

    </div>

    <!-- Header container end --> 

</div>
