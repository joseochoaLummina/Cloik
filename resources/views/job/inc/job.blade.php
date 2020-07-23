<h5>{{__('Job Details')}}</h5>
@if(isset($job))
    {!! Form::model($job, array('method' => 'put', 'route' => array('update.front.job', $job->id), 'class' => 'form')) !!}
    {!! Form::hidden('idHidden', $job->id) !!}
@else
    {!! Form::open(array('method' => 'post', 'route' => array('store.front.job'), 'class' => 'form')) !!}
    {!! Form::hidden('idHidden', null) !!}
@endif
<div class="row">  
    <h1>@php
        $skills = old('skills', $jobSkillIds);
    @endphp</h1>
    <div class="col-md-12">
        <label for="title"><h6>{{__('Job title')}}</h6></label>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'title') !!}"> {!! Form::text('title', null, array('class'=>'form-control', 'id'=>'title', 'placeholder'=>__('Job title'))) !!}
            {!! APFrmErrHelp::showErrors($errors, 'title') !!} </div>
    </div>
    <div class="col-md-12">
        <label for="description"><h6>{{__('Job Description')}}</h6></label>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'description') !!}"> {!! Form::textarea('description', null, array('class'=>'form-control', 'id'=>'description', 'placeholder'=>__('Job description'))) !!}
            {!! APFrmErrHelp::showErrors($errors, 'description') !!} </div>
    </div>
    <div class="col-md-12">
        <label for="skills"><h6>{{__('Select Required Skills')}}</h6></label>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'skills') !!}">
            <?php
            $skills = old('skills', $jobSkillIds);
            
            ?>
            {!! Form::select('skills[]', $jobSkills, $skills, array('class'=>'form-control select2-multiple', 'id'=>'skills', 'multiple'=>'multiple')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'skills') !!} </div>
    </div>
    <div class="col-md-4">
        <label for="country_id"><h6>{{__('Select Country')}}</h6></label>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'country_id') !!}" id="country_id_div"> {!! Form::select('country_id', ['' => __('Select Country')]+$countries, old('country_id', (isset($job))? $job->country_id:0), array('class'=>'form-control', 'id'=>'country_id')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'country_id') !!} </div>
    </div>
    <div class="col-md-4">
        <label for="state_id"><h6>{{__('Select State')}}</h6></label>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'state_id') !!}" id="state_id_div"> <span id="default_state_dd"> {!! Form::select('state_id', ['' => __('Select State')], null, array('class'=>'form-control', 'id'=>'state_id')) !!} </span> {!! APFrmErrHelp::showErrors($errors, 'state_id') !!} </div>
        
    </div>
    <div class="col-md-4">
        <label for="city_id"><h6>{{__('Select City')}}</h6></label>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'city_id') !!}" id="city_id_div"> <span id="default_city_dd"> {!! Form::select('city_id', ['' => __('Select City')], null, array('class'=>'form-control', 'id'=>'city_id')) !!} </span> {!! APFrmErrHelp::showErrors($errors, 'city_id') !!} </div>
    </div>
    <!-- <div class="col-md-6">
        <label for="salary_from"><h6>{{__('Salary from')}}</h6></label>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'salary_from') !!}" id="salary_from_div"> {!! Form::number('salary_from', null, array('class'=>'form-control', 'id'=>'salary_from', 'placeholder'=>__('Salary from'))) !!}
            {!! APFrmErrHelp::showErrors($errors, 'salary_from') !!} </div>
    </div>
    <div class="col-md-6">
        <label for="salary_to"><h6>{{__('Salary to')}}</h6></label>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'salary_to') !!}" id="salary_to_div">
            {!! Form::number('salary_to', null, array('class'=>'form-control', 'id'=>'salary_to', 'placeholder'=>__('Salary to'))) !!}
            {!! APFrmErrHelp::showErrors($errors, 'salary_to') !!} </div>
    </div> -->
    <!-- <div class="col-md-4">
        <label for="salary_currency"><h6>{{__('Select Salary Currency')}}</h6></label>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'salary_currency') !!}" id="salary_currency_div">
            @php
            $salary_currency = Request::get('salary_currency', (isset($job))? $job->salary_currency:$siteSetting->default_currency_code);
            @endphp

            {!! Form::select('salary_currency', ['' => __('Select Salary Currency')]+$currencies, $salary_currency, array('class'=>'form-control', 'id'=>'salary_currency')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'salary_currency') !!} </div>
    </div> -->
    <!-- <div class="col-md-4">
        <label for="salary_period_id"><h6>{{__('Select salary period')}}</h6></label>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'salary_period_id') !!}" id="salary_period_id_div"> {!! Form::select('salary_period_id', ['' => __('Select Salary Period')]+$salaryPeriods, null, array('class'=>'form-control', 'id'=>'salary_period_id')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'salary_period_id') !!} </div>
    </div> -->
    <!-- <div class="col-md-4">
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'hide_salary') !!}"> 
            <label for="hide_salary"><h6>{{__('Hide Salary?')}}</h6></label>
            <div class="radio-list" id="hide_salary">
                <?php
                $hide_salary_1 = '';
                $hide_salary_2 = 'checked="checked"';
                if (old('hide_salary', ((isset($job)) ? $job->hide_salary : 0)) == 1) {
                    $hide_salary_1 = 'checked="checked"';
                    $hide_salary_2 = '';
                }
                ?>
                <label class="radio-inline">
                    <input id="hide_salary_yes" name="hide_salary" type="radio" value="1" {{$hide_salary_1}}>
                    {{__('Yes')}} </label>
                <label class="radio-inline">
                    <input id="hide_salary_no" name="hide_salary" type="radio" value="0" {{$hide_salary_2}}>
                    {{__('No')}} </label>
            </div>
            {!! APFrmErrHelp::showErrors($errors, 'hide_salary') !!} </div>
    </div> -->
    <!-- <div class="col-md-6">
        <label for="career_level_id"><h6>{{__('Select Career level')}}</h6></label>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'career_level_id') !!}" id="career_level_id_div"> {!! Form::select('career_level_id', ['' => __('Select Career level')]+$careerLevels, null, array('class'=>'form-control', 'id'=>'career_level_id')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'career_level_id') !!} </div>
    </div> -->

    <!-- <div class="col-md-6">
        <label for="functional_area_id"><h6>{{__('Select Functional Area')}}</h6></label>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'functional_area_id') !!}" id="functional_area_id_div"> {!! Form::select('functional_area_id', ['' => __('Select Functional Area')]+$functionalAreas, null, array('class'=>'form-control', 'id'=>'functional_area_id')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'functional_area_id') !!} </div>
    </div> -->
    <div class="col-md-6">
        <label for="job_type_id"><h6>{{__('Select Job Type')}}</h6></label>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'job_type_id') !!}" id="job_type_id_div"> {!! Form::select('job_type_id', ['' => __('Select Job Type')]+$jobTypes, null, array('class'=>'form-control', 'id'=>'job_type_id')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'job_type_id') !!} </div>
    </div>
    <div class="col-md-6">
        <label for="job_shift_id"><h6>{{__('Select Job Shift')}}</h6></label>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'job_shift_id') !!}" id="job_shift_id_div"> {!! Form::select('job_shift_id', ['' => __('Select Job Shift')]+$jobShifts, null, array('class'=>'form-control', 'id'=>'job_shift_id')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'job_shift_id') !!} </div>
    </div>
    <div class="col-md-6">
            <label for="num_of_positions"><h6>{{__('Select number of Positions')}}</h6></label>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'num_of_positions') !!}" id="num_of_positions_div"> {!! Form::select('num_of_positions', ['' => __('Select number of Positions')]+MiscHelper::getNumPositions(), null, array('class'=>'form-control', 'id'=>'num_of_positions')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'num_of_positions') !!} </div>
    </div>
    <div class="col-md-6">
        <label for="gender_id"><h6>{{__('Select Gender')}}</h6></label>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'gender_id') !!}" id="gender_id_div"> {!! Form::select('gender_id', ['' => __('No preference')]+$genders, null, array('class'=>'form-control', 'id'=>'gender_id')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'gender_id') !!} </div>
    </div>
    <div class="col-md-6">
        <label for="expiry_date"><h6>{{__('Job expiry date')}}</h6></label>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'expiry_date') !!}"> {!! Form::text('expiry_date', null, array('class'=>'form-control datepicker', 'id'=>'expiry_date', 'required','placeholder'=>__('Job expiry date'), 'autocomplete'=>'off')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'expiry_date') !!} </div>
    </div>
    <div class="col-md-6">
        <label for="degree_level_id"><h6>{{__('Select Required Degree Level')}}</h6></label>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'degree_level_id') !!}" id="degree_level_id_div"> {!! Form::select('degree_level_id', ['' =>__('Select Required Degree Level')]+$degreeLevels, null, array('class'=>'form-control', 'id'=>'degree_level_id')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'degree_level_id') !!} </div>
    </div>
    <div class="col-md-12">
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'videoJobURL') !!}"> {!! Form::hidden('videoJobURL', null, array('class'=>'form-control', 'id'=>'videoJobURL', 'placeholder'=>__('Video'))) !!}
            {!! APFrmErrHelp::showErrors($errors, 'videoJobURL') !!} </div>
    </div>
    <!-- <div class="col-md-6">
        <label for="job_experience_id"><h6>{{__('Select Required job experience')}}</h6></label>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'job_experience_id') !!}" id="job_experience_id_div"> {!! Form::select('job_experience_id', ['' => __('Select Required job experience')]+$jobExperiences, null, array('class'=>'form-control', 'id'=>'job_experience_id')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'job_experience_id') !!} </div>
    </div> -->
    <div class="col-md-6">
        
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'is_freelance') !!}">
            <label for="is_freelance"><h6>{{__('Is Freelance?')}}</h6></label>
            <div class="radio-list" id="is_freelance">
                <?php
                $is_freelance_1 = '';
                $is_freelance_2 = 'checked="checked"';
                if (old('is_freelance', ((isset($job)) ? $job->is_freelance : 0)) == 1) {
                    $is_freelance_1 = 'checked="checked"';
                    $is_freelance_2 = '';
                }
                ?>
                <label class="radio-inline">
                    <input id="is_freelance_yes" name="is_freelance" type="radio" value="1" {{$is_freelance_1}}>
                    {{__('Yes')}} </label>
                <label class="radio-inline">
                    <input id="is_freelance_no" name="is_freelance" type="radio" value="0" {{$is_freelance_2}}>
                    {{__('No')}} </label>
            </div>
            {!! APFrmErrHelp::showErrors($errors, 'is_freelance') !!} </div>
    </div>
    <div class="col-md-6">
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'confidential') !!}">
            <label for="confidential"><h6>{{__('Job confidential?')}}</h6></label>
            <div class="radio-list" id="confidential">
                <?php
                $confidential_1 = '';
                $confidential_2 = 'checked="checked"';
                if (old('confidential', ((isset($job)) ? $job->confidential : 0)) == 1) {
                    $confidential_1 = 'checked="checked"';
                    $confidential_2 = '';
                }
                ?>
                <label class="radio-inline">
                    <input id="confidential_yes" name="confidential" type="radio" value="1" {{$confidential_1}}>
                    {{__('Yes')}} </label>
                <label class="radio-inline">
                    <input id="confidential_no" name="confidential" type="radio" value="0" {{$confidential_2}}>
                    {{__('No')}} </label>
            </div>
            {!! APFrmErrHelp::showErrors($errors, 'is_freelance') !!} </div>
    </div>
    <div class="col-md-12" id="diVRecomendacion">
        <div class="divR" id="divR">
            <h5>{{__('Recommendations')}}</h5><button id="more"class="more btn-default">+</button>
        </div>
        <div id="divCreado" class="divCreado">
            
        </div>
        
    </div>
    <div class="col-md-12" >
        <div>
            <div class="videobox" id="videoboxTag">
            @if (!empty($job->videoJobURL))
                <h5>{{__('Video Job Description')}}</h5>
                <video id="videoJob" width="100%" height="400px" class="video" style="max-height: 400px; background-color: black;" src="https://filescloik.s3.us-east-2.amazonaws.com/videoscompany/{{$job->videoJobURL}}" controls type='video/mp4 codecs="avc1.42E01E, mp4a.40.2"'>
                </video>
            @endif
            </div>
        </div>
        <div >
            <input type="text" name="recomendations" id="recomendations" style="display:none">
        </div>
    </div>
    <div class="col-md-12">
        <button type="button" class="btn btn-new-video" data-toggle="modal">{{__('Record Video')}} <i class="fa fa-camera"></i></button>

        <div class="alert alert-danger alertWebcam" role="alert" id="alertWebcam">
        {{__("It does not record, please, verify you allow your browser to use your camera and microphone, to know how, please click ")}}
        <a href="javascript:webCam()" class="alert-link">{{__('HERE')}}</a>

    </div>
    <div class="col-md-12">
        <div class="formrow">
            <button id="envia"  class="butt btn-success">{{__('Update Job')}} <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button>
        </div>
    </div>
</div>
{{-- <input type="file" name="image" id="image" style="display:none;" accept="image/*"/> --}}
{!! Form::close() !!}
<hr>
@push('styles')
<style type="text/css">
    .hide{
        display: none;
    }
    .divCreado{
        display: block;
    }
    .butt{
        margin-top: 3%;
        width: 100%;
        color: #fff;
        border-radius: 0;
        padding: 10px;
        font-size: 16px;
        font-weight: 700;
        text-transform: uppercase;
    }
    .button{
        margin: 1%;
        width: 20%;
        color: #fff;
        border-radius: 0;
        padding: 10px;
        font-size: 16px;
        font-weight: 700;
        text-transform: uppercase;
    }
    .recomendacion{
        width: 100%;
    }
    .divR{
        display:flex;
        width: 100%;
        border-bottom: 1px solid gray;
    }

    .divR > h5 {
        width: 95%;
        padding: 0.5rem;
    }

    .divR > button {
        margin-top: 1rem;
        width: 30px;
        height: 30px;
        border: 0px;
    }

    .datepicker>div {
        display: block;
    }
    .more{
        margin-left: 1%;
        width: 100%;
        height: 75%;
        background: #18a7ff;
        color: #fff;
        border-radius: 0;
        padding: 10px;
        font-size: 16px;
        font-weight: 700;
        text-transform: uppercase;
    }
    .NuevaRecomendacion{
        width: 100%;
        margin-top: 2%;
    }

    #divReco {
        display: flex;
        width: 100%;
        flex-wrap: nowrap !important;
    }

    #divReco > input[type=text] {
        width: 90%;
    }

    #divReco > input[type=button] {
        width: 10%;
    }

    #btn_eliminarR {
        border: 0px;
        height: 2rem;
        margin: 1rem 0;
    }

    .alertWebcam{
        display:none;
        line-height:1.5em;
    }

</style>
@endpush
@push('scripts')
@include('includes.tinyMCEFront')
<script type="text/javascript">
    $(document).ready(function () {
        $('.select2-multiple').select2({
            placeholder: "{{__('Select Required Skills')}}",
            allowClear: true
        });
        $(".datepicker").datepicker({
            autoclose: true,
            format: 'yyyy-m-d'
        });
        $('#country_id').on('change', function (e) {
            e.preventDefault();
            filterLangStates(0);
        });
        $(document).on('change', '#state_id', function (e) {
            e.preventDefault();
            filterLangCities(0);
        });
        $(document).on('change', '#city_id', function (e) {
            e.preventDefault();
        });
        $(document).on('click','#more',function(e){
            e.preventDefault();
            var divRow = document.createElement('div');
            var input=document.createElement('input');
            var btn=document.createElement('button');
            var icon = document.createElement('i');

            divRow.id = "divReco";

            icon.className += "fa fa-trash";
        
            btn.classList.add('btn-danger');
            // btn.classList.add('button');
            btn.id="btn_eliminarR";
            btn.appendChild(icon);
            btn.type="button";
            
            input.type="text";
            input.style="margin:1%";
            input.name="recomendation";
            input.classList.add("form-control")
            input.placeholder="{{__('Recommendations')}}";
            
            let creaBtn = divRow.appendChild(btn);
            let nuevo = divRow.insertBefore(input,creaBtn);
            document.getElementById('divCreado').appendChild(divRow);
          })
        $(document).on('click', '#envia', function (e) {
            let recomendacion=document.getElementsByName('recomendation');
            let texto="";
            for (let i = 0; i < recomendacion.length; i++) {
                const valor = recomendacion[i].value;
                texto=texto+valor+"(/&/)";
            }
            document.getElementById("recomendations").value=texto;
        });
        $(document).on('click','#btn_eliminarR',function(){
            $(this).prev().remove();
            $(this).remove();
        })
        var id_job=document.getElementsByName('idHidden')[0].value;
        if (id_job) {
            $.post("{{ route('show.recomendacion') }}", { id_job: id_job, _method: 'POST', _token: '{{ csrf_token() }}' } )
                .done(function (response){
                    
                    if (response[0] != null) {
                        let recomendacion=response[0].split("(/&/)");
                        for (let i = 0; i < recomendacion.length; i++) {
                            
                            if(recomendacion[i]!=""){
                                var divRow = document.createElement('div');
                                var input=document.createElement('input');
                                var btn=document.createElement('button');
                                var icon = document.createElement('i');
                            
                                divRow.id = "divReco";

                                icon.className += "fa fa-trash";

                                btn.classList.add('btn-danger');
                                // btn.classList.add('button');
                                btn.id="btn_eliminarR";
                                btn.appendChild(icon);
                                btn.type="button";
                                
                                input.type="text";
                                input.style="margin:1%";
                                input.name="recomendation";
                                input.classList.add("form-control")
                                input.value=recomendacion[i];
                            }
                            
                            // let creaBtn=document.getElementById('divCreado').appendChild(btn)
                            // let nuevo=document.getElementById('divCreado').insertBefore(input,creaBtn);

                            let creaBtn = divRow.appendChild(btn);
                            let nuevo = divRow.insertBefore(input,creaBtn);
                            document.getElementById('divCreado').appendChild(divRow);
                        }
                    }
                    
            })
        }
        
        filterLangStates(<?php echo old('state_id', (isset($job)) ? $job->state_id : 0); ?>);
    });
    function filterLangStates(state_id){
        var country_id = $('#country_id').val();
        if (country_id != '') {
            $.post("{{ route('filter.lang.states.dropdown') }}", {country_id: country_id, state_id: state_id, _method: 'POST', _token: '{{ csrf_token() }}'})
                    .done(function (response) {
                        $('#default_state_dd').html(response);
                        filterLangCities(<?php echo old('city_id', (isset($job)) ? $job->city_id : 0); ?>);
                    });
        }
    }
    function filterLangCities(city_id){
        var state_id = $('#state_id').val();
        if (state_id != '') {
            $.post("{{ route('filter.lang.cities.dropdown') }}", {state_id: state_id,city_id:city_id, _method: 'POST', _token: '{{ csrf_token() }}'})
                    .done(function (response) {
                        $('#default_city_dd').html(response);
                    });
        }
    }
</script> 
@endpush