{!! APFrmErrHelp::showErrorsNotice($errors) !!}
@include('flash::message')
<div class="form-body">        
    {!! Form::hidden('id', null) !!}
    <div class="form-group {!! APFrmErrHelp::hasError($errors, 'company_id') !!}" id="company_id_div">
        {!! Form::label('company_id', 'Company', ['class' => 'bold']) !!}                    
        {!! Form::select('company_id', ['' => 'Select Company']+$companies, null, array('class'=>'form-control', 'id'=>'company_id')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'company_id') !!}                                       
    </div>
    <div class="form-group {!! APFrmErrHelp::hasError($errors, 'title') !!}">
        {!! Form::label('title', 'Job title', ['class' => 'bold']) !!}
        {!! Form::text('title', null, array('class'=>'form-control', 'id'=>'title', 'placeholder'=>'Job title')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'title') !!}
    </div>
    <div class="form-group {!! APFrmErrHelp::hasError($errors, 'description') !!}">
        {!! Form::label('description', 'Job description', ['class' => 'bold']) !!}
        {!! Form::textarea('description', null, array('class'=>'form-control', 'id'=>'description', 'placeholder'=>'Job description')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'description') !!}
    </div>
    <div class="form-group {!! APFrmErrHelp::hasError($errors, 'skills') !!}">
        {!! Form::label('skills', 'Job skills', ['class' => 'bold']) !!}
        <?php
        $skills = old('skills', $jobSkillIds);
        ?>
        {!! Form::select('skills[]', $jobSkills, $skills, array('class'=>'form-control select2-multiple', 'id'=>'skills', 'multiple'=>'multiple')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'skills') !!}
    </div>
    <div class="form-group {!! APFrmErrHelp::hasError($errors, 'country_id') !!}" id="country_id_div">
        {!! Form::label('country_id', 'Country', ['class' => 'bold']) !!}                    
        {!! Form::select('country_id', ['' => 'Select Country']+$countries, old('country_id', (isset($job))? $job->country_id:0), array('class'=>'form-control', 'id'=>'country_id')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'country_id') !!}                                       
    </div>
    <div class="form-group {!! APFrmErrHelp::hasError($errors, 'state_id') !!}" id="state_id_div">
        {!! Form::label('state_id', 'State', ['class' => 'bold']) !!}                    
        <span id="state_dd">
            {!! Form::select('state_id', ['' => 'Select State'], null, array('class'=>'form-control', 'id'=>'state_id')) !!}
        </span>
        {!! APFrmErrHelp::showErrors($errors, 'state_id') !!}                                       
    </div>
    <div class="form-group {!! APFrmErrHelp::hasError($errors, 'city_id') !!}" id="city_id_div">
        {!! Form::label('city_id', 'City', ['class' => 'bold']) !!}                    
        <span id="city_dd">
            {!! Form::select('city_id', ['' => 'Select City'], null, array('class'=>'form-control', 'id'=>'city_id')) !!}
        </span>
        {!! APFrmErrHelp::showErrors($errors, 'city_id') !!}                                       
    </div>
    <div class="form-group {!! APFrmErrHelp::hasError($errors, 'is_freelance') !!}">
        {!! Form::label('is_freelance', 'Is Freelance?', ['class' => 'bold']) !!}
        <div class="radio-list">
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
                Yes </label>
            <label class="radio-inline">
                <input id="is_freelance_no" name="is_freelance" type="radio" value="0" {{$is_freelance_2}}>
                No </label>
        </div>
        {!! APFrmErrHelp::showErrors($errors, 'is_freelance') !!}
    </div>
    {{-- <div class="form-group {!! APFrmErrHelp::hasError($errors, 'career_level_id') !!}" id="career_level_id_div">
        {!! Form::label('career_level_id', 'Career level', ['class' => 'bold']) !!}                    
        {!! Form::select('career_level_id', ['' => 'Select Career level']+$careerLevels, null, array('class'=>'form-control', 'id'=>'career_level_id')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'career_level_id') !!}                                       
    </div> --}}
    {{-- <div class="form-group {!! APFrmErrHelp::hasError($errors, 'salary_from') !!}" id="salary_from_div">
        {!! Form::label('salary_from', 'Salary From', ['class' => 'bold']) !!}                    
        {!! Form::number('salary_from', null, array('class'=>'form-control', 'id'=>'salary_from')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'salary_from') !!}                                       
    </div>
    <div class="form-group {!! APFrmErrHelp::hasError($errors, 'salary_to') !!}" id="salary_to_div">
        {!! Form::label('salary_to', 'Salary To', ['class' => 'bold']) !!}                    
        {!! Form::number('salary_to', null, array('class'=>'form-control', 'id'=>'salary_to')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'salary_to') !!}                                       
    </div>
    <div class="form-group {!! APFrmErrHelp::hasError($errors, 'salary_currency') !!}" id="salary_currency_div">
        {!! Form::label('salary_currency', 'Salary Currency', ['class' => 'bold']) !!}                    
        {!! Form::select('salary_currency', ['' => 'Select Salary Currency']+$currencies, null, array('class'=>'form-control', 'id'=>'salary_currency')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'salary_currency') !!}                                       
    </div>
    <div class="form-group {!! APFrmErrHelp::hasError($errors, 'salary_period_id') !!}" id="salary_period_id_div">
        {!! Form::label('salary_period_id', 'Salary Period', ['class' => 'bold']) !!}                    
        {!! Form::select('salary_period_id', ['' => 'Select Salary Period']+$salaryPeriods, null, array('class'=>'form-control', 'id'=>'salary_period_id')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'salary_period_id') !!}                                       
    </div> --}}
   
    {{-- <div class="form-group {!! APFrmErrHelp::hasError($errors, 'functional_area_id') !!}" id="functional_area_id_div">
        {!! Form::label('functional_area_id', 'Functional Area', ['class' => 'bold']) !!}                    
        {!! Form::select('functional_area_id', ['' => 'Select Functional Area']+$functionalAreas, null, array('class'=>'form-control', 'id'=>'functional_area_id')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'functional_area_id') !!}                                       
    </div> --}}
    <div class="form-group {!! APFrmErrHelp::hasError($errors, 'job_type_id') !!}" id="job_type_id_div">
        {!! Form::label('job_type_id', 'Job Type', ['class' => 'bold']) !!}                    
        {!! Form::select('job_type_id', ['' => 'Select Job Type']+$jobTypes, null, array('class'=>'form-control', 'id'=>'job_type_id')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'job_type_id') !!}                                       
    </div>
    <div class="form-group {!! APFrmErrHelp::hasError($errors, 'job_shift_id') !!}" id="job_shift_id_div">
        {!! Form::label('job_shift_id', 'Job Shift', ['class' => 'bold']) !!}                    
        {!! Form::select('job_shift_id', ['' => 'Select Job Shift']+$jobShifts, null, array('class'=>'form-control', 'id'=>'job_shift_id')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'job_shift_id') !!}                                       
    </div>
    <div class="form-group {!! APFrmErrHelp::hasError($errors, 'num_of_positions') !!}" id="num_of_positions_div">
        {!! Form::label('num_of_positions', 'Positions#', ['class' => 'bold']) !!}                    
        {!! Form::select('num_of_positions', ['' => 'Select Positions#']+MiscHelper::getNumPositions(), null, array('class'=>'form-control', 'id'=>'num_of_positions')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'num_of_positions') !!}                                       
    </div>
    <div class="form-group {!! APFrmErrHelp::hasError($errors, 'gender_id') !!}" id="gender_id_div">
        {!! Form::label('gender_id', 'Gender', ['class' => 'bold']) !!}                    
        {!! Form::select('gender_id', ['' => __('No preference')]+$genders, null, array('class'=>'form-control', 'id'=>'gender_id')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'gender_id') !!}                                       
    </div>
    <div class="form-group {!! APFrmErrHelp::hasError($errors, 'expiry_date') !!}">
        {!! Form::label('expiry_date', 'Job expiry date', ['class' => 'bold']) !!}
        {!! Form::text('expiry_date', null, array('class'=>'form-control datepicker', 'id'=>'expiry_date', 'placeholder'=>'Job expiry date', 'autocomplete'=>'off')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'expiry_date') !!}
    </div>
    <div class="form-group {!! APFrmErrHelp::hasError($errors, 'degree_level_id') !!}" id="degree_level_id_div">
        {!! Form::label('degree_level_id', 'Required Degree Level', ['class' => 'bold']) !!}                    
        {!! Form::select('degree_level_id', ['' => 'Select Required Degree Level']+$degreeLevels, null, array('class'=>'form-control', 'id'=>'degree_level_id')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'degree_level_id') !!}                                       
    </div>
    {{-- <div class="form-group {!! APFrmErrHelp::hasError($errors, 'job_experience_id') !!}" id="job_experience_id_div">
        {!! Form::label('job_experience_id', 'Required job experience', ['class' => 'bold']) !!}                    
        {!! Form::select('job_experience_id', ['' => 'Select Required job experience']+$jobExperiences, null, array('class'=>'form-control', 'id'=>'job_experience_id')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'job_experience_id') !!}                                       
    </div> --}}
    <div class="col-md-6">
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'confidential') !!}" >
            {!! Form::label('confidential', 'Job confidential?', ['class' => 'bold']) !!}  
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
                    YES </label>
                <label class="radio-inline">
                    <input id="confidential_no" name="confidential" type="radio" value="0" {{$confidential_2}}>
                    NO </label>
            </div>
            {!! APFrmErrHelp::showErrors($errors, 'is_freelance') !!} </div>
    </div>
    <div class="col-md-12" id="diVRecomendacion">
        <div class="divR" id="divR">
            <h5 class="bold">Recommendations</h5>
            <button id="more"class="more btn-default">+</button>
        </div>
        <div id="divCreado" class="divCreado">
            
        </div>
        
    </div>
    <div class="form-actions">
        {!! Form::button('Update <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>', array('id'=>"envia",'class'=>'btn btn-large btn-primary', 'type'=>'submit')) !!}
    </div>
</div>
@push('css')
<style type="text/css">
    .datepicker>div {
        display: block;
    }
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

</style>
@endpush
@push('scripts')
@include('admin.shared.tinyMCEFront') 
<script type="text/javascript">
    $(document).ready(function () {
        $('.select2-multiple').select2({
            placeholder: "Select Required Skills",
            allowClear: true
        });
        $(".datepicker").datepicker({
            autoclose: true,
            format: 'yyyy-m-d'
        });
        $('#country_id').on('change', function (e) {
            e.preventDefault();
            filterDefaultStates(0);
        });
        $(document).on('change', '#state_id', function (e) {
            e.preventDefault();
            filterDefaultCities(0);
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
        filterDefaultStates(<?php echo old('state_id', (isset($job)) ? $job->state_id : 0); ?>);         
    });
    function filterDefaultStates(state_id)
    {
        var country_id = $('#country_id').val();
        if (country_id != '') {
            $.post("{{ route('filter.default.states.dropdown') }}", {country_id: country_id, state_id: state_id, _method: 'POST', _token: '{{ csrf_token() }}'})
                    .done(function (response) {
                        $('#default_state_dd').html(response);
                        filterDefaultCities(<?php echo old('city_id', (isset($job)) ? $job->city_id : 0); ?>);
                    });
        }
    }
    function filterDefaultCities(city_id)
    {
        var state_id = $('#state_id').val();
        if (state_id != '') {
            $.post("{{ route('filter.default.cities.dropdown') }}", {state_id: state_id, city_id: city_id, _method: 'POST', _token: '{{ csrf_token() }}'})
                    .done(function (response) {
                        $('#default_city_dd').html(response);
                    });
        }
    }
</script>
@endpush