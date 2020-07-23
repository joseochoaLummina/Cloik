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
            <li> <span>Language Test</span> </li>
        </ul>
    </div>
    <!-- END PAGE BAR --> 
    <!-- BEGIN PAGE TITLE-->
    <h3 class="page-title">Manage Language Test <small>Language Test</small> </h3>
    <!-- END PAGE TITLE--> 
    <!-- END PAGE HEADER-->
    <div class="row">
        <div class="col-md-12"> 
            <!-- Begin: life time stats -->
            <div class="portlet light portlet-fit portlet-datatable bordered">
                <div class="portlet-title">
                    <div class="caption"> <i class="icon-settings font-dark"></i> <span class="caption-subject font-dark sbold uppercase">Language Test</span> </div>
                </div>
                <div class="portlet-body">
                    <div class="row">
                        <div>
                            {!! Form::model($datos,array('method' => 'post', 'route' => array('post.new.test.language'), 'class' => 'form', 'id'=>'formP')) !!}
                            {!! Form::textarea('newParagraph', null, array('class'=>'col-md-12"','rows'=>"5" ,'cols'=>"100",'id'=>'newParagraph','required' ,'placeholder'=>__('Paragraphs'))) !!}
                            <div class="formrow" style="margin:1%;">
                                <button type="submit" class="btn">{{__('Save Paragraphs')}}
                                    <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                                </button>
                            </div>
                            {!! Form::close() !!}
                            @if ($datos)
                                @foreach ($datos as $item)
                                    <div class="col-md-5"style="padding: 1%; margin:1%;">
                                        <br>
                                        <textarea name="oldParagraph" id="{{$item->id}}" cols="50" rows="5" >{{$item->paragraph}}</textarea>
                                        <div>
                                            <button type="button" onclick="eliminar({{$item->id}})" class="btn btn-danger" >{{__('Remove Paragraphs')}}</button>
                                            <button type="button" onclick="actualizar({{$item->id}})" class="btn btn-primary" >{{__('Modify Paragraphs')}}</button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <h1>{{__('You have no language test')}}</h1>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- END CONTENT BODY --> 
</div>
@endsection
@push('scripts') 
<script>
    function actualizar(id) {
        paragraphs=$("#"+id).val();
        $.post("{{ route('put.paragraphs' ) }}",{id:id, paragraphs:paragraphs, _method: 'PUT', _token: '{{ csrf_token() }}'}).done(function (response) {
            $("#"+id).css('border','solid 1px green');
        }).fail(function (e) {
            console.log(e);
            
        });
    }
    function eliminar(id) {
        
        $.post("{{ route('delete.paragraphs' ) }}",{id:id, _method: 'DELETE', _token: '{{ csrf_token() }}'}).done(function (response) {
            $("#"+id).parent().remove();
        }).fail(function (e) {
            console.log(e);
            
        });
    }
</script> 
@endpush