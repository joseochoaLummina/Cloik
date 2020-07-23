<div class="col-md-3 col-sm-6"> 
    <!-- Side Bar start -->
    <div class="sidebar">
        <input type="hidden" name="search" value="{{Request::get('search', '')}}"/>
        <!-- Jobs By Title -->
        {{--
        <div class="widget">
            <h4 class="widget-title">{{__('Jobs By Title')}}</h4>
            <ul class="optionlist view_more_ul">
                @if(isset($jobTitlesArray) && count($jobTitlesArray))
                @foreach($jobTitlesArray as $key=>$jobTitle)
                <li>
                    @php
                    $checked = (in_array($jobTitle, Request::get('job_title', array())))? 'checked="checked"':'';
                    @endphp
                    <input type="checkbox" name="job_title[]" id="job_title_{{$key}}" value="{{$jobTitle}}" {{$checked}} >
                    <label for="job_title_{{$key}}"></label>
                    {{$jobTitle}} <span>{{App\Job::countNumJobs('title', $jobTitle)}}</span> </li>

                @endforeach
                @endif
            </ul> 
            <!-- title end --> 
            <span class="text text-primary view_more hide_vm">{{__('View More')}}</span> </div> --}}

       {{-- <!-- Jobs By Country -->
        <div class="widget">
            <h4 class="widget-title">{{__('Jobs By Country')}}</h4>
            <ul class="optionlist view_more_ul">
                @if(isset($countryIdsArray) && count($countryIdsArray))
                @foreach($countryIdsArray as $key=>$country_id)
                @php
                $country = App\Country::where('country_id','=',$country_id)->lang()->active()->first();			  
                @endphp
                @if(null !== $country)
                @php
                $checked = (in_array($country->country_id, Request::get('country_id', array())))? 'checked="checked"':'';
                @endphp
                <li>
                    <input type="checkbox" name="country_id[]" id="country_{{$country->country_id}}" value="{{$country->country_id}}" {{$checked}}>
                    <label for="country_{{$country->country_id}}"></label>
                    {{$country->country}} <span>{{App\Job::countNumJobs('country_id', $country->country_id)}}</span> </li>
                @endif
                @endforeach
                @endif
            </ul>
            <span class="text text-primary view_more hide_vm">{{__('View More')}}</span> </div>
        <!-- Jobs By Country end-->  --}}


        <!-- Jobs By State -->
        {{-- <div class="widget">
            <h4 class="widget-title">{{__('Jobs By State')}}</h4>
            <ul class="optionlist view_more_ul">
                @if(isset($stateIdsArray) && count($stateIdsArray))
                @foreach($stateIdsArray as $key=>$state_id)
                @php
                $state = App\State::where('state_id','=',$state_id)->lang()->active()->first();			  
                @endphp
                @if(null !== $state)
                @php
                $checked = (in_array($state->state_id, Request::get('state_id', array())))? 'checked="checked"':'';
                @endphp
                <li>
                    <input type="checkbox" name="state_id[]" id="state_{{$state->state_id}}" value="{{$state->state_id}}" {{$checked}}>
                    <label for="state_{{$state->state_id}}"></label>
                    {{$state->state}} <span>{{App\Job::countNumJobs('state_id', $state->state_id)}}</span> </li>
                @endif
                @endforeach
                @endif
            </ul>
            <span class="text text-primary view_more hide_vm">{{__('View More')}}</span> </div> --}}
        <!-- Jobs By State end--> 


        <!-- Jobs By City -->
        <div class="widget">
            <h4 class="widget-title">{{__('Jobs By City')}}</h4>
            <ul class="optionlist view_more_ul">
                @if(isset($cityIdsArray) && count($cityIdsArray))
                @foreach($cityIdsArray as $key=>$city_id)
                @php
                $city = App\City::where('city_id','=',$city_id)->lang()->active()->first();	
                    if(count(collect($city)->toArray()) === 0) {
                        $city = App\City::where('city_id','=',$city_id)->isDefault()->active()->first();
                    }		  
                @endphp
                @if(null !== $city)
                @php
                $checked = (in_array($city->city_id, Request::get('city_id', array())))? 'checked="checked"':'';
                @endphp
                <li>
                    <input type="checkbox" name="city_id[]" id="city_{{$city->city_id}}" value="{{$city->city_id}}" {{$checked}}>
                    <label for="city_{{$city->city_id}}"></label>
                    {{$city->city}} <span>{{App\Job::countNumJobs('city_id', $city->city_id)}}</span> </li>
                @endif
                @endforeach
                @endif
            </ul>
            <span class="text text-primary view_more hide_vm">{{__('View More')}}</span> </div>
        <!-- Jobs By City end--> 

        <!-- Jobs By Industry -->
        <div class="widget">
            <h4 class="widget-title">{{__('Jobs By Industry')}}</h4>
            <ul class="optionlist view_more_ul">
                @if(isset($industryIdsArray) && count($industryIdsArray))
                @foreach($industryIdsArray as $key=>$industry_id)
                @php
                $industry = App\Industry::where('id','=',$industry_id)->lang()->active()->first();
                    if(count(collect($industry)->toArray()) === 0) {
                        $industry = App\Industry::where('id','=',$industry_id)->isDefault()->active()->first();
                    }
                @endphp
                @if(null !== $industry)
                @php
                $checked = (in_array($industry->id, Request::get('industry_id', array())))? 'checked="checked"':'';
                @endphp
                <li>
                    <input type="checkbox" name="industry_id[]" id="industry_{{$industry->id}}" value="{{$industry->id}}" {{$checked}}>
                    <label for="industry_{{$industry->id}}"></label>
                    <p style="padding-right: 15px;">{{$industry->industry}}</p> <span>{{App\Job::countNumJobs('industry_id', $industry->id)}}</span> </li>
                @endif
                @endforeach
                @endif
            </ul>
            <span class="text text-primary view_more hide_vm">{{__('View More')}}</span> </div>
        <!-- Jobs By Industry end --> 

        <!-- Jobs By Skill -->
        <div class="widget">
            <h4 class="widget-title">{{__('Jobs By Skill')}}</h4>
            <ul class="optionlist view_more_ul">
                @if(isset($skillIdsArray) && count($skillIdsArray))
                @foreach($skillIdsArray as $key=>$job_skill_id)
                @php
                $jobSkill = App\JobSkill::where('job_skill_id','=',$job_skill_id)->lang()->active()->first();
                @endphp
                @if(null !== $jobSkill)

                @php
                $checked = (in_array($jobSkill->job_skill_id, Request::get('job_skill_id', array())))? 'checked="checked"':'';
                @endphp
                <li>
                    <input type="checkbox" name="job_skill_id[]" id="job_skill_{{$jobSkill->job_skill_id}}" value="{{$jobSkill->job_skill_id}}" {{$checked}}>
                    <label for="job_skill_{{$jobSkill->job_skill_id}}"></label>
                    {{$jobSkill->job_skill}} <span>{{App\Job::countNumJobs('job_skill_id', $jobSkill->job_skill_id)}}</span> </li>
                @endif
                @endforeach
                @endif
            </ul>
            <span class="text text-primary view_more hide_vm">{{__('View More')}}</span> </div>
        <!-- Jobs By Industry end --> 

        <div class="widget">
            <!-- button -->
            <div class="searchnt">
                <button type="submit" class="btn"><i class="fa fa-search" aria-hidden="true"></i> {{__('Search Jobs')}}</button>
            </div>
            <!-- button end--> 
        </div>
        <!-- Side Bar end --> 
    </div>
</div>