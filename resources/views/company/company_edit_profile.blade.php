@extends('layouts.app')
@section('content') 
<!-- Header start --> 
@include('includes.header_verifi') 
<!-- Header end --> 
<!-- Inner Page Title start --> 
@include('includes.inner_page_title_profile', ['page_title'=>__('Company Profile')]) 
<!-- Inner Page Title end -->
<div class="listpgWraper">
    <div class="container">
        <div class="row" style="display: flex; justify-content: center;">
            <div class="col-md-9 col-sm-8"> 
                <div class="row">
                    <div class="col-md-12">
                        <div class="userccount">
                            <div class="formpanel"> @include('flash::message') 
                                <!-- Personal Information -->
                                @include('company.inc.profile')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('includes.footer')
@endsection
@push('styles')
<style type="text/css">
    .userccount p{ text-align:left !important;}
</style>
@endpush