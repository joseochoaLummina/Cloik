<!--Start Modal-->
<div id="modal_recommend_content">  
    <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">{{__("Company's Jobs")}}</h4>
        </div>

        <div class="modal-body">
            <ul class="searchList">
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

                        <div class="col-md-4 col-sm-4 boxBtn{{$job->id}}">
                            <div class="listbtn"><a href="javascript:;" onclick="displayBox({{$job->id}},{{$userId}});" >{{__('Select')}}</a></div>                                                      
                        </div>

                    </div>
                </li>
                @endif
                @endforeach
                @endif
            </ul>                                
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">{{__('Close')}}</button>
        </div>
        
    </div>    
</div>
<!-- End Modal -->