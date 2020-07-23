<h5>{{__('Language Test')}}</h5><br>
<h6>{{__('Instructions')}}</h6>
<p>{{__('This is an oral test to determine your English language skills, please read the following paragraph and record an audio.')}}</p>
<p>{{__('In case you are not satisfied with your qualification you can do it again.')}}</p>
<hr>
@push('scripts')
    <script src="https://sdk.amazonaws.com/js/aws-sdk-2.528.0.min.js"></script>
@endpush

<div style="padding: 20px; background-color: #00335e;">
@if(isset($data))
    <form>
        <div class="grid-langs">
            <select id="langSelect" class="form-control" style="height: 50px; border-radius: 5px; border: none;">
                @foreach($data['langs'] as $lang)
                <option value="{{$lang->iso_code}}">{{__('Language Test')}} {{__($lang->lang)}}</option>
                @endforeach
            </select>
            <a class="btn" style="background: #0093cf;" href="javascript:;" onclick="hacerTest();">{{__('Take test')}}</a>
        </div>
    </form>
@endif
</div>
<!-- <a href="javascript:;" onclick="showLanguageTestModal();"> {{__('Language Test')}} </a> -->
<div class="modal fade" id="add_language_test_modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form" id="add_edit_language_test" method="POST" action="">{{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">{{__('Language Test')}}</h4>
                    <input type="hidden" id="iso_code">
                    <input type="hidden" id="api_code">
                    <input type="hidden" id="id_paragraph">
                </div>
                <div class="modal-body">
                    <div class="form-body caja">
                        <label id="test-paragraph">{{$lang_test->paragraph}}</label>
                    </div>
                    <br>
                    <div class="row" style="display: flex; justify-content: center; align-items: center;">
                        <div class="col-4 col-sm-5">
                            <select id="listaDeDispositivos" class="form-control"></select>
                        </div>
                        <div class="col-4 col-sm-6" id="audioTest"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row" style="display:flex; justify-content:space-around;">
                        <div class="col-1 col-sm-2">
                            <i id="btnComenzarGrabacion" class="fa fa-circle fa-stack-2x text-success fa-microphone noselect" style="cursor: pointer;"> {{__('Start')}}</label></i>
                            <i id="btnDetenerGrabacion" class="fa fa-circle fa-stack-2x text-danger fa-microphone-slash noselect" style="cursor: pointer;"> {{__('Stop')}}</label></i>
                        </div>
                        <div class="col-1 col-sm-2">
                            <p id="duracion"></p>
                        </div>
                        <div class="col-1 col-sm-4"></div>
                        <div class="col-1 col-sm-4">
                            <button id="btnEnviarAudio" type="button" class="btn btn-primary">{{__('Test Finished')}}   <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.modal-content --> 
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="add_language_test_thanks_modal" role="dialog"></div>

<style type="text/css">
   .caja {
        margin: 0px 20px 0px 20px;
        padding: 10px;
        font-size: 17px;
        border-radius: 4px;
        border: solid 1px;
        border-color: #D5D7D8;
        background-color: #f9f8f6 !important;
        background: linear-gradient(#f8f8f8, #fff);
        box-shadow: 0 8px 16px -8px rgba(0,0,0,0.4);
        overflow: hidden;
        position: relative;
    }
</style>


@push('scripts') 
<script type="text/javascript">    
    $(document).ready(function(){
        $("#audioTest").hide();
        $("#btnDetenerGrabacion").hide();
        $("#btnEnviarAudio").prop( "disabled", true ); //Disable
    });

    
    function hacerTest() {
        var iso_code = $('#langSelect').val();
        var idiomas = {!! str_replace("'", "\'", json_encode($data['langs'])) !!};
        var filtro = idiomas.filter(x => x.iso_code == iso_code);
        var api_code = filtro[0].api_code;
        showLanguageTestModal(iso_code, api_code);
    }

    function showLanguageTestModal(iso_code, api_code){
        $.ajax({
            type: "GET",
            url: "{{ route('test.paragraph') }}",
            data: {lang: iso_code},
            success: function(responseData) {
                $("#test-paragraph").html('');
                $("#test-paragraph").html(responseData[1]);  
                document.getElementById("id_paragraph").value = responseData[0];
                document.getElementById("iso_code").value = iso_code;  
                document.getElementById("api_code").value = api_code;              
                $("#btnComenzarGrabacion").show();
            },
            error: function(errorData) {
                console.log("error ", errorData);
                $("#test-paragraph").html("{{__('Test Not Available')}}");
                $("#btnComenzarGrabacion").hide();
            }
        });
        $("#add_language_test_modal").modal();
        
        $("#audioTest").hide();
    }
    function cierra(){
        window.location.reload();
    }
    function cerrar(){
        window.location.reload();
    }
</script> 
@include('user.forms.language_test.process_language_test')
@endpush