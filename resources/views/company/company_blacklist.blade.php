@extends('layouts.app')
@section('content') 
<!-- Header start --> 
@include('includes.header') 
<!-- Header end --> 
<!-- Inner Page Title start --> 
@include('includes.inner_page_title', ['page_title'=>__('Company Followers')]) 
<!-- Inner Page Title end -->
@push('styles')
    <link href="{{ asset('css/meeting.css') }}" rel="stylesheet">
    <style>
    #blacklist {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
        grid-auto-rows: 20rem;
        overflow-y: auto;
    }

    #blacklist > div {
        width: 100%;
        height: 100%;
        background-color: white;
        border-radius: 3px;
        -webkit-box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.45);
        -moz-box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.45);
        box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.45);
        display: grid;
        grid-template-rows: 1fr 4fr 1fr
    }

    #blacklist > div > div {
        padding: 0.35rem 1rem;
    }

    #blacklist .head {
        background-color: #272727;
        color: white;
        border-radius: 3px 3px 0 0;
        display: grid;
        grid-template-columns: 20% 70%;
        gap: 2.5%;
        align-items: center;
    }

    #blacklist .comment {
        border: solid 1px #80808040;
        margin: 0.5rem;
    }

    #blacklist .head img {
        border-radius: 50%;
    }

    #blacklist .head a {
        color: white;
        text-decoration: none;
    }

    #blacklist .footer {
        display: grid;
        grid-template-columns: 50% 50%;
        justify-content: center;
        align-items: center;
    }

    #blacklist .footer > div {
        font-size: smaller;
    }
    </style>
@endpush
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
                    <h3>{{__('My Blacklist')}}</h3>
                    <div id="blacklist">
                        @foreach($users as $key) 
                        <div>
                            <div class="head">
                                <img src="{{asset('/')}}user_images/{{$key->image}}">
                                @if (Auth::guard('recruiter')->user())
                                    <a href="{{route('recruiter.user.profile', $key->id_user)}}">{{$key->username}}</a>
                                @elseif(Auth::guard('company')->user())
                                    <a href="{{route('user.profile', $key->id_user)}}">{{$key->username}}</a>
                                @endif
                                
                            </div>
                            <div class="comment">{{$key->comentario}}</div>
                            <div class="footer">
                                <div>{{__('Added by:')}}<br>{{$key->recruiter}}</div>
                                @if (Auth::guard('recruiter')->user() && Auth::guard('recruiter')->user()->recruiterType())
                                    <a href="{{route('recruiter.remove.from.blacklist', [$key->id_user, $key->id_empresa])}}" class="btn btn-success">{{__('Delete')}}</a>
                                @endif
                                @if (Auth::guard('company')->user())
                                    <a href="{{route('remove.from.blacklist', [$key->id_user, $key->id_empresa])}}" class="btn btn-success">{{__('Delete')}}</a>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
@include('includes.footer')
@endsection
@push('scripts')
<script>
</script>
@include('includes.immediate_available_btn')
@endpush