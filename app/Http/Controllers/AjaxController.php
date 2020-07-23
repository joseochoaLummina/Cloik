<?php

namespace App\Http\Controllers;

use DB;
use Input;
use Form;
use App\Helpers\MiscHelper;
use App\Helpers\DataArrayHelper;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use App\Traits\CountryStateCity;

class AjaxController extends Controller
{

    use CountryStateCity;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function filterDefaultStates(Request $request)
    {
        $country_id = $request->input('country_id');
        $state_id = $request->input('state_id');
        $new_state_id = $request->input('new_state_id', 'state_id');
        $states = DataArrayHelper::langStatesArray($country_id);
        $dd = Form::select('state_id', ['' => __('Select State')] + $states, $state_id, array('id' => $new_state_id, 'class' => 'form-control'));
        echo $dd;
    }

    public function filterDefaultCities(Request $request)
    {
        
        $state_id = $request->input('state_id');
        $city_id = $request->input('city_id');
        
        $cities = DataArrayHelper::defaultCitiesArray($state_id);
        $dd = Form::select('city_id', ['' => __('Select City')] + $cities, $city_id, array('id' => 'city_id', 'class' => 'form-control'));
        echo $dd;
    }

    /*     * ***************************************** */

    public function filterLangStates(Request $request)
    {
        $country_id = $request->input('country_id');
        $state_id = $request->input('state_id');
        $new_state_id = $request->input('new_state_id', 'state_id');
        $states = DataArrayHelper::langStatesArray($country_id);
        $dd = Form::select('state_id', ['' => __('Select State')] + $states, $state_id, array('id' => $new_state_id, 'class' => 'form-control'));
        echo $dd;
    }

    public function filterLangCities(Request $request)
    {
        $state_id = $request->input('state_id');
        $city_id=$request->input('city_id');
        $cities = DataArrayHelper::langCitiesArray($state_id);
        $dd = Form::select('city_id', ['' => __('Select City')] + $cities, $city_id, array('id' => 'city_id', 'class' => 'form-control'));
        echo $dd;
    }
    /*     * ***************************************** */

    public function filterStates(Request $request)
    {
        $country_id = $request->input('country_id');
        $state_id = $request->input('state_id');
        $new_state_id = $request->input('new_state_id', 'state_id');
        $states = DataArrayHelper::langStatesArray($country_id);
        $dd = Form::select('state_id[]', ['' => __('Select State')] + $states, $state_id, array('id' => $new_state_id, 'class' => 'form-control'));
        echo $dd;
    }

    public function filterCities(Request $request)
    {    
        $country_id = $request->input('country_id');
        $state_id = $request->input('state_id');
        $city_id = $request->input('city_id');
        
        $array2=DB::table('cities')
                ->join('states','cities.state_id','=','states.state_id')
                ->select('cities.city','cities.city_id')
                ->where('states.country_id',$country_id)
                ->where('cities.is_active', '1')
                ->get();
        $cities=collect($array2)->pluck('city', 'city_id')->toArray();

        $dd = Form::select('city_id[]', ['' => __('Select City')] + $cities, $city_id, array('id' => 'city_id', 'class' => 'form-control'));
        
        echo $dd;
    }

    /*     * ***************************************** */

    public function filterDegreeTypes(Request $request)
    {
        $degree_level_id = $request->input('degree_level_id');
        $degree_type_id = $request->input('degree_type_id');

        $degreeTypes = DataArrayHelper::langDegreeTypesArray($degree_level_id);

        $dd = Form::select('degree_type_id', ['' => __('Select degree type')] + $degreeTypes, $degree_type_id, array('id' => 'degree_type_id', 'class' => 'form-control'));
        echo $dd;
    }
    public function showRecomendacion(Request $request){
        $job_id=$request->input('id_job');
        $array2=DB::select('select video_recommendations from jobs where id = :id', ['id'=>$job_id]);
        $array=collect($array2)->pluck('video_recommendations')->toArray();
        
        return $array;
    }

}
