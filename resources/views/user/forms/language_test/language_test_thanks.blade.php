<div class="modal-dialog"> 
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="userccount">
                        <button type="button" onclick="cierra()" class="close" data-dismiss="modal">&times;</button>
                        <h5>{{__('Language test sucessfully completed')}}</h5>            
                        <div class="formpanel">
                            <div class="formrow">
                                <h3>{{__('Language test performed')}}</h3>
                                <p>{{__('Earned a grade of')}} {{$qualification}}%</p>
                                <div>{!! $diff !!}</div>
                            </div>                
                            <div>
                                <button onclick="cerrar()" class="btn btn-success">
                                    OK
                                </button>
                            </div>
                        </div>            
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
