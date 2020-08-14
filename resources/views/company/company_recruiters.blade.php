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
<meta name="csrf-token" content="{{ csrf_token() }}">
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

    .shakeIt{
        animation: shake 0.82s cubic-bezier(.36,.07,.19,.97) both;
        transform: translate3d(0, 0, 0);
        backface-visibility: hidden;
        perspective: 1000px;
    }

    @keyframes shake {
        10%, 90% {
            transform: translate3d(-1px, 0, 0);
        }
        
        20%, 80% {
            transform: translate3d(2px, 0, 0);
        }

        30%, 50%, 70% {
            transform: translate3d(-4px, 0, 0);
        }

        40%, 60% {
            transform: translate3d(4px, 0, 0);
        }
    }

    #alertSendEmails{
        display:none;
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
                        <!-- <input type="text" id="emailText" onchange="validarCorreos(this.value);" onkeyup="this.onchange();" onpaste="this.onchange();" oninput="this.onchange();"> -->
                        <input type="text" id="emailText" placeholder="{{__('Write and press Enter')}}">
                        <label style="margin: 0 0.5rem"><input type="checkbox" id="is_master"> {{__('Is master recruiter')}}</label>
                        <div id="emailserror"></div>
                        <div id="emailsinfo"></div>
                        <div id="emails"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="closeRecruiterModal">{{__('Close')}}</button>
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
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newRecruiterModal" onclick="$('#alertSendEmails').hide();">{{__('New Recruiter')}}</button>
                    </h3>

                    <div class="alert alert-success alert-dismissible" id="alertSendEmails" role="alert">
                        {{__("Emails were sent successfully")}}
                        <button type="button" class="close closeAlert" onclick="$('#alertSendEmails').hide();">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

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
        //Se obtiene el texto del input
        let emailInput = $('#emailText').val();         

        //Se intentan enviar los correos al cumplir el condicional
        if ((emailsMaster.length>0 || emailsJrs.length >0) && emailInput==''){
            //se desabilita el boton enviar para evitar mas de un envio de los correos
            sendBtn.setAttribute('disabled','true');

            $.ajax({
                type: 'GET',
                url: "{{ route('send.invitation.recruiter') }}",
                data: {
                    emailsMaster: emailsMaster,
                    emailsJrs: emailsJrs
                },
                success: function(data) {
                    $("#closeRecruiterModal").click()
                    $("#alertSendEmails").show();

                    emailsMaster=[];
                    emailsJrs=[];
                    mostrarEmails();

                    sendBtn.removeAttribute('disabled');
                    error.style.display = "none";
                    error.innerHTML = "";

                    /*
                        Una vez enviado los correos se resetean las variables 
                        a la espera de nuevos envios
                    */
                },
                error: function(err) {
                    console.log('error: ', err)
                }
            });

        }else if(emailInput!=''){
            /*Si hay contenido texto en el input, se intenta agregar a la lista 
            pasando por las verificaciones primero */       

            let event = $.Event('keypress');
            event.keyCode= 13;
            $('#emailText').trigger(event); //se simula que el usuario presiono enter

            /*Se espera para que se agregue a la lista y luego se vuelve
            a presionar el boton */
            if(error.style.display=="none"){
                setTimeout(() => {
                    $('#sendInvitationsBtn').click();
                }, 1500); 
            }

        }else{
            displayError("{{__('Please add a email, write and press enter')}}");
            $('#emailText').val('');
        }

    });
    
    // function validarCorreos(e) {
    //     if (e.includes(';')) {
    //         accionesValidar(e); 
    //     }
    //     else if (!e.includes(';') || e == ''){
    //         error.style.display = "none";
    //         error.innerHTML = "";
    //     }       
    // }

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
                }else{
                    info.style.display = "none";
                }

                insertEmailOnTheList(isMasterInput=true,email);
                
            }//termina proceso correo master
            else {

                var cantJr = {!! json_encode($jr_recruiters_company[0]->cuenta) !!};
                var limitJr = {!! json_encode($limit_recruiters[0]->recruiters_jr_limit) !!}

                if ((emailsJrs.length + cantJr) >= limitJr ) {
                    info.style.display = "block";
                    info.innerHTML = "(JR) {{__('Invitations to send exceed the limit, all invitations will be sent but recruiters who wish to register after exceeding the limit will not be able to enter.')}}";
                }else {
                    info.style.display = "none";
                }

                insertEmailOnTheList(isMasterInput=false,email);            
                
            }//termina proceso correo jr

            mostrarEmails();

        }else {
            //no tiene formato valido
            displayError('{{__("The text is not email")}}');
            $('#emailText').val(e);
        }

    }//Termina funcion accionesValidar()

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

    function verifyExist(_email){
        return new Promise(function(resolve, reject) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type:'post',    
                url:"{{ route('verify.exist.email') }}",
                data: {
                    email: _email                    
                },
                success: function(data) {
                    resolve(data); // caso de exito, se utiliza then()
                },
                error: function(err) {
                    reject(err); // caso fracaso, utiliza catch()
                }
            });
        });
        
        /* 
            Esta funcion realizara un peticion http para verificar 
            si el correo que acaba de ingresar el usuario puede ser 
            usado por un nuevo reclutador o si ya esta en uso por algun 
            otro usuario en la plataforma.
        */
        
    }//Termina funcion verifyExist()

    function insertEmailOnTheList(isMaster,_email){

        //Verificar que el correo no esta en la lista
        if ( emailsMaster.includes(_email) || emailsJrs.includes(_email) ){   

            displayError("{{__('The email is already in the list')}}");
            $('#emailText').val(_email);

        }else{
            //Quitar Errores si los hay
            error.style.display = "none";
            error.innerHTML = "";
        
            //verificar que el correo no esta en uso e intentar insertarlo
            verifyExist(_email).then(function(response){
                if(response=="notFind"){                    
                    //Ningun usuario tiene en uso el correo, insertar en la lista y mostrarla
                    isMaster==true ? emailsMaster.push(_email) : emailsJrs.push(_email);                                       
                    mostrarEmails();

                }else{
                    //Mostrar mensaje de que tipo de usuario tiene en uso el correo

                    if(response=="user")
                        displayError(`{{__("You can't invite an already register user")}}`);

                    else if(response=="company")
                        displayError(`{{__("You can't invite an already register company")}}`);

                    else if(response=="recruiter")
                        displayError(`{{__("It is already your recruiter")}}`);   

                }  
                //Limpiar el input para ingresar otro correo
                $('#emailText').val('');  

            }).catch(function(err){
                //En caso de que no se pudo realizar la peticion http
                displayError("{{__('Something went wrong')}}");
                console.log(err.responseText)
            })     
        } 

    }//Termina funcion insertEmailOnTheList()  

    function displayError(message){
        //Se deplega el div y se agrega el mensaje
        error.style.display = "block";
        error.innerHTML = message;

        //Se agrega la clase para activar la animacion del mensaje
        error.classList.add("shakeIt");

        setTimeout(function(){
            //se remueve para poder activar otra vez la animacion
            error.classList.remove("shakeIt"); 
        },600);

        /*
            Esta funcion solo desplegara un mensaje notificando
            el error ocurrido y agrega una animacion al mensaje
        */
    }//Termina funcion displayError()

</script>
@include('includes.immediate_available_btn')
@endpush