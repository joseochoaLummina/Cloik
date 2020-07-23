<div class="modal-body">
    <div class="form-body">
        <div class="formrow" id="div_language_id">
            <span style="font-weight: bold;">{{__('Select language')}}</span>
            <?php
            $language_id = (isset($profileLanguage) ? $profileLanguage->language_id : null);
            ?>
            {!! Form::select('language_id', [''=>__('Select language')]+$languages, $language_id, array('class'=>'form-control', 'id'=>'language_id')) !!} <span class="help-block language_id-error"></span> </div>
        <div class="formrow" id="div_language_level_id">
            <span style="font-weight: bold;">{{__('Select Language Level')}}</span>
            <?php
            $language_level_id = (isset($profileLanguage) ? $profileLanguage->language_level_id : null);
            ?>
            {!! Form::select('language_level_id', [''=>__('Select Language Level')]+$languageLevels, $language_level_id, array('class'=>'form-control', 'id'=>'language_level_id')) !!} <span class="help-block language_level_id-error"></span> </div>
    </div>
</div>
