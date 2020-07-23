@extends('layouts.app')
@section('content') 
<!-- Header start --> 
@include('includes.header') 
<!-- Header end --> 
<!-- Inner Page Title start --> 
@include('includes.inner_page_title', ['page_title'=>__('Job Seekers')]) 
<!-- Inner Page Title end -->
<div class="listpgWraper">
    <div class="container">
        @include('flash::message')
        <form action="{{route('job.seeker.list')}}" method="get">
            <!-- Page Title start -->
            <div class="pageSearch">
                <div class="row">
                    <div class="col-md-3">
                        @if(Auth::guard('company')->check())
                        <a href="{{ route('post.job') }}" class="btn"><i class="fa fa-file-text" aria-hidden="true"></i> {{__('Post a job')}}</a>
                        @else
                        <a href="{{url('my-profile#cvs')}}" class="btn"><i class="fa fa-file-text" aria-hidden="true"></i> {{__('Upload Your Resume')}}</a>
                        @endif

                    </div>
                    <div class="col-md-9">
                        <div class="searchform">
                            <div class="row">
                                <div class="col-md-{{((bool)$siteSetting->country_specific_site)? 5:3}}">
                                    <input type="text" name="search" value="{{Request::get('search', '')}}" class="form-control" placeholder="{{__('Enter Skills or job seeker details')}}" />
                                </div>
                                <div class="col-md-2"> {!! Form::select('functional_area_id[]', ['' => __('Select Functional Area')]+$functionalAreas, Request::get('functional_area_id', null), array('class'=>'form-control', 'id'=>'functional_area_id')) !!} </div>


                                @if((bool)$siteSetting->country_specific_site)
                                {!! Form::hidden('country_id[]', Request::get('country_id[]', $siteSetting->default_country_id), array('id'=>'country_id')) !!}
                                @else
                                <div class="col-md-2">
                                    {!! Form::select('country_id[]', ['' => __('Select Country')]+$countries, Request::get('country_id', $siteSetting->default_country_id), array('class'=>'form-control', 'id'=>'country_id')) !!}
                                </div>
                                @endif

                                {{-- <div class="col-md-2">
                                    <span id="state_dd">
                                        {!! Form::select('state_id[]', ['' => __('Select State')], Request::get('state_id', null), array('class'=>'form-control', 'id'=>'state_id')) !!}
                                    </span>
                                </div> --}}
                                <div class="col-md-2">
                                    <span id="city_dd">
                                        {!! Form::select('city_id[]', ['' => __('Select City')], Request::get('city_id', null), array('class'=>'form-control', 'id'=>'city_id')) !!}
                                    </span>
                                </div>
                                <div class="col-md-1">
                                    <button type="submit" class="btn"><i class="fa fa-search" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Page Title end -->
        </form>
        <form action="{{route('job.seeker.list')}}" method="get">
            <!-- Search Result and sidebar start -->
            <div class="row"> @include('includes.job_seeker_list_side_bar')
                {{-- <div class="col-md-3 col-sm-6 pull-right">
                    <!-- Sponsord By -->
                    <div class="sidebar">
                        <h4 class="widget-title">{{__('Sponsord By')}}</h4>
                        <div class="gad">{!! $siteSetting->listing_page_vertical_ad !!}</div>
                    </div>
                </div> --}}
                <div class="col-md-9 col-sm-12"> 
                    <!-- Search List -->
                    <ul class="searchList">
                        <!-- job start --> 
                        @if(isset($jobSeekers) && count($jobSeekers))
                        @foreach($jobSeekers as $jobSeeker)
                        <li>
                            <div class="row">
                                <div class="col-md-9 col-sm-9">
                                    <div class="jobimg">{{$jobSeeker->printUserImage(100, 100)}}</div>
                                    <div class="jobinfo">
                                        @if(Auth::guard("company")->check())
                                            <h3><a href="{{route('user.profile', $jobSeeker->id)}}">{{$jobSeeker->getName()}}</a></h3>
                                        @elseif(Auth::guard("recruiter")->check())
                                            <h3><a href="{{route('recruiter.user.profile', $jobSeeker->id)}}">{{$jobSeeker->getName()}}</a></h3>
                                        @endif
                                        <div class="location"> {{$jobSeeker->getLocation()}}</div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="col-md-3 col-sm-3">
                                    @if(Auth::guard("company")->check())
                                        <div class="listbtn"><a href="{{route('user.profile', $jobSeeker->id)}}">{{__('View Profile')}}</a></div>
                                    @elseif(Auth::guard("recruiter")->check())
                                        <div class="listbtn"><a href="{{route('recruiter.user.profile', $jobSeeker->id)}}">{{__('View Profile')}}</a></div>
                                        @if(!Auth::guard('recruiter')->user()->recruiterType())
                                            <div class="listbtn"><a href="{{route('recruiter.show.modal.jobs', [$jobSeeker->id])}}"  data-toggle="modal" data-target="#invite_modal_jobs">{{__('Recommend ')}}</a></div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <p>{{str_limit($jobSeeker->getProfileSummary('summary'),150,'...')}}</p>
                        </li>
                        <!-- job end --> 
                        @endforeach
                        @endif
                    </ul>

                    <!-- Pagination Start -->
                    <div class="pagiWrap">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="showreslt">
                                    {{__('Showing Pages')}} : {{ $jobSeekers->firstItem() }} - {{ $jobSeekers->lastItem() }} {{__('Total')}} {{ $jobSeekers->total() }}
                                </div>
                            </div>
                            <div class="col-md-7 text-right">
                                @if(isset($jobSeekers) && count($jobSeekers))
                                {{ $jobSeekers->appends(request()->query())->links() }}
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- Pagination end --> 
                    <div class=""><br />{!! $siteSetting->listing_page_horizontal_ad !!}</div>

                    <!-- Modal -->
                    <div class="modal fade" id="invite_modal_jobs" role="dialog" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog">                            
                            <div class="modal-content">

                            </div>
                        </div>
                    </div>         
                </div>
            </div>
        </form>
    </div>
</div>
@include('includes.footer')
@endsection
@push('styles')
<style type="text/css">
    .searchList li .jobimg {
        min-height: 80px;
    }
    .hide_vm_ul{
        height:110px;
        overflow:hidden;
    }
    .hide_vm{
        display:none !important;
    }
    .view_more{
        cursor:pointer;
    }
    /* modal */
    .commentBox{
        margin-top: 12px;
    }

    textarea{
        max-width:343.6px;
    }

    
    

</style>
@endpush
@push('scripts') 
<script>
    $(document).ready(function ($) {
        $("form").on('submit',function () {
            $(this).find(":input").filter(function () {
                return !this.value;
            }).attr("disabled", "disabled");
            return true;
        });
        $("form").find(":input").prop("disabled", false);

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

        $('#invite_modal_jobs').on('hidden.bs.modal', function () {
            location.reload();
        });
    });    

    function displayBox(id,userId){
        let modalJob=document.querySelector(`#job_li_${id}`);
        modalJob.innerHTML+=
        `<div class="row commentBox" >\
            <div class="col-md-8 col-sm-8">\
                <textarea id="comment" name="comment" rows="3" cols="45" placeholder="¿Por que recomiendas este usuario?"></textarea>\
            </div>\

            <div class="col-md-4 col-sm-4">\
                <div class="listbtn"><a href="javascript:;" onclick="send(${id},${userId});">{{__('Send')}}</a></div>\
            </div>\

        </div>`;

        let btn=document.querySelector(`.boxBtn${id}`);
        btn.innerHTML=`<div class="listbtn"><a href="javascript:;" onclick="removeBox(${id},${userId});"> {{__("Cancel")}} </a></div>`;
    }

    function removeBox(id,userId){
        let box=document.querySelector(`#job_li_${id} > .commentBox`);
        box.parentNode.removeChild(box);

        let btn=document.querySelector(`.boxBtn${id}`);
        btn.innerHTML=`<div class="listbtn"><a href="javascript:;" onclick="displayBox(${id},${userId});"> {{__("Select")}} </a></div>`;
    }

    function send(id,userId){
        let comment=$('#comment').val();

        $.post("{{ route('recruiter.recommend.candidate') }}", {user:userId, job:id, msg:comment, _method: 'POST', _token: '{{ csrf_token() }}'})
            .done(function(response){
                location.reload();
            }).fail(function(e){
                console.log(e);
            })
        ;
    }

    

</script>
@include('includes.country_state_city_js')
@endpush