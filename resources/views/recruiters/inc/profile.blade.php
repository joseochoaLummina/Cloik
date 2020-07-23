<h5 class="a">{{__('Recruiter Information')}}</h5>
{!! Form::model($recruiter, array('method' => 'put', 'route' => array('update.recruiter.profile'), 'class' => 'form', 'files'=>true)) !!}
<div class="row">
    <div class="col-md-12">
        <div class="formrow"> {{ ImgUploader::print_image("recruiters_images/$recruiter->image", 100, 100) }} </div>
    </div>
    <div class="col-md-12">
        <div class="formrow">
            <div id="thumbnail"></div>
            <label class="btn btn-default"> {{__('Select Recruiter Image')}}
                <input type="file" name="image" id="image" style="display: none;">
            </label>
            {!! APFrmErrHelp::showErrors($errors, 'image') !!} </div>
    </div>
</div>
<div class="row cjustify-content-center">
    <div class="col-md-12 a">
        <h6>{{__('Company Recruiter')}}</h6>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'email') !!}"> {!! Form::text('email', null, array('class'=>'form-control a', 'id'=>'email', 'placeholder'=>__('Company Recruiter'))) !!}
            {!! APFrmErrHelp::showErrors($errors, 'email') !!} </div>
    </div>
    <div class="col-md-6">
        <h6>{{__('Recruiter Name')}}</h6>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'name') !!}"> {!! Form::text('name', null, array('class'=>'form-control', 'id'=>'name', 'placeholder'=>__('Recruiter Name'))) !!}
            {!! APFrmErrHelp::showErrors($errors, 'name') !!} </div>
    </div>
    <div class="col-md-6">
        <h6>{{__('Surnames')}}</h6>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'lastname') !!}"> {!! Form::text('lastname', null, array('class'=>'form-control', 'id'=>'lastname', 'placeholder'=>__('Surnames'))) !!}
            {!! APFrmErrHelp::showErrors($errors, 'lastname') !!}</div>
    </div>
    <div class="col-md-6">
        <h6>{{__('Phone')}}</h6>
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'phone') !!}"> {!! Form::text('phone', null, array('class'=>'form-control', 'id'=>'phone', 'placeholder'=>__('Phone'))) !!}
            {!! APFrmErrHelp::showErrors($errors, 'phone') !!} </div>
    </div>
    <div class="col-md-6">
        <h6>{{__('Company Email')}}</h6>
        <div class="formrow">
            <input type="email" class="form-control" name="companyEmail" id="companyEmail" value="{{$company->email}}" readonly> 
        </div>     
    </div>
    <div class="col-md-6">
        <h6>{{__('Company Name')}}</h6>
        <div class="formrow">
            <input type="text" class="form-control" name="companyName" id="companyName" value="{{$company->name}}" readonly>
        </div>
    </div>
    <div class="col-md-6">
        <h6>{{__('Company Phone')}}</h6>
        <div class="formrow">
            <input type="tel" class="form-control" name="companyPhone" id="companyPhone" value="{{$company->phone}}" readonly>
        </div>
    </div>

    <div class="col-md-12">
        <div class="formrow">
            <button type="submit" class="btn">{{__('Update Profile and Save')}} <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button>
        </div>
    </div>

    
</div>
{!! Form::close() !!}
<div>

</div>
<hr>
@push('styles')
<style type="text/css">
    .datepicker>div {
        display: block;
    }
    .padre {
    display: flex;
    justify-content: center;
    }
    .hijo {
    padding: 10px;
    margin: 10px;
    }
    .a{
        text-align: center;
    }
</style>
@endpush
@push('scripts')
@include('includes.tinyMCEFront') 
<script type="text/javascript">
    $(document).ready(function () {
        $('#country_id').on('change', function (e) {
            e.preventDefault();
            filterLangStates(0);
        });
        $(document).on('change', '#state_id', function (e) {
            e.preventDefault();
            filterLangCities(0);
        });
        filterLangStates(<?php echo old('state_id', (isset($company)) ? $company->state_id : 0); ?>);

        /*******************************/
        var fileInput = document.getElementById("image");
        fileInput.addEventListener("change", function (e) {
            var files = this.files
            
            if (files[0].size > 5242880) {                
                alert("La imagen seleccionado no debe ser mayor a 5MB");
            }
            else {
                showThumbnail(files)
            }
            
        }, false)
    });

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
                    $('#thumbnail').append('<div class="fileattached"><img height="100px" src="' + e.target.result + '" > <div>' + theFile.name + '</div><div class="clearfix"></div></div>');
                };
            }(file))
            var ret = reader.readAsDataURL(file);
        }
    }


    function filterLangStates(state_id)
    {
        var country_id = $('#country_id').val();
        if (country_id != '') {
            $.post("{{ route('filter.lang.states.dropdown') }}", {country_id: country_id, state_id: state_id, _method: 'POST', _token: '{{ csrf_token() }}'})
                    .done(function (response) {
                        $('#default_state_dd').html(response);
                        filterLangCities(<?php echo old('city_id', (isset($company)) ? $company->city_id : 0); ?>);
                    });
        }
    }
    function filterLangCities(city_id){
        var state_id = $('#state_id').val();
        if (state_id != '') {
            $.post("{{ route('filter.lang.cities.dropdown') }}", {state_id: state_id, city_id: city_id, _method: 'POST', _token: '{{ csrf_token() }}'})
                    .done(function (response) {
                        $('#city_dd').html(response);
                    });
        }
    }
</script> 
@endpush