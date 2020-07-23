@extends('layouts.app')
@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end -->
<!-- Inner Page Title end -->
<div class="listpgWraper">
    <div class="container" style="display: flex; justify-content: center;">
        <div class="row loginRecruiter col-md-8 col-sm-12">
            <div class="col-md-12 col-sm-12">
                @if((bool)$state == true)
                    @if((bool)$enableForm)
                        {!! Form::model([], array('method' => 'post', 'route' => array('recruiter.new'), 'class' => 'form', 'files'=>true)) !!}
                            <input type="hidden" name="email" value="{{$mail}}">
                            <input type="hidden" name="invitation" value="{{$invitation}}">
                            <input type="hidden" name="company" value="{{$company}}">
                            <input type="hidden" name="is_master" value="{{$is_master}}">
                            <div class="col-md-12 col-sm-12 title">{{__('Recruiters Register')}}</div>
                            <div class="col-md-4 col-sm-12 photoDiv">
                                <img id="myimage" src="{{asset('/')}}admin_assets/profile-image.png">
                                <label for="selphoto"><i class="fa fa-camera"></i></label>
                                <input type="file" id="selphoto" name="image" style="visibility: hidden" value="{{__('Select Photo')}}" accept="image/jpeg, image/png" onchange="readURL(this);" required />
                            </div>
                            <div class="col-md-8 col-sm-12 div-left">
                                <div class="row">
                                    <div class="form-group col-md-6 col-sm-12"><input type="text" class="form-control" name="name" placeholder="{{__('Name')}}" required></div>
                                    <div class="form-group col-md-6 col-sm-12"><input type="text" class="form-control" name="lastname" placeholder="{{__('Last Name')}}" required></div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12 col-sm-12"><input type="text" class="form-control" name="phone" placeholder="{{__('Phone')}}" required></div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12 col-sm-12"><input type="password" class="form-control" name="password" placeholder="{{__('Password')}}" required></div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <button type="submit" id="submitRegister" class="btn btn-primary btn-block">{{__('Complete Register')}}</button>
                            </div>
                        {!! Form::close() !!}
                    @else
                        <div>
                            {{__('Sorry, the company has reached the recruitment limit of its plan, contact your administrator.')}}
                        </div>
                    @endif
                @else
                    <div>
                        {{__('Sorry this invitation has already been completed')}}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@include('includes.footer')
@endsection
@push('styles')
<style>
    .loginRecruiter {
        background-color: white;
        border-radius: 5px;
        padding: 5rem;
        box-shadow: #04226d33 0px 0px 25px 1px;
    }

    .loginRecruiter form > .title {
        color: #022367;
        font-size: x-large;
        font-weight: bold;
        padding: 0 0 2rem 0;
        text-align: center;
    }

    .loginRecruiter form > .div-left {
        border-left: solid 1px #0000004d;
    }

    .photoDiv {
        display: flex;
        justify-content: center;
        min-height: 180px;
        border: none !important;
    }

    .photoDiv #myimage {
        width: 150px;
        height: 150px;
        border-radius: 100%;
        position: absolute;
    }

    .photoDiv label {
        width: 150px;
        height: 150px;
        background-color: transparent;
        border-radius: 100%;
        z-index: 100;
        position: absolute;
        display: flex;
        justify-content: center;
        align-items: center;
        color: transparent;
    }

    .photoDiv label:hover {
        cursor: pointer;
        background-color: #636164c2;
        color: white;
    }


</style>
@endpush
@push('scripts')
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#myimage')
                    .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    $('#submitRegister').on('click', function() {
        if ($('#selphoto').get(0).files.length < 1) {
            alert("{{__('Select Profile Photo')}}");
        }
    });
</script>
@endpush