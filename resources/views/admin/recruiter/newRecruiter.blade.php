@extends('admin.layouts.admin_layout')
@section('content')
<style type="text/css">
  
</style>
<div class="page-content-wrapper"> 
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content"> 
        <!-- BEGIN PAGE HEADER--> 
        <!-- BEGIN PAGE BAR -->
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li> <a href="{{ route('admin.home') }}">Home</a> <i class="fa fa-circle"></i> </li>
                <li> <a href="{{ route('list.recruiters') }}">Recruiters</a> <i class="fa fa-circle"></i> </li>
                <li> <span>New Recruiter</span> </li>
            </ul>
        </div>
        <!-- END PAGE BAR --> 
        <!-- BEGIN PAGE TITLE-->
        <h3 class="page-title">Send Invitation To A New Recruiter</h3>
        <!-- END PAGE TITLE--> 
        <!-- END PAGE HEADER-->
        <div class="row">
            <div class="col-md-12"> 
                <!-- Begin: life time stats -->
                <div class="portlet light portlet-fit portlet-datatable bordered">
                    <div class="portlet-title">
                        <div class="caption"> <i class="icon-plus font-dark"></i> <span class="caption-subject font-dark sbold uppercase">Recruiter</span> </div>
                    </div>
                    <div class="form">
                        <div class="col-md-12">
                            <table class="table" id="countRecruiter">
                                <thead>
                                    <tr>
                                    <th scope="col">Select Company</th>
                                    <th scope="col">Number of Master Recruiters</th>
                                    <th scope="col">Number of Junior Recruiters</th>
                                    <th scope="col">Available Master Recruiters</th>
                                    <th scope="col">Available Junior Recruiters</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="tr">
                                        <td id='company_id_td'>
                                            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'company_id') !!}" id="company_id_div"> {!! Form::select('company_id', ['' => __('Select Company')]+$companies,null, array('class'=>'form-control', 'id'=>'company_id')) !!}
                                                {!! APFrmErrHelp::showErrors($errors, 'company_id') !!} 
                                            </div>
                                        </td>
                                        <td id="NMR"></td>
                                        <td id="NJR"></td>
                                        <td id="AMR"></td>
                                        <td id="AJR"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form hide" id="formRecruiter">
            <div class="row" style="display: block">
                <div class="col-md-6">
                    <h6>{{__('Company Recruiter')}}</h6>
                    {!! Form::model(null, array('method' => 'post', 'class' => 'form', 'files'=>true)) !!}
                        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'email') !!}"> 
                            {!! Form::email('email', null, array('class'=>'form-control a','id'=>'email','placeholder'=>__('Email Recruiter'),'required'=>'required')) !!} 
                            {!! APFrmErrHelp::showErrors($errors, 'email') !!}
                        </div>
                    {!! Form::close() !!}
                </div>
                <div class="col-md-2" id="divIs_master">
                    <input type="checkbox" id="is_master" value="true"> Is master recruiter</div>
                <div class="col-md-2" id="msgNM">
                    <p><strong>This company has no master recruiter invitations available</strong></p>
                </div>
                <div class="col-md-2" id="msgNJR">
                    <p><strong>This company has no junior recruiter invitations available</strong></p>
                </div>
                <div class="col-md-6">
                    <label for="btn_send"><button type="submit" class="btn btn-success" id="btn_send" name="btn_send">Send Invitation</button></label>                        
                </div>
            </div>
        </div>
        <div id='msgOk' class="hide">
            <p><strong>The invitation message has been sent</strong></p>
        </div>
        <div id="NoA" class="hide">
            <p><strong>No avalive</strong></p>
        </div>
    </div>
    <!-- END CONTENT BODY --> 
</div>
@endsection
@push('scripts') 
<script>
    $(document).on('change', '#company_id', function (e) {
        document.getElementById('NoA').classList.add('hide');
        document.getElementById('formRecruiter').classList.add('hide');
        document.getElementById('msgNM').classList.add('hide');
        document.getElementById('msgNJR').classList.add('hide');
        document.getElementById('divIs_master').classList.remove('hide');
        document.getElementById('msgOk').classList.add('hide');

        document.getElementById('is_master').checked=0;
        document.getElementById('is_master').parentElement.classList.remove('checked');
        document.getElementById('is_master').disabled = false;

        let recruiters_master_limit=0;
        let recruiters_jr_limit=0;
        let amr=0;
        let ajr=0;
        let master=0;
        let jr=0;
        e.preventDefault();
        var company_id = document.getElementById('company_id').value;
        if (company_id != '') {
            $.post("{{ route('fetch.data.recruiters.companies') }}", {company_id: company_id, _method: 'POST', _token: '{{ csrf_token() }}'})
                .done(function (response) {
                    document.getElementById('NMR').innerHTML='';
                    document.getElementById('NJR').innerHTML='';
                    document.getElementById('AMR').innerHTML='';
                    document.getElementById('AJR').innerHTML='';
                    
                    recruiters_master_limit=response[0].recruiters_master_limit;
                    recruiters_jr_limit=response[0].recruiters_jr_limit;
                    master=response[1];
                    jr=response[2];
                    amr=recruiters_master_limit-master;
                    ajr=recruiters_jr_limit-jr;

                    document.getElementById('NMR').innerHTML=response[1];
                    document.getElementById('NJR').innerHTML=response[2];                    
                    document.getElementById('AMR').innerHTML=amr;
                    document.getElementById('AJR').innerHTML=ajr;
                    
                    if(amr>0 || ajr>0){
                        if (amr==0) {
                            document.getElementById('formRecruiter').classList.remove('hide');
                            document.getElementById('divIs_master').classList.add('hide');
                            document.getElementById('msgNM').classList.remove('hide');
                        }else if(ajr==0){                            
                            document.getElementById('formRecruiter').classList.remove('hide');
                            document.getElementById('msgNJR').classList.remove('hide');
                            document.getElementById('is_master').checked=1;
                            document.getElementById('is_master').parentElement.classList.add('checked');
                            document.getElementById('is_master').disabled = true;
                        }
                        else{
                            document.getElementById('formRecruiter').classList.remove('hide');
                        }
                    }else{
                        document.getElementById('NoA').classList.remove('hide');
                    }
                });
        }
    });

    $(document).on('click','#btn_send',function (e) {
        let company_id=document.getElementById('company_id').value;
        let email=document.getElementById('email').value;
        let is_master=document.getElementById('is_master').checked;
        $.post("{{ route('admin.verify.exist.email') }}", {company_id: company_id,email:email,is_master:is_master ,_method: 'POST', _token: '{{ csrf_token() }}'})
            .done(function (res) {
                if (res=='ok') {
                    document.getElementById('msgOk').classList.remove('hide');
                    document.getElementById('company_id').selectedIndex=0;
                    document.getElementById('email').value='';
                    document.getElementById('formRecruiter').classList.add('hide');
                }
            })
        
    })
</script> 
@endpush