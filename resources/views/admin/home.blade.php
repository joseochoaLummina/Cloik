@extends('admin.layouts.admin_layout')
@section('content')
<div class="page-content-wrapper"> 
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content" style="background-color:#eef1f5;"> 
        <!-- BEGIN PAGE HEADER-->     
        <!-- BEGIN PAGE BAR -->
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li> <a href="index.html">Home</a> <i class="fa fa-circle"></i> </li>
                <li> <span>{{ $siteSetting->site_name }} Admin Panel</span> </li>
            </ul>
        </div>
        <!-- END PAGE BAR --> 
        <!-- BEGIN PAGE TITLE-->
        <h3 class="page-title"> {{ $siteSetting->site_name }} Admin Panel <small>{{ $siteSetting->site_name }} Admin Panel</small> </h3>
        <!-- END PAGE TITLE--> 
        <!-- END PAGE HEADER-->
        <div class="row">
            <div class="col-md-4 col-sm-4">
            
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                    <a class="dashboard-stat dashboard-stat-v2 graphic-card">
                        <div>
                            <div class="graphic-card-title">
                                <label>Todays Users (G:7d)</label>
                                <span></span>
                                <label>{{ $todayUsersTotal }}</label>
                            </div>
                            <div class="graphic-card-body chart-container">
                                <canvas id="newUsersPerDay" ></canvas>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                    <a class="dashboard-stat dashboard-stat-v2 graphic-card">
                        <div>
                            <div class="graphic-card-title">
                                <label>Active Users (G:6m)</label>
                                <span></span>
                                <label>{{ $activeUsersTotal }}</label>
                                <label></label>
                            </div>
                            <div class="graphic-card-body chart-container">
                                <canvas id="activeUsers" ></canvas>
                            </div>
                        </div>
                    </a>
                </div>

               <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                    <a class="dashboard-stat dashboard-stat-v2 graphic-card">
                        <div>
                            <div class="graphic-card-title">
                                <label>Verified Users (G:6m)</label>
                                <span></span>
                                <label>{{ $verifiedUsersTotal }}</label>
                            </div>
                            <div class="graphic-card-body chart-container">
                                <canvas id="verifiedUsers" ></canvas>
                            </div>
                        </div>
                    </a>
                </div>
                
            </div>

            <div class="col-md-4 col-sm-4">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                    <a class="dashboard-stat dashboard-stat-v2 graphic-card">
                        <div>
                            <div class="graphic-card-title">
                                <label>Active Jobs (G:6m)</label>
                                <span></span>
                                <label>{{ $activeJobsTotal }}</label>
                            </div>
                            <div class="graphic-card-body chart-container">
                                <canvas id="activeJobs"></canvas>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                    <a class="dashboard-stat dashboard-stat-v2 graphic-card">
                        <div>
                            <div class="graphic-card-title">
                                <label>Test Language (G:6m)</label>
                                <span></span>
                                <label>{{ $testLanguageTotal }}</label>
                            </div>
                            <div class="graphic-card-body chart-container">
                                <canvas id="testLanguage" ></canvas>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                    <a class="dashboard-stat dashboard-stat-v2 graphic-card">
                        <div>
                            <div class="graphic-card-title">
                                <label>Today Applicants (G:7d)</label>
                                <span></span>
                                <label>{{ $todayApplicantsTotal }}</label>
                            </div>
                            <div class="graphic-card-body chart-container">
                                <canvas id="jobsApplicantPerDay"></canvas>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <!-- End Estadistics User -->   

            <div class="col-md-4 col-sm-4">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                    <a class="dashboard-stat dashboard-stat-v2 graphic-card">
                        <div>
                            <div class="graphic-card-title">
                                <label>New Companies (G:7d)</label>
                                <span></span>
                                <label>{{ $newCompaniesTotal }}</label>
                            </div>
                            <div class="graphic-card-body chart-container">
                                <canvas id="newCompanyPerDay"></canvas>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                    <a class="dashboard-stat dashboard-stat-v2 graphic-card">
                        <div>
                            <div class="graphic-card-title">
                                <label>Active Companies (G:6m)</label>
                                <span></span>
                                <label>{{ $activeCompaniesTotal }}</label>
                            </div>
                            <div class="graphic-card-body chart-container">
                                <canvas id="activeCompany"></canvas>
                            </div>
                        </div>
                    </a>
                </div>

               <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                    <a class="dashboard-stat dashboard-stat-v2 graphic-card">
                        <div>
                            <div class="graphic-card-title">
                                <label>Verified Companies (G:6m)</label>
                                <span></span>
                                <label>{{ $verifiedCompaniesTotal }}</label>
                            </div>
                            <div class="graphic-card-body chart-container">
                                <canvas id="verifiedCompany"></canvas>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <!-- End Estadistics Company-->

        </div>

        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12"> 
                <a class="dashboard-stat dashboard-stat-v2 blue">
                    <div class="visual"> <i class="fa fa-list"></i> </div>
                        <div class="details">
                            <div class="number"> <span data-counter="counterup" data-value="1349">{{ $videosRecordedTotal }}</span> </div>
                            <div class="desc"> Videos Recorded </div>
                    </div>
                </a>

                <a class="dashboard-stat dashboard-stat-v2 blue">
                    <div class="visual"> <i class="fa fa-list"></i> </div>
                        <div class="details">
                            <div class="number"> <span data-counter="counterup" data-value="1349">{{ $todayJobsTotal }}</span> </div>
                            <div class="desc"> Today New Jobs</div>
                    </div>
                </a>  
            </div>            
            
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12"> 
                <a class="dashboard-stat dashboard-stat-v2 graphic-card">
                    <div>
                        <div class="graphic-card-title">
                            <label>Top 5 Companies (G:6m)</label>
                            <span></span>
                            <!-- <label></label> -->
                        </div>
                        <div class="graphic-card-body chart-container">
                            <canvas id="topCompaniesMonths"></canvas>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-sm-6">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption"> <i class="icon-share font-dark hide"></i> <span class="caption-subject font-dark bold uppercase">Recent Registered Users</span> </div>
                    </div>
                    <div class="portlet-body">
                        <div class="slimScrol">
                            <ul class="feeds">
                                @foreach($recentUsers as $recentUser)
                                <li>
                                    <div class="col1">
                                        <div class="cont">
                                            <div class="cont-col1">
                                                <div class="label label-sm label-info"> <i class="fa fa-check"></i> </div>
                                            </div>
                                            <div class="cont-col2">
                                                <div class="desc"><a href="{{ route('edit.user', $recentUser->id) }}"> {{ $recentUser->name }} ({{ $recentUser->email }}) </a>  - <i class="fa fa-home" aria-hidden="true"></i> {{ $recentUser->getLocation()}}</div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="scroller-footer">
                            <div class="btn-arrow-link pull-right"> <a href="{{ route('list.users') }}">See All Users</a> <i class="icon-arrow-right"></i> </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption"> <i class="icon-share font-dark hide"></i> <span class="caption-subject font-dark bold uppercase">Recent Jobs</span> </div>
                    </div>
                    <div class="portlet-body">
                        <div class="slimScrol">
                            <ul class="feeds">
                                @foreach($recentJobs as $recentJob)
                                <li>
                                    <div class="col1">
                                        <div class="cont">
                                            <div class="cont-col1">
                                                <div class="label label-sm label-info"> <i class="fa fa-check"></i> </div>
                                            </div>
                                            <div class="cont-col2">
                                                <div class="desc"><a href="{{ route('edit.job', $recentJob->id) }}"> {{ str_limit($recentJob->title, 50) }} </a>  - <i class="fa fa-list" aria-hidden="true"></i> {{ $recentJob->getCompany('name') }} - <i class="fa fa-home" aria-hidden="true"></i> {{ $recentJob->getLocation() }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endforeach                  
                            </ul>
                        </div>
                        <div class="scroller-footer">
                            <div class="btn-arrow-link pull-right"> <a href="{{ route('list.jobs') }}">See All Jobs</a> <i class="icon-arrow-right"></i> </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END CONTENT BODY --> 
</div>
@endsection
@push('scripts')
<script src="{{asset('/')}}/js/Chart.bundle.min.js"></script>
<script src="{{asset('/')}}/js/graphics.js"></script>

<script type="text/javascript">
    $(function () {
        $('.slimScrol').slimScroll({
            height: '250px',
            railVisible: true,
            alwaysVisible: true
        });
    });

    makeGraph(<?=json_encode($newUsersPerDay)?>,"newUsersPerDay","line");
    makeGraph(<?=json_encode($activeUsers)?>,"activeUsers","line");
    makeGraph(<?=json_encode($verifiedUsers)?>,"verifiedUsers","line");
    makeGraph(<?=json_encode($testLanguage)?>,"testLanguage","line");
    makeGraph(<?=json_encode($newCompanyPerDay)?>,"newCompanyPerDay","line");
    makeGraph(<?=json_encode($activeCompany)?>,"activeCompany","line");
    makeGraph(<?=json_encode($verifiedCompany)?>,"verifiedCompany","line");
    makeGraph(<?=json_encode($jobsApplicantPerDay)?>,"jobsApplicantPerDay","line");
    makeGraph(<?=json_encode($activeJobs)?>,"activeJobs","line");
    var topCompaniesTotal=<?=json_encode($topCompaniesTotal)?>;
    makeGraph(<?=json_encode($topCompaniesMonths)?>,"topCompaniesMonths","line");
</script>
@endpush