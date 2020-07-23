<?php

namespace App\Http\Controllers;

use Auth;
use ImgUploader;
use Newsletter;
use App\User;
use App\ProfileSummary;
use App\Subscription;
use App\Traits\Cron;
use Illuminate\Http\Request;
use App\Helpers\ProfileArrayHelper;
use App\Helpers\DataArrayHelper;
use App\Http\Requests\ProfileSummaryFormRequest;
use App\Http\Requests\ProfileCvFormRequest;
use App\Http\Requests\ProfileCvFileFormRequest;
use App\Http\Requests\Front\UserFrontFormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use App\Traits\CommonUserFunctions;
use App\Traits\ProfileSummaryTrait;
use App\Traits\ProfileCvsTrait;
use App\Traits\ProfileProjectsTrait;
use App\Traits\ProfileExperienceTrait;
use App\Traits\ProfileEducationTrait;
use App\Traits\ProfileSkillTrait;
use App\Traits\ProfileLanguageTrait;
use App\Traits\Skills;


use DB;
use Input;
use File;
use Carbon\Carbon;
use Redirect;
use App\ApplicantMessage;
use App\Company;
use App\FavouriteCompany;
use App\Gender;
use App\MaritalStatus;
use App\Country;
use App\State;
use App\City;
use App\JobExperience;
use App\JobApply;
use App\CareerLevel;
use App\Industry;
use App\FunctionalArea;
use App\Http\Requests;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;

class CheckProfileController extends Controller
{
    use CommonUserFunctions;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function fullProfile()
    {   
        $genders = DataArrayHelper::langGendersArray();
        $maritalStatuses = DataArrayHelper::langMaritalStatusesArray();
        $nationalities = DataArrayHelper::langNationalitiesArray();
        $countries = DataArrayHelper::langCountriesArray();
        $jobExperiences = DataArrayHelper::langJobExperiencesArray();
        $careerLevels = DataArrayHelper::langCareerLevelsArray();
        $industries = DataArrayHelper::langIndustriesArray();
        $functionalAreas = DataArrayHelper::langFunctionalAreasArray();

        $upload_max_filesize = UploadedFile::getMaxFilesize() / (1048576);
        $user = User::findOrFail(Auth::user()->id);

        $recommendation=DB::select('SELECT recomendations FROM forge.site_settings');
        $array=ProfileArrayHelper::comprueba();
        if (!$array['cumplePerfil'] ) {
            return view('user.complete_profile')
                ->with('genders', $genders)
                ->with('maritalStatuses', $maritalStatuses)
                ->with('nationalities', $nationalities)
                ->with('countries', $countries)
                ->with('jobExperiences', $jobExperiences)
                ->with('careerLevels', $careerLevels)
                ->with('industries', $industries)
                ->with('functionalAreas', $functionalAreas)
                ->with('user', $user)
                ->with('upload_max_filesize', $upload_max_filesize);

        } elseif(!$array['cumpleVideo']) {
            return view('homeProfile')            
                ->with('recommendation',$recommendation[0]->recomendations);
        } else {
            return redirect('home');
        }
    }
    public function updateMyProfile(UserFrontFormRequest $request){
        $user = User::findOrFail(Auth::user()->id);
        
        /*         * **************************************** */
        if ($request->file('image')) {
            $is_deleted = $this->deleteUserImage($user->id);
            // $is_deleted = CommonUserFunctions::deleteUserImage($user->id);
            $image = $request->file('image');
            $fileName = ImgUploader::UploadImage('user_images', $image, $request->input('name'), 300, 300, false);
            $user->image = $fileName;
        }
        /*         * ************************************** */
        $user->first_name = $request->input('first_name');
        $user->middle_name = $request->input('middle_name');
        $user->last_name = $request->input('last_name');
        /*         * *********************** */
        $user->name = $user->getName();
        /*         * *********************** */
        $user->email = $request->input('email');
        if (!empty($request->input('password'))) {
            $user->password = Hash::make($request->input('password'));
        }
        $user->father_name = $request->input('father_name');
        $user->date_of_birth = $request->input('date_of_birth');
        $user->gender_id = $request->input('gender_id');
        $user->marital_status_id = $request->input('marital_status_id');
        $user->nationality_id = $request->input('nationality_id');
        $user->national_id_card_number = $request->input('national_id_card_number');
        $user->country_id = $request->input('country_id');
        $user->state_id = $request->input('state_id');
        $user->city_id = $request->input('city_id');
        $user->phone = $request->input('phone');
        $user->mobile_num = $request->input('mobile_num');
        $user->job_experience_id = $request->input('job_experience_id');
        $user->career_level_id = $request->input('career_level_id');
        $user->industry_id = $request->input('industry_id');
        $user->functional_area_id = $request->input('functional_area_id');
        $user->current_salary = $request->input('current_salary');
        $user->expected_salary = $request->input('expected_salary');
        $user->salary_currency = $request->input('salary_currency');
        $user->street_address = $request->input('street_address');
		$user->is_subscribed = $request->input('is_subscribed', 0);
		
        $user->update();

        $this->updateUserFullTextSearch($user);
		/*************************/
		Subscription::where('email', 'like', $user->email)->delete();
		if((bool)$user->is_subscribed)
		{			
			$subscription = new Subscription();
			$subscription->email = $user->email;
			$subscription->name = $user->name;
			$subscription->save();
			
			/*************************/
			Newsletter::subscribeOrUpdate($subscription->email, ['FNAME'=>$subscription->name]);
			/*************************/
		}
		else
		{
			/*************************/
			Newsletter::unsubscribe($user->email);
			/*************************/
		}
        
        ProfileSummary::where('user_id', '=', $user->id)->delete();
        $summary = $request->input('summary');
        $ProfileSummary = new ProfileSummary();
        $ProfileSummary->user_id = $user->id;
        $ProfileSummary->summary = $summary;
        $ProfileSummary->save();
        return redirect('home-profile');
    }
}
