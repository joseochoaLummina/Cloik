@extends('layouts.app')
@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end -->
<!-- Inner Page Title start -->
@include('includes.inner_page_title', ['page_title'=>__('Company Recruiters')])
<!-- Inner Page Title end -->
@push('styles')
<link href="{{ asset('css/meeting.css') }}" rel="stylesheet">
<style>
    #recruiters {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 2rem;
        grid-auto-rows: 12rem;
        overflow-y: auto;
        max-height: 500px;
        scrollbar-width: thin;
    }

    #recruiters>div {
        width: 100%;
        height: 100%;
        /* background-color: #272727; */
        background: rgb(0, 0, 0);
        background: linear-gradient(0deg, rgba(0, 0, 0, 1) 0%, rgba(39, 39, 39, 1) 80%, rgba(128, 130, 131, 1) 100%);
        border-radius: 3px;
        -webkit-box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.45);
        -moz-box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.45);
        box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.45);
        display: grid;
        grid-template-rows: 5fr 1fr
    }

    #recruiters>div>div {
        padding: 0.35rem 1rem;
    }

    #recruiters .head {
        color: white;
        border-radius: 3px 3px 0 0;
        display: grid;
        grid-template-columns: 100%;
        grid-template-rows: 70% 30%;
        gap: 2.5%;
        align-items: center;
        justify-items: center;
    }

    #recruiters .head img {
        border-radius: 50%;
        width: 60%;
        height: auto;
    }

    #recruiters .recruiters-btn {
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: large;
    }

    .recruiters-btn a {
        color: white;
        text-decoration: none;
        cursor: pointer;
        margin: 0 auto;
    }

    .delRecruiter:hover {
        color: red;
    }

    .editRecruiter:hover {
        color: yellow;
    }

    .myads>h3 {
        background-color: #00246d;
        color: white;
        padding: 0.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: large;
    }

    #emails {
        display: flex;
        flex-wrap: wrap;
    }

    .email {
        display: grid;
        width: 100%;
        grid-template-columns: 90% 10%;
        background-color: #0211371c;
        border-radius: 3px;
        padding: 0.5rem 0.3rem;
        margin: 5px;
        border: 1px solid #01123654;
    }

    .email > button {
        border: 0;
        display: flex;
        justify-content: center;
        background-color: transparent;
        color: #011233;
        font-weight: bold;
    }

    .email > button:hover {
        color: red;
    }

    #emailText {
        width: 100%;
        border-radius: 3px;
        border: solid 1px #80808050;
        padding: 0.6rem;
        margin: 1rem 0;
    }

    #emailserror {
        display: none;
        margin: 5px;
        border: 1px solid #f005;
        background-color: #ff00001f;
        padding: 0.5rem 0.3rem;
        border-radius: 3px;
    }

    #emailsinfo {
        display: none;
        margin: 5px;
        border: 1px solid #ffd700c9;
        background-color: #ffef001f;;
        padding: 0.5rem 0.3rem;
        border-radius: 3px;
    }
</style>
@endpush
<div class="modal fade" id="newRecruiterModal" tabindex="-1" role="dialog" aria-labelledby="newRecruiterModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div>
                    <label style="width: 100%; font-weight: bold; text-align: center;">{{__('Add mail from recruiters')}}</label>
                    <div>
                        <input type="text" id="emailText" onchange="validarCorreos(this.value);" onkeyup="this.onchange();" onpaste="this.onchange();" oninput="this.onchange();">
                        <label style="margin: 0 0.5rem"><input type="checkbox" id="is_master"> {{__('Is master recruiter')}}</label>
                        <div id="emailserror"></div>
                        <div id="emailsinfo"></div>
                        <div id="emails"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                <button type="button" class="btn btn-primary" id="sendInvitationsBtn">{{__('Send Emails')}}</button>
            </div>
        </div>
    </div>
</div>
<div class="listpgWraper">
    <div class="container">
        <div class="row">
            @include('includes.company_dashboard_menu')

            <div class="col-md-9 col-sm-8">
                <div class="myads">
                    <h3>{{__('Company Recruiters')}}
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newRecruiterModal">{{__('New Recruiter')}}</button>
                    </h3>
                    <div id="recruiters">
                        @foreach($recruiters as $key)
                        <div>
                            <div class="head">
                                @if($key->image != null)
                                <img src="{{asset('/')}}recruiters_images/{{$key->image}}">
                                @else
                                <img src="{{asset('/')}}/admin_assets/no-image.png">
                                @endif
                                <label>{{$key->name}} {{$key->lastname}}</label>
                            </div>
                            <div class="recruiters-btn">
                                <a href="{{ route('delete.recruiter', [$key->id])}}"><i class="fa fa-trash delRecruiter"></i></a>
                                <!-- <a><i class="fa fa-edit editRecruiter"></i></a> -->
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('includes.footer')
@endsection
@push('scripts')
<script>
    var emailsMaster = [];
    var emailsJrs = [];
    var error = document.getElementById('emailserror');
    var info = document.getElementById('emailsinfo');
    var sendBtn = document.getElementById('sendInvitationsBtn');


    sendBtn.addEventListener('click', () => {
        let emailInput = $('#emailText').val();
        emailInput = emailInput + ';';
        if (emailInput.length > 0) {
            validarCorreos(emailInput);

            if (emailsMaster.length>0 || emailsJrs.length >0) {
                $.ajax({
                    type: 'GET',
                    url: "{{ route('send.invitation.recruiter') }}",
                    data: {
                        emailsMaster: emailsMaster,
                        emailsJrs: emailsJrs
                    },
                    success: function(data) {
                        window.location.reload();
                    },
                    error: function(err) {
                        console.log('error: ', err)
                    }
                });
            }  
            else {
                error.style.display = "block";
                error.innerHTML = "{{__('Please add a email, write and press enter')}}";
            }  
        }    

        emailsMaster = [];
        emailsJrs = [];
    });
    
    function validarCorreos(e) {
        if (e.includes(';')) {
            accionesValidar(e); 
        }
        else if (!e.includes(';') || e == ''){
            error.style.display = "none";
            error.innerHTML = "";
        }       
    }

    function accionesValidar(e) {
        if (isValidEmail(e.replace(';', ''))) {
            var email = e.replace(';', '');
            var isMasterInput = $('#is_master').prop('checked');

            var emails;

            if (isMasterInput == true ) {
                
                var cantMaster = {!! json_encode($master_recruiters_company[0]->cuenta) !!};
                var limitMaster = {!! json_encode($limit_recruiters[0]->recruiters_master_limit) !!}
                if ((emailsMaster.length + cantMaster) >= limitMaster ) {
                    info.style.display = "block";
                    info.innerHTML = "(Master) {{__('Invitations to send exceed the limit, all invitations will be sent but recruiters who wish to register after exceeding the limit will not be able to enter.')}}";
                }
                else {
                    info.style.display = "none";
                }

                if (emailsMaster.includes(email)) {                    
                    error.style.display = "block";
                    error.innerHTML = "{{__('The email is already in the list')}}";
                }
                else {
                    emailsMaster.push(email);
                    $('#emailText').val('');
                }
            }
            else {
                var cantJr = {!! json_encode($jr_recruiters_company[0]->cuenta) !!};
                var limitJr = {!! json_encode($limit_recruiters[0]->recruiters_jr_limit) !!}
                if ((emailsJrs.length + cantJr) >= limitJr ) {
                    info.style.display = "block";
                    info.innerHTML = "(JR) {{__('Invitations to send exceed the limit, all invitations will be sent but recruiters who wish to register after exceeding the limit will not be able to enter.')}}";
                }
                else {
                    info.style.display = "none";
                }

                if (emailsJrs.includes(email)) {                    
                    error.style.display = "block";
                    error.innerHTML = "{{__('The email is already in the list')}}";
                }
                else {
                    emailsJrs.push(email);
                    $('#emailText').val('');
                }
            }

            
            mostrarEmails();
        }    
        else {
            //no tiene formato valido
            error.style.display = "block";
            error.innerHTML = "{{__('The text is not email')}}";
        }
    }

    $('#emailText').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            var e = $('#emailText').val();
            $('#emailText').val(e+';');
            accionesValidar(e); 
        }
    });

    function mostrarEmails() {
        var emailsDom = document.getElementById('emails');
        var html = '';

        emailsMaster.forEach((email, index) => {
            html += `
                <div class="email">
                    <div>`+ email +` (Master)</div>
                    <button onclick="eliminarEmail(`+index+`,'` + email + `')">x</button>
                </div>
            `;
        });

        emailsJrs.forEach((email, index) => {
            html += `
                <div class="email">
                    <div>`+ email +`</div>
                    <button onclick="eliminarEmail(`+index+`,'` + email + `')">x</button>
                </div>
            `;
        });

        emailsDom.innerHTML = html;
    }

    function isValidEmail(mail) { 
        return /^\w+([\.\+\-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(mail); 
    }

    function eliminarEmail(i, email) {
        if (emailsMaster.includes(email)) {
            emailsMaster.splice(i,1);
        }
        else if (emailsJrs.includes(email)) {
            emailsJrs.splice(i,1);
        }
        mostrarEmails();
    }
</script>
@include('includes.immediate_available_btn')
@endpush