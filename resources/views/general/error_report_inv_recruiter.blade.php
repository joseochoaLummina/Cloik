@extends('layouts.app')
@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end -->
<!-- Inner Page Title start -->
<!-- Inner Page Title end -->
<div class="listpgWraper">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12" style="display: flex;justify-content: center;">
                <div style="width: 70%;background-color: white;padding: 3em;border-radius: 3px;">
                    <div style="font-size: x-large;color: #05226e;font-weight: bold;">{{__('Thanks')}}</div>
                    <div style="margin: 1rem 0;">
                        <p style="margin: 1rem 0;">{{__('We appreciate you have notified us of the error and now that you are here we invite you to meet Cloik.')}}</p>

                        <p style="margin: 1rem 0;">{{__('We are an artificial intelligence assisted recruitment platform and through which we make selective recommendations, online language exams and more available to our users.')}}</p>

                        <p style="margin: 1rem 0;">{{__('We continue to grow and why not be part of the platform and take every step of the hand.')}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('includes.footer')
@endsection