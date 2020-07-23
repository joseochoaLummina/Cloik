{{-- Contenido del modal:invite_candidate_modal --}}
<div id="meeting-content">
    <div class="modal-content" >
            <div class="modal-header">
            {{-- Titulo del modal y boton de cierre --}}
                <h5 class="modal-title" id="modalTitle">{{__('Invite Candidate')}}
                <button type="button" class="close" id="close-apply-modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </h5>
            </div>
        <div class="modal-body row" style="margin: 0; overflow-y: auto; padding: 0.5rem">
            <div class="my-list-candidates noselect">
                @if ($cadidatos)
                    @foreach ($cadidatos as $item)
                    <div>
                        <div>
                            <div class="formrow"> {{ ImgUploader::print_image("user_images/$item->image", 400, 400) }} </div>                        
                        </div>
                        <div>
                            <p><a href="{{route('user.profile', [$item->id])}}" class="name row-md-3">{{$item->name}}</a></p>
                            <p><a href="javascript:void(0);"class="mail row-md-3" style="word-wrap: break-word;">{{$item->email}}</a></p>
                            <p>{{__('Country')}}: {{$item->country}}</p>
                            <p>{{__('City')}}: {{$item->city}}</p>
                        </div>
                        @if (in_array($item->id, $invitados))
                                <div>
                                    <button disabled="disabled" class="btn btn-success">{{__('Guest candidate')}}</button>
                                </div>
                        @else                            
                            <div>
                                <a href="{{route('invite.candidate', [$id_job,$item->id])}}" class="btn btn-success invite-button" id="{{$item->id}}">{{__('Invite Candidate')}}</a>
                            </div>
                        @endif
                    </div>
                    @endforeach
                @else
                    <h1>{{__('There are no candidates that meet your requirements')}}</h1>
                @endif
            </div>
        </div>
        <div class="modal-footer">
            
        </div>
    </div>
</div>

@push('styles')
<style type="text/css">

</style>
@endpush

@push('scripts')
<script>
    
</script>
@include('includes.immediate_available_btn')
@endpush