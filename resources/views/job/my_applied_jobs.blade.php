@extends('layouts.app')
@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end --> 
<!-- Inner Page Title start -->
@include('includes.inner_page_title', ['page_title'=>__('Applied Jobs')])
<!-- Inner Page Title end -->
<div class="listpgWraper">
    <div class="container">
        <div class="row">
            @include('includes.user_dashboard_menu')

            <div class="col-md-9 col-sm-8"> 
                <div class="myads">
                    <h3>{{__('Applied Jobs')}}</h3>
                    <ul class="searchList">
                        <!-- job start --> 
                        @if(isset($jobs) && count($jobs))
                        @foreach($jobs as $job)
                        @php $company = $job->getCompany(); @endphp
                        @if(null !== $company)
                        <li>
                            <div class="row">
                                <div class="col-md-8 col-sm-8">
                                    <div class="jobimg">{{$company->printCompanyImage()}}</div>
                                    <div class="jobinfo">
                                        <h3><a href="{{route('job.detail', [$job->slug])}}" title="{{$job->title}}">{{$job->title}}</a></h3>
                                        <div class="companyName"><a href="{{route('company.detail', $company->slug)}}" title="{{$company->name}}">{{$company->name}}</a></div>
                                        <div class="location">
                                            <label class="fulltime" title="{{$job->getJobShift('job_shift')}}">{{$job->getJobShift('job_shift')}}</label>
                                            - <span>{{$job->getCity('city')}}</span></div>
                                    </div>
                                    @if ($job->isJobExpired())
                                        <span style="color:red"> {{__('Job is expired')}}</span>                                        
                                    @endif
                                    <div class="clearfix"></div>
                                </div>
                                <div class="col-md-4 col-sm-4">
                                    <div class="listbtn">
                                        <a href="{{route('job.detail', [$job->slug])}}">{{__('View Details')}}</a>
                                        <a class="mini-btn-delete delete-btn" style="margin: 0 !important;" href="{{route('delete.job.apply', [$job->id])}}"><i class="fa fa-trash"></i></a>
                                    </div>
                                </div>
                            </div>
                            <p>{{str_limit(strip_tags($job->description), 150, '...')}}</p>
                        </li>
                        <!-- job end --> 
                        @endif
                        @endforeach
                        @endif
                    </ul>
                </div>
                 <!-- Pagination Start -->
                 <div class="pagiWrap">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="showreslt">
                                {{__('Showing Pages')}} : {{ $jobs->firstItem() }} - {{ $jobs->lastItem() }} {{__('Total')}} {{ $jobs->total() }}
                            </div>
                        </div>
                        <div class="col-md-7 text-right">
                            @if(isset($jobs) && count($jobs))
                            {{ $jobs->appends(request()->query())->links() }}
                            @endif
                        </div>
                    </div>
                </div>
                <!-- Pagination end --> 
                <div class=""><br />{!! $siteSetting->listing_page_horizontal_ad !!}</div>
            </div>
        </div>
    </div>
</div>
@include('includes.footer')
@endsection
@push('scripts')
<script>
    $(document).ready(function ($) {        
        $(".view_more_ul").each(function () {
            if ($(this).height() > 100)
            {
                $(this).addClass('hide_vm_ul');
                $(this).next().removeClass('hide_vm');
            }
        });
        $('.view_more').on('click', function (e) {
            e.preventDefault();
            $(this).prev().removeClass('hide_vm_ul');
            $(this).addClass('hide_vm');
        });

    });
</script>
@include('includes.immediate_available_btn')
@endpush