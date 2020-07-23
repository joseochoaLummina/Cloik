@extends('layouts.app')
@section('content')
@php
    $cantidad=count($datos);
    if ($cantidad) {
        $planned_time=$datos[0]->planned_time;
        $planned_date=$datos[0]->planned_date;
        $id=$datos[0]->id;
    }else {
        $planned_time=null;
        $planned_date=null;
        $id=0;
    }
    $routeRecruiterSave='recruiter.save.meeting';
    $routeRecruiterUpdate='recruiter.update.meeting';
    $routeRecruiterDelete='recruiter.delete.meeting';
    $routeCompanySave='save.meeting';
    $routeCompanyUpdate='update.meeting';
    $routeCompanyDelete='delete.meeting';
    
@endphp
{{-- Contenido del modal:meeting_modal --}}
<div id="meeting-content">
    <div class="modal-content" >
            <div class="modal-header">
            {{-- Titulo del modal y boton de cierre --}}
                <h5 class="modal-title" id="modalTitle">{{__('Schedule meeting')}}
                <button type="button" onclick="cerrar()" class="close" id="close-apply-modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </h5>
            </div>
        <div class="modal-body row">
            <div class="col-md-12">
            <label for="date_videocall"><h6>{{__('Date to make video call')}} ({{__('Year-Month-Day')}})</h6></label>
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'date_videocall') !!}"> {!! Form::date('date_videocall', $planned_date, array('class'=>'form-control datepicker', 'id'=>'date_videocall', 'placeholder'=>__('Year-Month-Day'), 'autocomplete'=>'off')) !!}
                {!! APFrmErrHelp::showErrors($errors, 'date_videocall') !!} </div>
            </div>
            <div class="col-md-12" >
            <label for="time_videocall"><h6>{{__('Time to make video call')}} ({{__('Hour-Seconds')}})</h6></label>
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'time_videocall') !!}"> {!! Form::time('time_videocall', $planned_time, array('class'=>'form-control timepicker', 'id'=>'time_videocall', 'placeholder'=>__('Hour-Seconds'), 'autocomplete'=>'off')) !!}
                    {!! APFrmErrHelp::showErrors($errors, 'time_videocall') !!} </div>
            </div>
            <div class="recruiter">
                @if ($recruiter)
                @foreach ($recruiter as $item)
                <label for="{{$item->id}}"><div>
                    <div class="formrow"> {{ ImgUploader::print_image("recruiters_images/$item->image", 100, 100) }} </div>   
                <p>{{$item->name}}</p>                     
                </div>
                </label>
                    @if (count($arrayRecruiter)>0)
                        @if (in_array($item->id,$arrayRecruiter))
                            <input type="checkbox" value="{{$item->id}}" name="recruiter" checked>
                        @else
                            <input type="checkbox" value="{{$item->id}}" name="recruiter" >
                        @endif
                    @else
                        <input type="checkbox" value="{{$item->id}}" name="recruiter" >
                    @endif                    
                @endforeach
            @endif
        </div>
        </div>
        <div class="modal-footer">
            @if ($cantidad)
                <button id="btnDelete" type="button" onclick="eliminar()" class="btn btn-danger">{{__('Remove meeting')}}</button>
                <button id="btnUpdate" type="button" onclick="actualizar()" class="btn btn-primary">{{__('Modify meeting')}}</button>
            @else
            <button id="btnSave" type="button" onclick="guardar()" class="btn btn-success" >{{__('Save meeting')}}</button>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style type="text/css">
    .recruiter{
        margin: 10px;
    }
    .searchList li .listbtn {
        margin-top: 10px !important;
    }
    .requerido{
        border-color:red;
    }
</style>
@endpush

@push('scripts')
<script>
    function guardar(){
        date=$('#date_videocall').val();
        time=$('#time_videocall').val();
        var selected = [];
        $('.recruiter input:checked').each(function() {
            selected.push($(this).val());
        });
        if (!date || !time) {
            $('#date_videocall').addClass('requerido');
            $('#time_videocall').addClass('requerido');
        } else {
            $('#date_videocall').removeClass('requerido');
            $('#time_videocall').removeClass('requerido');
            $('#btnSave').attr("disabled", true);
            document.getElementById('btnSave').innerHTML='{{__("Saving meeting")}}';
            $.post('<?php  if( Auth::guard("recruiter")->check() ){ echo( route($routeRecruiterSave) ); } elseif(Auth::guard("company")->check()){ echo( route($routeCompanySave) ); }  ?>', {selected: selected,date: date,time: time,user_id:{{$user_id}},job_id:{{$job_id}},company_id:{{$company_id}}, _method: 'POST', _token: '{{ csrf_token() }}'})
                .done(function (response) {
                    window.location.reload();
                    
                    
            }).fail(function(e){
                console.log(e);
                
            });
        }

    }
    function actualizar() {
        date=$('#date_videocall').val();
        time=$('#time_videocall').val();
        id={{$id}};
        var selected = [];
        $('#btnUpdate').attr("disabled", true);
        $('#btnDelete').attr("disabled", true);
        document.getElementById('btnUpdate').innerHTML='{{__("Updating meeting")}}';
        $('.recruiter input:checked').each(function() {
            selected.push($(this).val());
        });
        $.post('<?php  if( Auth::guard("recruiter")->check() ){ echo( route($routeRecruiterUpdate) ); } elseif(Auth::guard("company")->check()){ echo( route($routeCompanyUpdate) ); }  ?>', {selected:selected,id:id,date: date,time: time, _method: 'PUT', _token: '{{ csrf_token() }}'})
            .done(function (response) {
                window.location.reload();
           
           
        }).fail(function(e){
            console.log(e);
            
        });
    }
    function eliminar() {
        $('#btnUpdate').attr("disabled", true);
        $('#btnDelete').attr("disabled", true);
        document.getElementById('btnUpdate').innerHTML='{{__("Deleting meeting")}}';
        id={{$id}};
        var selected = [];
        $('.recruiter input:checked').each(function() {
            selected.push($(this).val());
        });
        $.post('<?php  if( Auth::guard("recruiter")->check() ){ echo( route($routeRecruiterDelete) ); } elseif(Auth::guard("company")->check()){ echo( route($routeCompanyDelete) ); }  ?>', {selected:selected,id:id, _method: 'DELETE', _token: '{{ csrf_token() }}'})
            .done(function (response) {
                window.location.reload();
            
                            
        }).fail(function(e){
            console.log(e);
            
        });
    }
    function cerrar() {
        window.location.reload();
    }
</script>
@include('includes.immediate_available_btn')
@endpush