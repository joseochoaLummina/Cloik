@php 
if (Auth::user()->image=='' || null || "") {
    $required='required';
} else {
    $required='';
}
@endphp

<h5>{{__('Personal Information')}}</h5>
<h6>{{__('Profile Image')}}</h6>
<div class="row">
    {{-- ************************ Select Profile Image  *********************** --}}
    <div class="col-md-6">
        <div class="formrow"> {{ ImgUploader::print_image("user_images/$user->image", 100, 100) }} </div>
        <div id="thumbnail"></div>
    </div>
    <div class="col-md-6">
        <div class="formrow">
            <span class="btn btn-default" id="selectImgBtn"> {{__('Select Profile Image')}}
                <input type="file" name="image" id="image" {{$required}} class="fileImg">            
            {!! APFrmErrHelp::showErrors($errors, 'image') !!}
        </div>
    </div>    
</div>
<div class="row">
    {{-- ************************ First Name  *********************** --}}
    <div class="col-md-6">
        <h6>{{__('Names')}}</h6>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'first_name') !!}"> {!! Form::text('first_name', null, array('class'=>'form-control', 'id'=>'first_name', 'placeholder'=>__('Names'))) !!}
            {!! APFrmErrHelp::showErrors($errors, 'first_name') !!} </div>
    </div>
    {{-- ************************ Last Name  *********************** --}}
    <div class="col-md-6">
        <h6>{{__('Surnames')}}</h6>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'last_name') !!}"> {!! Form::text('last_name', null, array('class'=>'form-control', 'id'=>'last_name', 'placeholder'=>__('Surname'))) !!}
            {!! APFrmErrHelp::showErrors($errors, 'last_name') !!}</div>
    </div>
    {{-- ************************ Email  *********************** --}}
    <div class="col-md-6">
        <h6>{{__('Email')}}</h6>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'email') !!}"> {!! Form::text('email', null, array('class'=>'form-control', 'id'=>'email', 'placeholder'=>__('Email'))) !!}
            {!! APFrmErrHelp::showErrors($errors, 'email') !!} </div>
    </div>
    {{-- ************************ Gender  *********************** --}}
    <div class="col-md-6">
        <h6>{{__('Gender')}}</h6>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'gender_id') !!}"> {!! Form::select('gender_id', [''=>__('Select Gender')]+$genders, null, array('class'=>'form-control','required', 'id'=>'gender_id')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'gender_id') !!} </div>
    </div>
    {{-- ************************ Marital Status  *********************** --}}
    <div class="col-md-6">
        <h6>{{__('Marital Status')}}</h6>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'marital_status_id') !!}"> {!! Form::select('marital_status_id', [''=>__('Select Marital Status')]+$maritalStatuses, null, array('class'=>'form-control','required', 'id'=>'marital_status_id')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'marital_status_id') !!} </div>
    </div>
    {{-- ************************ Select Country  *********************** --}}
    <div class="col-md-6">
        <h6>{{__('Country')}}</h6>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'country_id') !!}">
            <?php $country_id = old('country_id', (isset($user) && (int) $user->country_id > 0) ? $user->country_id : $siteSetting->default_country_id); ?>
            {!! Form::select('country_id', [''=>__('Select Country')]+$countries, $country_id, array('class'=>'form-control','required', 'id'=>'country_id')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'country_id') !!} </div>
    </div>
    {{-- ************************ Select State  *********************** --}}
    <div class="col-md-6">
        <h6>{{__('State')}}</h6>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'state_id') !!}"> <span id="state_dd"> {!! Form::select('state_id', [''=>__('Select State')], null, array('class'=>'form-control','required', 'id'=>'state_id')) !!} </span> {!! APFrmErrHelp::showErrors($errors, 'state_id') !!} </div>
    </div>
    {{-- ************************ Select City  *********************** --}}
    <div class="col-md-6">
        <h6>{{__('City')}}</h6>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'city_id') !!}"> <span id="city_dd"> {!! Form::select('city_id', [''=>__('Select City')], null, array('class'=>'form-control','required', 'id'=>'city_id')) !!} </span> {!! APFrmErrHelp::showErrors($errors, 'city_id') !!} </div>
    </div>
    {{-- ************************ Nationality  *********************** --}}
    <div class="col-md-6">
        <h6>{{__('Nationality')}}</h6>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'nationality_id') !!}"> {!! Form::select('nationality_id', [''=>__('Select Nationality')]+$nationalities, null, array('class'=>'form-control','required', 'id'=>'nationality_id')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'nationality_id') !!} </div>
    </div>
    {{-- ************************ Date of Birth  *********************** --}}
    <div class="col-md-6">
        <h6>{{__('Date of Birth')}} ({{__('Year-Month-Day')}})</h6>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'date_of_birth') !!}"> {!! Form::date('date_of_birth', Auth::user()->date_of_birth, array('class'=>'form-control','required', 'id'=>'date_of_birth', 'placeholder'=>__('Date of Birth'), 'autocomplete'=>'off')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'date_of_birth') !!} </div>
    </div>
    {{-- ************************ National ID Card  *********************** --}}
    <div class="col-md-6">
        <h6>{{__('National ID Card')}} ({{__('optional')}})</h6>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'national_id_card_number') !!}"> {!! Form::text('national_id_card_number', null, array('class'=>'form-control', 'id'=>'national_id_card_number', 'placeholder'=>__('National ID Card#'))) !!}
            {!! APFrmErrHelp::showErrors($errors, 'national_id_card_number') !!} </div>
    </div>
    {{-- ************************ Phone  *********************** --}}
    <div class="col-md-6">
        <h6>{{__('Phone')}} ({{__('optional')}})</h6>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'phone') !!}"> {!! Form::text('phone', null, array('class'=>'form-control', 'id'=>'phone', 'placeholder'=>__('Phone'))) !!}
            {!! APFrmErrHelp::showErrors($errors, 'phone') !!} </div>
    </div>
    {{-- ************************ Mobile Number  *********************** --}}
    <div class="col-md-6">
        <h6>{{__('Mobile Number')}} ({{__('optional')}})</h6>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'mobile_num') !!}"> {!! Form::text('mobile_num', null, array('class'=>'form-control', 'id'=>'mobile_num', 'placeholder'=>__('Mobile Number'))) !!}
            {!! APFrmErrHelp::showErrors($errors, 'mobile_num') !!} </div>
    </div>
    {{-- ************************ Industry  *********************** --}}
    <div class="col-md-6">
        <h6>{{__('Industry')}}</h6>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'industry_id') !!}"> {!! Form::select('industry_id', [''=>__('Select Industry')]+$industries, null, array('class'=>'form-control','required', 'id'=>'industry_id')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'industry_id') !!} </div>
    </div>
    {{-- ************************ Street Address  *********************** --}}
    <div class="col-md-12">
        <h6>{{__('Street Address')}}</h6>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'street_address') !!}"> {!! Form::textarea('street_address', null, array('class'=>'form-control','required', 'id'=>'street_address', 'placeholder'=>__('Street Address'))) !!}
            {!! APFrmErrHelp::showErrors($errors, 'street_address') !!} </div>
    </div>
    {{-- ************************ Profile Summary  *********************** --}}
    <div class="col-md-12">
        <h6>{{__('Profile Summary')}}</h6>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'summary') !!}"> {!! Form::textarea('summary', old('summary', $user->getProfileSummary('summary')), array('class'=>'form-control','required', 'id'=>'summary', 'placeholder'=>__('Profile Summary'))) !!}
            {!! APFrmErrHelp::showErrors($errors, 'summary') !!} </div>
    </div>
    {{-- ************************ is_subscribed  *********************** --}}
    <div class="col-md-6"  style="display: inline-flex;  width: 100%;">
    <div class="formrow {!! APFrmErrHelp::hasError($errors, 'is_subscribed') !!}">
    <?php
	$is_checked = 'checked="checked"';	
	if (old('is_subscribed', ((isset($user)) ? $user->is_subscribed : 1)) == 0) {
		$is_checked = '';
	}
	?>
      <input type="checkbox" value="1" name="is_subscribed" {{$is_checked}} style="width: auto" />
      <label>{{__('Subscribe to news letter')}}</label>
      {!! APFrmErrHelp::showErrors($errors, 'is_subscribed') !!}
      </div>
    </div>
    <div class="col-md-6 col-md-offset-3 col-sm-12">
        <div class="formrow"><button type="submit" class="btn">{{__('Update Profile and Save')}}  <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button></div>
    </div>
  {!! Form::close() !!}
   
</div>
<hr>
@push('styles')
<style type="text/css">
    .datepicker>div {
        display: block;
    }
    .fileImg{
        position:absolute;
        top:0px;
        left:0px;
        right:0px;
        bottom:0px;
        width:100%;
        height:100%;
        opacity: 0;
    }
</style>
@endpush
@push('scripts') 
<script type="text/javascript">
// onClick="submitProfileSummaryForm();"
    $(document).ready(function () {        
        $('#country_id').on('change', function (e) {
            e.preventDefault();
            filterStates(0);
        });
        $(document).on('change', '#state_id', function (e) {
            e.preventDefault();
            filterCities(0);
        });
        filterStates(<?php echo old('state_id', $user->state_id); ?>);
        filterCities(<?php echo old('city_id', $user->city_id); ?>);

        /*******************************/
        var fileInput = document.getElementById("image");
        fileInput.addEventListener("change", function (e) {
            var files = this.files;
            if (files[0].type == "image/jpeg" || files[0].type == "image/png") {
                if ((files[0].size/1024/1024)>2) {
                    alert("{{__('The image exceeds the allowed size (2M)')}}");
                }
                else {
                    showThumbnail(files)
                }
            }
            else {
                alert("{{__('The selected file must be a png or jpg image')}}");
            }
            
            
        }, false)
        function showThumbnail(files) {
            $('#thumbnail').html('');
            for (var i = 0; i < files.length; i++) {
                var file = files[i]
                var imageType = /image.*/
                if (!file.type.match(imageType)) {
                    alert("Formato no admitido");
                    console.log("Not an Image");
                    continue;
                }
                var reader = new FileReader()
                reader.onload = (function (theFile) {
                    return function (e) {
                        $('#thumbnail').append('<div class="fileattached"><img height="100px" src="' + e.target.result + '" ><div class="clearfix"></div></div>');
                        // $('#thumbnail').append('<div class="fileattached"><img height="100px" src="' + e.target.result + '" > <div>' + theFile.name + '</div><div class="clearfix"></div></div>');
                    };
                }(file))
                var ret = reader.readAsDataURL(file);
            }
        }
    });
    function filterStates(state_id)
    {
        var country_id = $('#country_id').val();
        if (country_id != '') {
            $.post("{{ route('filter.lang.states.dropdown') }}", {country_id: country_id, state_id: state_id, _method: 'POST', _token: '{{ csrf_token() }}'})
                    .done(function (response) {
                        $('#state_dd').html(response);
                        filterCities(<?php echo old('city_id', $user->city_id); ?>);
                    });
        }
    }
    function filterCities(city_id)
    {
        var state_id = $('#state_id').val();
        if (state_id != '') {
            $.post("{{ route('filter.lang.cities.dropdown') }}", {state_id: state_id, city_id: city_id, _method: 'POST', _token: '{{ csrf_token() }}'})
                    .done(function (response) {
                        $('#city_dd').html(response);
                    });
        }
    }
    function initdatepicker() {
        $(".datepicker").datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
        });
    }
</script> 
@endpush            