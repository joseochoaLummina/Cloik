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
                    <div style="font-size: x-large;color: #05226e;font-weight: bold;">{{__('Sorry')}}</div>
                    <div style="margin: 1rem 0;">
                        <p style="margin: 1rem 0;">
                        {{__('This email is already in use as a company recruiter')}} {{$company_name}}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('includes.footer')
@endsection