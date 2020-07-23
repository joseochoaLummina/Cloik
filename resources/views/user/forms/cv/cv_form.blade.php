<div class="modal-body">
    <div class="form-body">
        <div class="formrow" id="div_title">
            <span style="font-weight: bold;">{{__('CV title')}}</span>
            <input class="form-control" id="title" placeholder="{{__('CV title')}}" name="title" type="text" value="{{(isset($profileCv)? $profileCv->title:'')}}">
            <span class="help-block title-error"></span> </div>

        @if(isset($profileCv))
        <div class="formrow">
            {{ImgUploader::print_doc("cvs/$profileCv->cv_file", $profileCv->title, $profileCv->title)}}
        </div>
        @endif

        <div class="formrow" id="div_cv_file" >
            <input name="cv_file" id="cv_file" type="file" style="font-weight: bold;"/>
            <span class="help-block cv_file-error"></span> </div>

        <div class="formrow" id="div_is_default">
            <label for="is_default" class="bold" style="font-weight: bold;">{{__('Is default?')}}</label>
            <div class="radio-list">
                <?php
                $val_1_checked = '';
                $val_2_checked = 'checked="checked"';

                if (isset($profileCv) && $profileCv->is_default == 1) {
                    $val_1_checked = 'checked="checked"';
                    $val_2_checked = '';
                }
                ?>

                <label class="radio-inline"><input id="default" name="is_default" type="radio" value="1" {{$val_1_checked}}> {{__('Yes')}} </label>
                <label class="radio-inline"><input id="not_default" name="is_default" type="radio" value="0" {{$val_2_checked}}> {{__('No')}} </label>
            </div>
            <span class="help-block is_default-error"></span>
        </div>
    </div>