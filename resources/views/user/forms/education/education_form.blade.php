<div class="modal-body">
    <div class="form-body">
        <div class="formrow" id="div_degree_level_id">
            <span style="font-weight: bold;">{{__('Select Degree Level')}}</span>
            <?php
            $degree_level_id = (isset($profileEducation) ? $profileEducation->degree_level_id : null);
            ?>
            {!! Form::select('degree_level_id', [''=>__('Select Degree Level')]+$degreeLevels, $degree_level_id, array('class'=>'form-control', 'id'=>'degree_level_id')) !!}
            <span class="help-block degree_level_id-error"></span> </div>

        <div class="formrow" id="div_degree_title">
            <span style="font-weight: bold;">{{__('Degree Title')}}</span>
            <input class="form-control" id="degree_title" placeholder="{{__('Degree Title')}}" name="degree_title" type="text" value="{{(isset($profileEducation)? $profileEducation->degree_title:'')}}">
            <span class="help-block degree_title-error"></span> </div>

        <div class="formrow" id="div_country_id">
            <span style="font-weight: bold;">{{__('Select Country')}}</span>
            <?php
            $country_id = (isset($profileEducation) ? $profileEducation->country_id : $siteSetting->default_country_id);
            ?>
            {!! Form::select('country_id', [''=>__('Select Country')]+$countries, $country_id, array('class'=>'form-control', 'id'=>'education_country_id')) !!}
            <span class="help-block country_id-error"></span> </div>

        <div class="formrow" id="div_state_id">
            <span style="font-weight: bold;">{{__('Select State')}}</span>
            <span id="default_state_education_dd">
                {!! Form::select('state_id', [''=>__('Select State')], null, array('class'=>'form-control', 'id'=>'education_state_id')) !!}
            </span>
            <span class="help-block state_id-error"></span> </div>

        <div class="formrow" id="div_city_id">
            <span style="font-weight: bold;">{{__('Select City')}}</span>
            <span id="default_city_education_dd">
                {!! Form::select('city_id', [''=>__('Select City')], null, array('class'=>'form-control', 'id'=>'city_id')) !!}
            </span>
            <span class="help-block city_id-error"></span> </div>

        <div class="formrow" id="div_institution">
            <span style="font-weight: bold;">{{__('Institution')}}</span>
            <input class="form-control" id="institution" placeholder="{{__('Institution')}}" name="institution" type="text" value="{{(isset($profileEducation)? $profileEducation->institution:'')}}">
            <span class="help-block institution-error"></span> </div>


        <div class="formrow" id="div_date_completion">
            <span style="font-weight: bold;">{{__('Select Year')}}</span>
            <?php
            $date_completion = (isset($profileEducation) ? $profileEducation->date_completion : null);
            ?>
            {!! Form::select('date_completion', [''=>__('Select Year')]+MiscHelper::getEstablishedIn(), $date_completion, array('class'=>'form-control', 'id'=>'date_completion')) !!}
            <span class="help-block date_completion-error"></span> </div>

    </div>
</div>