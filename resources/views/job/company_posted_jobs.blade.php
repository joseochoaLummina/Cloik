@extends('layouts.app')
@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end -->
<!-- Inner Page Title start -->
@include('includes.inner_page_title', ['page_title'=>__('Company Posted Jobs')])
<!-- Inner Page Title end -->
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
                    <h3>{{__('Company Posted Jobs')}}</h3>
                    <ul class="searchList">
                        <!-- job start -->
                        @if(isset($jobs) && count($jobs))
                        @foreach($jobs as $job)
                        @php $company = $job->getCompany(); @endphp
                        @if(null !== $company)
                        <li id="job_li_{{$job->id}}">
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
                                    <div class="clearfix"></div>
                                </div>
                                <div class="col-md-4 col-sm-4">
                                @if(Auth::guard('company')->user() || Auth::guard('recruiter')->user()->recruiterType())
                                    <div class="listbtn"><a href="{{route('edit.front.job', [$job->id])}}">{{__('Edit')}}</a></div>
                                    @if (Auth::guard('recruiter')->user())
                                    <div class="listbtn"><a href="{{route('recruiter.list.applied.users', [$job->id])}}">{{__('List Candidates')}}</a></div>
                                    @else
                                    <div class="listbtn"><a href="{{route('list.applied.users', [$job->id])}}">{{__('List Candidates')}}</a></div>
                                    @endif
                                    <div class="listbtn"><a href="javascript:;" onclick="deleteJob({{$job->id}});">{{__('Delete')}}</a></div>
                                @else
                                    <div class="listbtn"><a href="{{route('recruiter.list.applied.users', [$job->id])}}">{{__('List Candidates')}}</a></div>
                                @endif
                                </div>
                            </div>
                            <!-- <p>{{str_limit(strip_tags($job->description), 150, '...')}}</p> -->
                            <p>{!! str_limit(strip_tags($job->description), 150, '...') !!}</p>
                        </li>
                        <!-- job end -->
                        @endif
                        @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@include('includes.footer')
@endsection
@push('scripts')
<script src="https://sdk.amazonaws.com/js/aws-sdk-2.528.0.min.js"></script>
<script type="text/javascript">
    function deleteJob(id) {
        var msg = "{{__('Are you sure?')}}";
        if (confirm(msg)) {
            $.post("{{ route('delete.front.job') }}", {
                    id: id,
                    _method: 'DELETE',
                    _token: '{{ csrf_token() }}'
                })
                .done(function(response) {
                    if (response.status == 'ok') {
                        $('#job_li_' + id).remove();
                        eliminarVideo(response.videoUrl);

                    } else {
                        alert('Request Failed!');
                    }
                });
        }
    }

    function eliminarVideo(url) {
        AWS.config.update({
            // useAccelerateEndpoint: true,
            accessKeyId: ' AKIAQYEJSJ2ZH2ZFRBXG',
            secretAccessKey: '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'
        });
        AWS.config.region = 'us-east-2';
        var bucket = new AWS.S3();
        var params = {
            Bucket: 'filescloik/videoscompany',
            Key: decodeURIComponent(url)
        };
        bucket.deleteObject(params, function(err, data) {
            if (data) {
                console.log(data);
            }
            else {
                console.error(err);
            }
        });

    }
</script>
@endpush