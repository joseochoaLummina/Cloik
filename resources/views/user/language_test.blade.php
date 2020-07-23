@extends('layouts.app')
@section('content') 
<!-- Header start --> 
@include('includes.header') 
<!-- Header end --> 
<!-- Inner Page Title start --> 
@include('includes.inner_page_title', ['page_title'=>__('Language Test')]) 
<!-- Inner Page Title end -->
<div class="listpgWraper">
    <div class="container">
        <div class="row">
            @include('includes.user_dashboard_menu')

            <div class="col-md-9 col-sm-8"> 
                <div class="row">
                    <div class="col-md-12">
                        <div class="userccount">
                            <div class="formpanel"> @include('flash::message') 
                                <!-- Language Test -->
                                <?php 
                                    $data=[
                                        'langs' => $langs,
                                    ]  
                                ?>
                                @include('user.forms.language_test.language_test', $data)
                            </div>
                        </div>
                    </div>
                </div>
                <div class="userccount" style="margin-top: 1rem; text-align: center;">                                        
                    <div style="width: 100%">{{__('Historical Record of Evaluations')}}</div>
                    <div class="userccount my-test-grid noselect">
                        @foreach($logs_test as $lt)
                        <div>
                            <div style="grid-area: title">{{__('Test language')}}</div>
                            <div class="historico_lang" style="grid-area: lang">{{$lt->lang}}</div>
                            <div class="historico_score" style="grid-area: score">{{round($lt->score, 0)}}%</div>
                            <div style="grid-area: create">{{$lt->create_at}}</div>
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
@push('styles')
<style type="text/css">
    .userccount p{ text-align:left !important;}
</style>
@endpush
@push('scripts')
@include('includes.immediate_available_btn')
@endpush