@extends('layouts.app')
@section('content') 
<!-- Header start --> 
@include('includes.header') 
<!-- Header end --> 
<!-- Inner Page Title start --> 
@include('includes.inner_page_title', ['page_title'=>__('Company Profile')]) 
<!-- Inner Page Title end -->
<div class="listpgWraper">
    <div class="container">
        <div class="row">
            @if (Auth::guard('company')->user())
                @include('includes.company_dashboard_menu')                
            @elseif(Auth::guard('recruiter')->user())
                @include('includes.recruiter_dashboard_menu')
            @endif
            <div class="col-md-9 col-sm-8"> 
                <div class="row">
                    <div class="col-md-12">
                        <div class="userccount">
                            <div class="formpanel"> @include('flash::message') 
                                <!-- Personal Information -->
                                <div>
                                    @include('company.inc.profile')
                                </div>
                                @if (Auth::guard('company')->user())
                                <div class="row row-foto-perfil">
                                    <button type="button" class="btn btn-danger delete" data-toggle="modal" data-target="#deleteModalUser">
                                        {{__('Delete account')}}
                                    </button>
                                </div>
                                @endif
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
        <p>{{__('When you request the deletion of your profile, all your data that the Cloik.com platform has stored about you will be deleted including the jobs created and the meetings saved, once you accept this process, it will be irreversible.')}}
        {{__('An email will be sent to applicants to the plaza through the platform notifying the elimination of the meeting')}}
        </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
          <button onclick="deleteCompanyProfile({{$company->id}})" type="button" class="btn btn-danger">{{__('I agree to delete my account')}}</button>
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
        background-color: #d9534f !important;
        width: 100% !important;
        color: #fff !important;
        border-radius: 5px !important;
        padding: 10px !important;
        font-size: 16px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
    }
</style>
@endpush
@push('scripts')
<script src="https://sdk.amazonaws.com/js/aws-sdk-2.528.0.min.js"></script>
<script>
    function deleteCompanyProfile(id){
        let arrayVideoJob=new Array();
        $.post("{{ route('delete.profile.company') }}", {id: id, _method: 'POST', _token: '{{ csrf_token() }}'})
        .done(function(response){
            if (response) {                
                response.forEach(element => {
                    arrayVideoJob.push(element.videoJobURL)
                });
                if (arrayVideoJob.length>0) {
                    Elimina('videoscompany',arrayVideoJob);
                }                 
                $.post("{{ route('company.logout') }}", {id: id, _method: 'POST', _token: '{{ csrf_token() }}'})
                .done(function (data) {
                    window.location.reload();
                })
                .fail(function (e) {
                    console.log(e);
                });
            }else{
                $.post("{{ route('company.logout') }}", {id: id, _method: 'POST', _token: '{{ csrf_token() }}'})
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
@endpush