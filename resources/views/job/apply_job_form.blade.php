@extends('layouts.app')
@section('content') 
<!-- Header start --> 
@include('includes.header') 
<!-- Header end --> 
<!-- Inner Page Title start --> 
@include('includes.inner_page_title', ['page_title'=>__('Apply on Job')]) 
<!-- Inner Page Title end -->
<div class="listpgWraper">
    <div class="container"> <!--@include('flash::message')-->
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="userccount">
                    <div class="formpanel"> {!! Form::open(array('method' => 'post', 'route' => ['post.apply.job'])) !!} 
                        <!-- Job Information -->
                        <h5>{{$job->title}}</h5>
                        <p>{{__('You can upload a resume optionally')}}</p>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" name="job_slug" id="job_slug" value="{{$job_slug}}" style="display:none">
                                <input type="text" name="videoApply" id="videoApply" value="{{$videoApply}}" style="display:none" >
                                <div class="formrow{{ $errors->has('cv_id') ? ' has-error' : '' }}"> {!! Form::select('cv_id', [''=>__('Select CV')]+$myCvs, null, array('class'=>'form-control', 'id'=>'cv_id')) !!}
                                    @if ($errors->has('cv_id')) <span class="help-block"> <strong>{{ $errors->first('cv_id') }}</strong> </span> @endif </div>
                                @if (!$myCvs)
                                <a href="/my-profile#cvs">{{__('You do not have resumes click to add')}}</a>
                                @endif
                            </div>
                        </div>
                        <br>
                        <input type="submit" class="btn" value="{{__('Apply on Job')}}">
                        {!! Form::close() !!} </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('includes.footer')
@endsection
@push('scripts') 
<script>
    $(document).ready(function () {
        $('#salary_currency').typeahead({
            source: function (query, process) {
                return $.get("{{ route('typeahead.currency_codes') }}", {query: query}, function (data) {
                    console.log(data);
                    data = $.parseJSON(data);
                    return process(data);
                });
            }
        });

    });
</script> 
@endpush