@extends('layouts.app')
@section('content') 
<!-- Header start --> 
@include('includes.header') 
<!-- Header end --> 
<!-- Inner Page Title start --> 
@include('includes.inner_page_title', ['page_title'=>__('My Profile')]) 
<!-- Inner Page Title end -->
<div class="listpgWraper">
    <div class="container">
        <div class="row">
            @include('includes.user_dashboard_menu')

            <div class="col-md-9 col-sm-8"> 
                <div class="row">
                    <div class="col-md-12">
                        <div class="userccount">
                            <div class="formpanel"> @include('flash::message') 
                                <!-- Personal Information -->
                                @include('user.inc.profile')
                                @include('user.inc.summary')
                                @include('user.forms.cv.cvs')
                                <!-- @include('user.forms.project.projects') -->
                                @include('user.forms.experience.experience')
                                @include('user.forms.education.education')
                                @include('user.forms.skill.skills')
                                @include('user.forms.language.languages')
                            </div>
                            <div class="row row-foto-perfil">
                                <button type="button" class="btn btn-danger delete" data-toggle="modal" data-target="#deleteModalUser">
                                    {{__('Delete account')}}
                                  </button>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
</div>
<!-- Modal -->
<div class="modal fade" id="deleteModalUser" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" >{{__('Delete account')}}</h5>
        </div>
        <div class="modal-body">
          <p>{{__('When requesting the Deletion of your profile, all your data that the Cloik.com platform has stored about you will be deleted, once you accept this process it will be irreversible')}}</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
          <button onclick="deleteProfile({{$user->id}})" type="button" class="btn btn-danger">{{__('I agree to delete my account')}}</button>
        </div>
      </div>
    </div>
</div>
@include('includes.footer')
@endsection
@push('styles')
<style type="text/css">
    .userccount p{ text-align:left !important;}
    .delete{
        width: 100%;
        color: #fff;
        border-radius: 5px;
        padding: 10px;
        font-size: 16px;
        font-weight: 700;
        text-transform: uppercase;
    }
</style>
@endpush
@push('scripts')
<script src="https://sdk.amazonaws.com/js/aws-sdk-2.528.0.min.js"></script>
<script>
    function deleteProfile(id){
        let arrayAudio=new Array();
        let arrayVideo=new Array();
        $.post("{{ route('user.delete.user') }}", {id: id, _method: 'POST', _token: '{{ csrf_token() }}'})
        .done(function(response){
            if (response) {                
                response['audio'].forEach(element => {
                    arrayAudio.push(element['url'].replace('https://filescloik.s3.us-east-2.amazonaws.com/audios/',''));
                }); 
                Elimina('audios',arrayAudio);
                response['video'].forEach(element => {
                    arrayVideo.push(element['marca']+element['video']+'.'+element['ext'] );
                }); 
                Elimina('videos',arrayVideo);
                
                $.post("{{ route('logout.user') }}", {id: id, _method: 'POST', _token: '{{ csrf_token() }}'})
                .done(function (data) {
                    window.location.reload();
                })
                .fail(function (e) {
                    console.log(e);
                });
            }else{
                $.post("{{ route('logout.user') }}", {id: id, _method: 'POST', _token: '{{ csrf_token() }}'})
                .done(function (data) {
                    window.location.reload();
                })
                .fail(function (e) {
                    console.log(e);
                });
            }

        })
        .fail(function(e){
            console.log(e);
        })
    }
    function Elimina(carpeta,array) {
        AWS.config.update({            
            // useAccelerateEndpoint: true,
            accessKeyId : ' AKIAQYEJSJ2ZH2ZFRBXG',
            secretAccessKey : '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'
        });
        AWS.config.region = 'us-east-2';
        var bucket = new AWS.S3();
        
        array.forEach(element => {
            bucket.deleteObject({Bucket: `filescloik/${carpeta}`,Key:element}, function(err, data) {
                if (data) {
                    console.log('data',data);
                    
                }else if(err){
                    console.log('error'+err);
                    
                }
            });

        });
    }   
</script>
@include('includes.immediate_available_btn')
@endpush