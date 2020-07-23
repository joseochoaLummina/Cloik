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
    #favourites {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
        grid-auto-rows: 7rem;
        overflow-y: auto;
        min-height: 0;
        min-width: 0;
    }

    #favourites > div {
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

    #favourites > div > div {
        padding: 0.35rem 1rem;
    }

    #favourites .head {
        background-color: #00335e;
        color: white;
        border-radius: 3px 3px 0 0;
        display: grid;
        grid-template-columns: 20% 70%;
        gap: 2.5%;
        align-items: center;
    }

    #favourites .comment {
        border: solid 1px #80808040;
        margin: 0.5rem;
    }

    #favourites .head img {
        border-radius: 50%;
    }

    #favourites .head a {
        color: white;
        text-decoration: none;
    }

    #favourites .footer {
        display: grid;
        grid-template-columns: 50% 50%;
        justify-content: center;
        align-items: center;
    }

    #favourites .footer > div {
        font-size: smaller;
    }

    .deleteButton {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .deleteButton > a {
        background-color: red;
        color: white;
        padding: 5px;
        border-radius: 5px;
        line-height: initial;

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
                    <h3>{{__('Favourites Candidates')}}</h3>
                    <div id="favourites">
                        @foreach($users as $key) 
                        <div>
                            <div class="head">
                                <img src="{{asset('/')}}user_images/{{$key->image}}">
                                @if (Auth::guard('recruiter')->user())
                                    <a href="{{route('recruiter.user.profile', $key->user_id)}}">{{$key->first_name}} {{$key->last_name}}</a>
                                @elseif(Auth::guard('company')->user())
                                    <a href="{{route('user.profile', $key->user_id)}}">{{$key->first_name}} {{$key->last_name}}</a>
                                @endif
                            </div>
                            @if($key->job != null)
                            <div class="comment">
                                {{__('Favourite to')}}: {{$key->job}}
                            </div>
                            @endif
                            @if (Auth::guard('recruiter')->check())
                                @if (Auth::guard('recruiter')->user()->recruiterType())
                                <div class="deleteButton"><a href="{{route('recruiter.delete.from.favourites', $key->user_id)}}">{{__('Delete from favourites')}}</a></div>
                                @endif                                
                            @elseif (Auth::guard('company')->check())
                                <div class="deleteButton"><a href="{{route('company.delete.from.favourites', $key->user_id)}}">{{__('Delete from favourites')}}</a></div>
                            @endif
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