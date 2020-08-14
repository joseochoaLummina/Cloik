<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Input;
use App\ProfileCv;
use File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use ImgUploader;
use Carbon\Carbon;
use Redirect;
use Newsletter;
use App\User;
use App\Subscription;
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
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use App\Traits\CommonUserFunctions;
use App\Traits\ProfileSummaryTrait;
use App\Traits\ProfileCvsTrait;
use App\Traits\ProfileProjectsTrait;
use App\Traits\ProfileExperienceTrait;
use App\Traits\ProfileEducationTrait;
use App\Traits\ProfileSkillTrait;
use App\Traits\ProfileLanguageTrait;
use App\Traits\Skills;
use App\Http\Requests\Front\UserFrontFormRequest;
use App\Helpers\DataArrayHelper;


class UserController extends Controller
{

    use CommonUserFunctions;
    use ProfileSummaryTrait;
    use ProfileCvsTrait;
    use ProfileProjectsTrait;
    use ProfileExperienceTrait;
    use ProfileEducationTrait;
    use ProfileSkillTrait;
    use ProfileLanguageTrait;
    use Skills;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth', ['only' => ['myProfile', 'updateMyProfile', 'viewPublicProfile']]);
        $this->middleware('auth', ['except' => ['viewPublicProfile','showApplicantProfileEducation', 'showApplicantProfileProjects', 'showApplicantProfileExperience', 'showApplicantProfileSkills', 'showApplicantProfileLanguages']]);
    }

    /**
     * Funcion que carga el perfil publico de un candidato
     */
    public function viewPublicProfile($id)
    {
        $type='candidate';
        $user = User::findOrFail($id);
        $profileCv = $user->getDefaultCv();
        $application_id=0;
        return view('user.applicant_profile')
                        ->with('user', $user)
                        ->with('profileCv', $profileCv)
                        ->with('page_title', $user->getName())
                        ->with('form_title', 'Contact ' . $user->getName())
                        ->with('application_id', $application_id)
                        ->with('type',$type);
    }

    /**
     * Funcion que muestra los comentarios en un perfil de un candidato para una plaza en especifico
     */
    public function showComment (Request $request)
    {
        $user_id=$request->input('user_id');
        $array=DB::table('comments_on_candidates')
                    ->join('job_apply','comments_on_candidates.apply_id','=','job_apply.id')
                    ->join('jobs','jobs.id','=','job_apply.job_id')
                    ->select('comment','comments_on_candidates.created_at','jobs.slug','jobs.title')
                    ->where('job_apply.user_id',$user_id)
                    ->get()
                    ->toArray();
        $html="";
        if ($array) {
            foreach ($array as $comment => $value) {
                $html=$html."<div style='margin:2% ;
                -webkit-box-shadow: 1px 1px 1px #e5e5e5;
                box-shadow: 1px 1px 1px #e5e5e5;
                border-radius: 4px;
                padding: 5px 1px; '>
                <p style='margin:1%;' name='comments' >$value->comment</p>
                <p style='font-size: smaller'>$value->created_at <a href="."/job/{$value->slug}"." >$value->title </a> </p>
                </div>";
            }
        }else{
            $html="";
        }

        echo $html;
    }

    /**
     * Funcion que carga el perfil para editar del candidato
     */
    public function myProfile()
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
        return view('user.edit_profile')
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
    }

    public function updateMyProfile(UserFrontFormRequest $request)
    {
        $user = User::findOrFail(Auth::user()->id);
        /*         * **************************************** */
        if ($request->hasFile('image')) {
            $is_deleted = $this->deleteUserImage($user->id);
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
		
        flash(__('You have updated your profile successfully'))->success();
        return \Redirect::route('my.profile');
    }

    public function addToFavouriteCompany(Request $request, $company_slug)
    {
        $data['company_slug'] = $company_slug;
        $data['user_id'] = Auth::user()->id;
        $data_save = FavouriteCompany::create($data);
        flash(__('Company has been added in favorites list'))->success();
        return \Redirect::route('company.detail', $company_slug);
    }

    public function removeFromFavouriteCompany(Request $request, $company_slug)
    {
        $user_id = Auth::user()->id;
        FavouriteCompany::where('company_slug', 'like', $company_slug)->where('user_id', $user_id)->delete();

        flash(__('Company has been removed from favorites list'))->success();
        return \Redirect::route('company.detail', $company_slug);
    }

    public function myFollowings()
    {
        $user = User::findOrFail(Auth::user()->id);
        $companiesSlugArray = $user->getFollowingCompaniesSlugArray();
        $companies = Company::whereIn('slug', $companiesSlugArray)->get();

        return view('user.following_companies')
                        ->with('user', $user)
                        ->with('companies', $companies);
    }

    /**
     * Funcion que carga todos los mensajes internos del candidato
     */
    public function myMessages()
    {
        $user = User::findOrFail(Auth::user()->id);
        $messages = ApplicantMessage::where('user_id', '=', $user->id)
                ->orderBy('is_read', 'asc')
                ->orderBy('created_at', 'desc')
                ->get();

        return view('user.applicant_messages')
                        ->with('user', $user)
                        ->with('messages', $messages);

        /*
            Esta funcion carga los mensajes directos,
            no las notificaciones de la plataforma
        */
    }

    /**
     * Funcion que carga el contenido de cada mensaje del candidato
     */
    public function applicantMessageDetail($message_id)
    {
        $user = User::findOrFail(Auth::user()->id);
        $message = ApplicantMessage::findOrFail($message_id);
        $message->update(['is_read' => 1]);

        return view('user.applicant_message_detail')
                        ->with('user', $user)
                        ->with('message', $message);
    }

    /**
     * Funcion que elimina la cuenta de un candidato
     */
    public function deleteUser(Request $request) {
        $id_user=Auth::user()->id;
        $is_deleted_img =$this->deleteUserImage($id_user);
        $profileCvs = ProfileCv::where('user_id', '=', $id_user)->get();
        foreach ($profileCvs as $profileCv) {
            $is_deleted_cvs= $this->removeProfileCv($profileCv->id);
        }
        $video=DB::select('CALL sp_delete_user(:id_user)', ['id_user'=>$id_user]);
        $audio=DB::select('select * from log_lang_test where id_user = :id_user', ['id_user'=>$id_user]);
        $videoAudio=['video'=>$video,'audio'=>$audio];
        return $videoAudio;
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return Redirect::route('login');
    }

    /**
     * Funcion que envia un mensaje interno a la compañia y reclutadores master solicitando un cambio en una reunion
     */
    public function changeMeeting(Request $request){
        $id_user=Auth::user()->id;
        $idMeeting=$request->input("id");
        $msg=$request->input("msg");
        $meet=DB::select('SELECT company_id from meetings where id=:id',['id'=>$idMeeting]);
        $recruitersMaster_id=DB::select('SELECT recruiter_id 
                                FROM recruiter_meetings rm
                                INNER JOIN recruiters r
                                    ON rm.recruiter_id=r.id 
                                WHERE rm.meeting_id=:id 
                                    AND r.is_master=1;',
                                ['id'=>$idMeeting]);

        $dataMessage['user_id'] = $id_user;
        $dataMessage['company_id'] = $meet[0]->company_id;
        $dataMessage['type'] = 1;
        $dataMessage['message'] = $msg;
        $dataMessage['receivedfrom'] = 0;
        $dataMessage['meeting_id'] = $idMeeting;
        $meeting = new MeetingController();
        $meeting->sendNotification($dataMessage);

        if(count($recruitersMaster_id)>0){
            foreach($recruitersMaster_id as $value){           
                $dataMessage['user_id'] = $value->recruiter_id;
                $dataMessage['company_id'] = $meet[0]->company_id;
                $dataMessage['type'] = 1;
                $dataMessage['message'] = $msg;
                $dataMessage['receivedfrom'] = 0;
                $dataMessage['meeting_id'] = $idMeeting;
                $meeting = new MeetingController();
                $meeting->sendNotification($dataMessage);
            }
        }      
    }
}