@extends('layouts.app')
@section('content') 
<!-- Header start --> 
@include('includes.header_verifi')

<!-- Inner Page Title start --> 
@include('includes.inner_page_title_profile', ['page_title'=>__('Dashboard')]) 
<!-- Inner Page Title end -->
<div class="listpgWraper">
    <div class="container">@include('flash::message')
        <h1>{{__('Complete your profile')}}</h1>
    {!! Form::model($user, array('id'=>'regForm','method' => 'put', 'route' => array('update.my.profile'), 'class' => 'form', 'files'=>true)) !!}
    <div class="userccount">
        <div class="formpanel"> 
            <!-- Personal Information -->
            @include('user.inc.profileCheck')
        </div>
    </div>
    {!! Form::close() !!}

</div>
@include('includes.footer')
@endsection
{{-- start css --}}
@push('styles')
<style>
    .userccount {
        border: 0px !important;
    }

    .formpanel::-webkit-scrollbar {
        width: 5px !important;
    }

    .userccount p
    { 
        text-align:left !important;
    }

    /* Style the form */
    #regForm {
    background-color: #ffffff;
    margin: 30px auto;
    width: 100%;
    min-width: 300px;
    }

    /* Style the input fields */
    input {
    padding: 10px;
    width: 100%;
    font-size: 17px;
    font-family: Raleway;
    border: 1px solid #aaaaaa;
    }

    .fileImg{

    }
</style>
@endpush
{{-- end css --}}

{{-- start script --}}
@push('scripts')
<script>
</script>
{{-- end script --}}
@include('includes.immediate_available_btn')
@endpush