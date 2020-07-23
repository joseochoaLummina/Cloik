<?php

namespace App\Http\Controllers\Recruiter;

use Mail;
use Hash;
use File;
use ImgUploader;
use Auth;
use Validator;
use DB;
use Input;
use Newsletter;
use Redirect;
use App\User;
use App\Company;
use App\Recruiter;
use App\Subscription;
use App\CompanyMessage;
use App\ApplicantMessage;
use App\Http\Requests;
use App\Country;
use App\CountryDetail;
use App\State;
use App\City;
use App\Industry;
use App\SiteSetting;
use App\FavouriteCompany;
use App\FavouriteApplicant;
use App\OwnershipType;
use App\JobApply;
use App\RecruiterInvitation;
use App\Mail\ChangeMeetingMail;
use App\Mail\MeetingMail;
use App\Mail\CancelMeetingMail;
use App\Http\Controllers\MeetingController;
use App\Helpers\DataArrayHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\RecruiterTrait;
use App\Http\Requests\Front\CompanyFrontFormRequest;
use App\Traits\Cron;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth as FacadesAuth;


class RecruiterController extends Controller
{

    use RecruiterTrait;
    use Cron;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('recruiter');
        $this->runCheckPackageValidity();
    }


    public function viewRecruiterHome(Request $request) 
    {
        $company_id=Auth::guard('recruiter')->user()->id_company;
        $array=DB::select('select J.id as job_id, J.title as title, J.slug as slug, J.expiry_date as expiry_date, J.created_at as created_at, count(JV.id) as cantidad from job_views JV join jobs J on JV.job_id=J.id where J.company_id=:company_id group by J.id order by count(JV.id) desc;', ['company_id'=>$company_id]);
        if (Auth::guard('recruiter')->user()->recruiterType()) {
            return view('recruiters.recruiters_home_master')
                    ->with('array',$array);
        } else {
            return view('recruiters.recruiters_home_jr')
                    ->with('array',$array);
        }
        
    }

    public function recruiterProfile()
    {
        $recruiter=Auth::guard('recruiter')->user();
        $company = Company::findOrFail($recruiter->id_company);
        return view('recruiters.edit_profile')
                        ->with('recruiter', $recruiter)
                        ->with('company', $company);
    }
    
    public function updateRecruiterProfile(Request $request)
    {
        $recruiter=Auth::guard('recruiter')->user();
        if ($request->file('image')) {
            $is_deleted = $this->deleteRecruiterLogo($recruiter->id);
            $image = $request->file('image');
            $fileName = ImgUploader::UploadImage('recruiters_images', $image, $request->input('name'), 300, 300, false);
            $recruiter->image = $fileName;
        }
        $recruiter->name = $request->input('name');
        $recruiter->lastname = $request->input('lastname');
        $recruiter->phone = $request->input('phone');
        $recruiter->email = $request->input('email');
        $recruiter->update();
        return \Redirect::route('recruiter.profile');
    }
    public function inactiveUser(Request $request) 
    {

    }
    // public function companyProfile()
    // {
    //     $countries = DataArrayHelper::defaultCountriesArray();
    //     $industries = DataArrayHelper::defaultIndustriesArray();
    //     $ownershipTypes = DataArrayHelper::defaultOwnershipTypesArray();
    //     $company = Company::findOrFail(Auth::guard('recruiter')->user()->id_company);
    //     return view('company.edit_profile')
    //                     ->with('company', $company)
    //                     ->with('countries', $countries)
    //                     ->with('industries', $industries)
    //                     ->with('ownershipTypes', $ownershipTypes);
    // }
    // public function updateCompanyProfile(CompanyFrontFormRequest $request)
    // {
    //     $company = Company::findOrFail(Auth::guard('recruiter')->user()->id_company);
    //     /*         * **************************************** */
    //     if ($request->file('logo')) {
    //         $is_deleted = $this->deleteCompanyLogo($company->id);
    //         $image = $request->file('logo');
    //         $fileName = ImgUploader::UploadImage('company_logos', $image, $request->input('name'), 300, 300, false);
    //         $company->logo = $fileName;
    //     }
    //     /*         * ************************************** */
    //     $company->name = $request->input('name');
    //     $company->email = $request->input('email');
    //     if (!empty($request->input('password'))) {
    //         $company->password = Hash::make($request->input('password'));
    //     }
    //     $company->ceo = $request->input('ceo');
    //     $company->industry_id = $request->input('industry_id');
    //     $company->ownership_type_id = $request->input('ownership_type_id');
    //     $company->description = $request->input('description');
    //     $company->location = $request->input('location');
    //     $company->map = $request->input('map');
    //     $company->no_of_offices = $request->input('no_of_offices');
    //     $website = $request->input('website');
    //     $company->website = (false === strpos($website, 'http')) ? 'https://' . $website : $website;
    //     $company->no_of_employees = $request->input('no_of_employees');
    //     $company->established_in = $request->input('established_in');
    //     // $company->fax = $request->input('fax');
    //     $company->phone = $request->input('phone');
    //     $company->facebook = $request->input('facebook');
    //     $company->twitter = $request->input('twitter');
    //     $company->linkedin = $request->input('linkedin');
    //     $company->google_plus = $request->input('google_plus');
    //     $company->pinterest = $request->input('pinterest');
    //     $company->country_id = $request->input('country_id');
    //     $company->state_id = $request->input('state_id');
    //     $company->city_id = $request->input('city_id');
    //     $company->is_subscribed = $request->input('is_subscribed', 0);
        
    //     $company->description = str_replace("\"", "'", $company->description);
		
    //     $company->slug = str_slug($company->name, '-') . '-' . $company->id;
    //     $company->update();
	// 	/*************************/
	// 	Subscription::where('email', 'like', $company->email)->delete();
	// 	if((bool)$company->is_subscribed)
	// 	{			
	// 		$subscription = new Subscription();
	// 		$subscription->email = $company->email;
	// 		$subscription->name = $company->name;
	// 		$subscription->save();
	// 		/*************************/
	// 		Newsletter::subscribeOrUpdate($subscription->email, ['FNAME'=>$subscription->name]);
	// 		/*************************/
	// 	}
	// 	else
	// 	{
	// 		/*************************/
	// 		Newsletter::unsubscribe($company->email);
	// 		/*************************/
	// 	}
    //     flash(__('Company has been updated'))->success();
    //     return \Redirect::route('recruiter.company.profile');
    // }
    public function postedJobs()
    {   
        $jobs=Auth::guard('recruiter')->user()->jobs()->paginate(10);
        return view('job.company_posted_jobs')->with('jobs', $jobs);
        
    }

    public function listAppliedUsers($job_id){
        $job_applications = JobApply::where('job_id', '=', $job_id)->Where('state','=',1) ->get();
        return view('job.job_applications')
                        ->with('job_applications', $job_applications)
                        ->with('job_id',$job_id);
    }

    public function applicantProfile($application_id){

        $job_application = JobApply::findOrFail($application_id);
        $user = $job_application->getUser();
        $job = $job_application->getJob();
        $company = Company::findOrFail(Auth::guard('recruiter')->user()->id_company);
        $profileCv = $job_application->getProfileCv();
        $type='company';
        
        /*         * ********************************************** */
        $num_profile_views = $user->num_profile_views + 1;
        $user->num_profile_views = $num_profile_views;
        $user->update();
        /*         * ********************************************** */
        $video=DB::select('SELECT U.name,video,title,ext,dir,marca,VA.is_active as video_active FROM video_apply VA join users U on VA.id_user=U.id where VA.id=:video_apply_id and VA.id_user=:user_id',['video_apply_id'=>$job_application->video_apply_id,'user_id'=>$user->id]);
        return view('user.applicant_profile')
                        ->with('job_application', $job_application)
                        ->with('user', $user)
                        ->with('job', $job)
                        ->with('company', $company)
                        ->with('profileCv', $profileCv)
                        ->with('page_title', 'Applicant Profile')
                        ->with('form_title', 'Contact Applicant')
                        ->with('application_id', $application_id)
                        ->with('type',$type)
                        ->with('datos',$video);
    }

    public function listFavouriteAppliedUsers(Request $request, $job_id){
        $company_id = Auth::guard('recruiter')->user()->id_company;
        //$user_ids = FavouriteApplicant::where('job_id', '=', $job_id)->where('company_id', '=', $company_id)->pluck('user_id')->toArray();
        //$job_applications = JobApply::where('job_id', '=', $job_id)->whereIn('user_id', $user_ids)->get();
        $user_ids = FavouriteApplicant::where('company_id', '=', $company_id)->pluck('user_id')->toArray();
        $job_applications = JobApply::where('job_id', '=', $job_id)->where('state','=', 1)->whereIn('user_id', $user_ids)->get();       
        return view('job.job_applications')
                        ->with('job_applications', $job_applications)
                        ->with('job_id',$job_id);
    }
    
    public function companyFollowers(){
        $company = Company::findOrFail(Auth::guard('recruiter')->user()->id_company);
        $userIdsArray = $company->getFollowerIdsArray();
        $users = User::whereIn('id', $userIdsArray)->get();

        return view('company.follower_users')
                        ->with('users', $users)
                        ->with('company', $company);
    }

    public function userProfile($application_id){
        $user = User::findOrFail($application_id);
        $profileCv = $user->getDefaultCv();
        $company = Company::findOrFail(Auth::guard('recruiter')->user()->id_company);
        /*         * ********************************************** */
        $num_profile_views = $user->num_profile_views + 1;
        $user->num_profile_views = $num_profile_views;
        $user->update();
        $type='view';
        /*         * ********************************************** */
        return view('user.applicant_profile')
                        ->with('user', $user)
                        ->with('profileCv', $profileCv)
                        ->with('company', $company)
                        ->with('application_id', $application_id)
                        ->with('type',$type)
                        ->with('page_title', 'Job Seeker Profile')
                        ->with('form_title', 'Contact Job Seeker');
    }
    public function addToBlackList(Request $request)
    {
        $application_id = $request->input('application_id');
        $company_id = $request->input('company_id');
        $user_id = $request->input('user_id');
        $type = $request->input('type');
        $comment = $request->input('comment');
        $add=DB::insert('insert into lista_negra(id_empresa,id_candidato,id_reclutador, comentario)
        values(:company_id, :user_id, :reclutador_id, :comment);', ['company_id'=>$company_id, 'user_id'=>$user_id, 'reclutador_id'=>$company_id, 'comment'=>$comment]);
        flash(__('Job seeker has been added in black list'))->success();
        if ($type==='view') {
            return response()->json([
                'redirect_to' => route('recruiter.user.profile', $application_id)
            ]);
        } else {
            return response()->json([
                'redirect_to' => route('recruiter.applicant.profile', $application_id)
            ]);
        }
    }
    public function deleteToBlackList($application_id,$user_id,$type,$company_id)
    {
        $id=DB::select('SELECT id FROM lista_negra where id_candidato=:user_id and id_empresa=:company_id', ['user_id'=>$user_id,'company_id'=>$company_id]);
        $remove=DB::statement('DELETE FROM lista_negra WHERE id=:id', ['id'=>$id[0]->id]);
        flash(__('Job seeker has been remove in black list'))->error();
        if ($type==='view') {
            return \Redirect::route('recruiter.user.profile', $application_id);
        } else {
            return \Redirect::route('recruiter.applicant.profile', $application_id);
        }
    }
    public function deleteFromBlackList($user_id, $company_id)
    {
        $id=DB::select('SELECT id FROM lista_negra where id_candidato=:user_id and id_empresa=:company_id', ['user_id'=>$user_id,'company_id'=>$company_id]);
        $remove=DB::statement('DELETE FROM lista_negra WHERE id=:id', ['id'=>$id[0]->id]);
        flash(__('Job seeker has been remove in black list'))->error();
        return \Redirect::route('recruiter.show.blacklist');
    }
    public function showBlacklist(Request $request) 
    {
        $companyId = Auth::guard('recruiter')->user()->id_company;
        $users = DB::select('Select LN.id, U.id as id_user, U.image, LN.id_empresa,
        U.name as username, LN.id_reclutador, LN.comentario, if((LN.id_reclutador = LN.id_empresa), C.name, 
        (select concat(R.name, " ", R.lastname) from recruiters R where R.id = LN.id_reclutador)) as recruiter from lista_negra LN
        inner join users U on LN.id_candidato = U.id
        inner join companies C on LN.id_empresa = C.id where LN.id_empresa = :id_company;', ['id_company'=>$companyId]);
        return view('company.company_blacklist')->with('users', $users);

    }
    public function addToFavouriteApplicant($application_id, $user_id,$type, $company_id)
    {    
        $data['user_id'] = $user_id;
        $data['company_id'] = $company_id;
        $data_save = FavouriteApplicant::create($data);
        flash(__('Job seeker has been added in favorites list'))->success();
        if ($type==='view') {
            return \Redirect::route('recruiter.user.profile', $application_id);
        } else {
            return \Redirect::route('recruiter.applicant.profile', $application_id);
        }
    }

    public function removeFromFavouriteApplicant($application_id, $user_id,$type, $company_id)
    {
        $data['user_id'] = $user_id;
        $data['company_id'] = $company_id;
        FavouriteApplicant::where('user_id', $user_id)
                ->where('company_id', '=', $company_id)
                ->delete();
        flash(__('Job seeker has been removed from favorites list'))->success();
        if ($type==='view') {
            return \Redirect::route('recruiter.user.profile', $application_id);
        } else {
            return \Redirect::route('recruiter.applicant.profile', $application_id);
        }
    }

    public function getCompanyMeetings(Request $request) {
        $dateNow = date('Y-m-d', time());
        $recruiter=Auth::guard('recruiter')->user();
        $meetings = DB::select('SELECT A.id, A.planned_date, A.planned_time, B.image,
         B.name, B.email, D.title from meetings A inner join users B on 
         A.user_id = B.id inner join job_apply C on C.id = A.job_apply_id 
         inner join jobs D on C.job_id=D.id inner join recruiter_meetings E on A.id=E.meeting_id where A.planned_date >= :date and 
         A.company_id = :id_company and A.state = 0 and E.recruiter_id=:recruiter_id and E.state=true order by A.planned_date ASC', 
         ['date' => $dateNow, 'id_company'=>$recruiter->id_company,'recruiter_id'=>$recruiter->id]);
        $meetings = collect($meetings);

        return view('company.meetings')
                    ->with('meetings', $meetings);
    }

    public function getScheduleMeeting($user_id,$job_id,$company_id){   
        $arrayRecruiter=array();
        $recruiterSelect=array();
        $companyId = Auth::guard('recruiter')->user()->id_company;
        $job_apply_id=DB::select('SELECT id from job_apply where user_id=:user_id and job_id=:job_id and state=1 ORDER BY id desc', ['user_id'=>$user_id,'job_id'=>$job_id]);
        $array=DB::select('SELECT id,planned_date,planned_time,user_id,company_id,create_at,job_apply_id FROM meetings where job_apply_id=:job_apply_id and user_id=:user_id',['user_id'=>$user_id,'job_apply_id'=>$job_apply_id[0]->id] );
        if (count($array)>0) {
            $arrayRecruiter=DB::select('select recruiter_id from recruiter_meetings where meeting_id=:meeting_id', ['meeting_id'=>$array[0]->id]);
        }
        foreach ($arrayRecruiter as $key => $value) {
            array_push($recruiterSelect,$value->recruiter_id);
        }
        $recruiter=DB::select('select * from recruiters where id_company = :companyId and is_active=1', ['companyId'=>$companyId]);
        
        return view('job.modal_meeting')
                    ->with('datos', $array)
                    ->with('user_id',$user_id)
                    ->with('job_id',$job_id)
                    ->with('company_id',$company_id)
                    ->with('recruiter',$recruiter)
                    ->with('arrayRecruiter',$recruiterSelect);
    }

    public function saveMeeting(Request $request)
    {
       $user_id=$request->input('user_id');
       $job_id=$request->input('job_id');
       $company_id=$request->input('company_id');
       $date=$request->input('date');
       $time=$request->input('time');
       $job_apply_id=DB::select('SELECT id from job_apply where user_id=:user_id and job_id=:job_id and state=1', ['user_id'=>$user_id,'job_id'=>$job_id]);

       $new_meeting_id = DB::table('meetings')->insertGetId(
           array('planned_date'=>$date, 'planned_time'=>$time, 'user_id'=>$user_id, 'company_id'=>$company_id, 'job_apply_id'=>$job_apply_id[0]->id, 'state'=>0 )
       );
       $recruiter=$request->input('selected');
       if ($recruiter!=null) {
        foreach ($recruiter as $key => $value) {
            DB::insert('INSERT INTO recruiter_meetings(meeting_id,recruiter_id,state)
            VALUES(:meeting_id,:recruiter_id,1) ', ['meeting_id'=>$new_meeting_id, 'recruiter_id'=>$value]);
        }
       }
       $user = DB::select('select first_name, last_name, email from users where id = :id', ['id'=>$user_id]);
       $company = DB::select('select name from companies where id = :id', ['id'=>$company_id]);
       $slug = DB::select('select title from jobs where id = :id', ['id'=>$job_id]);
       
       $siteSetting = SiteSetting::findOrFail(1272);

       $data['name_user'] = $user[0]->first_name . ' ' . $user[0]->last_name;
       $data['companyName'] = $company[0]->name;
       $data['slugJob'] = $slug[0]->title;
       $data['dateMeeting'] = $request->input('date');
       $data['timeMeeting'] = $request->input('time');
       $data['usermail'] = $user[0]->email;
       $data['frommail'] = $siteSetting->mail_from_address;
       $data['fromname'] = $siteSetting->site_name;
       $data['siteSetting'] = $siteSetting;
       Mail::send(new MeetingMail($data));

       $dataMessage['user_id'] = $user_id;
       $dataMessage['company_id'] = $company_id;
       $dataMessage['type'] = 1;
       $dataMessage['message'] = $company[0]->name." has registered a meeting with you, regarding your application in the ".$slug[0]->title." position. The meeting has been set for the date ".$request->input('date')." at ".$request->input('time').", please accept or deny the meeting.";
       $dataMessage['receivedfrom'] = 1;
       $dataMessage['meeting_id'] = $new_meeting_id;
       $meeting = new MeetingController();
       $meeting->sendNotification($dataMessage);
          
    }

    public function updateMeeting(Request $request)
    {
        $id=$request->input('id');
        $date=$request->input('date');
        $time=$request->input('time');
        $actualiza=DB::update('UPDATE meetings
        SET
        planned_date = :planned_date ,
        planned_time = :planned_time ,
        updated_at = CURDATE()
        WHERE id=:id', ['planned_date'=>$date,'planned_time'=>$time,'id'=>$id ]);
        DB::delete('DELETE FROM recruiter_meetings WHERE meeting_id=:meeting_id', ['meeting_id'=>$id]);
        $recruiter=$request->input('selected');
        
        if($recruiter!=null){
            foreach ($recruiter as $key => $value) {            
                DB::insert('INSERT INTO recruiter_meetings(meeting_id,recruiter_id,update_at,state)
                VALUES(:meeting_id,:recruiter_id,now(),1) ', ['meeting_id'=>$id, 'recruiter_id'=>$value]);
            }
        }
        
        $meet = DB::select('select user_id, company_id, job_apply_id from meetings where id=:id', ['id'=>$id]);
        $user = DB::select('select first_name, last_name, email from users where id = :id', ['id'=>$meet[0]->user_id]);
        $company = DB::select('select name from companies where id = :id', ['id'=>$meet[0]->company_id]);
        $apply = DB::select('select job_id from job_apply where id = :id and state=1', ['id'=>$meet[0]->job_apply_id]);
        $slug = DB::select('select title from jobs where id = :id', ['id'=>$apply[0]->job_id]);

        $siteSetting = SiteSetting::findOrFail(1272);

        $data['name_user'] = $user[0]->first_name . ' ' . $user[0]->last_name;
        $data['companyName'] = $company[0]->name;
        $data['slugJob'] = $slug[0]->title;
        $data['dateMeeting'] = $date;
        $data['timeMeeting'] = $time;
        $data['usermail'] = $user[0]->email;
        $data['frommail'] = $siteSetting->mail_from_address;
        $data['fromname'] = $siteSetting->site_name;
        $data['siteSetting'] = $siteSetting;
        Mail::send(new ChangeMeetingMail($data));
        
    }

    public function deleteMeeting(Request $request)
    {
        $id=$request->input('id');
        $currentRecru=Auth::guard('recruiter')->user()->id;
        $meet = DB::select('select id, user_id, company_id, job_apply_id from meetings where id=:id', ['id'=>$id]);
        $user = DB::select('select first_name, last_name, email, id from users where id = :id', ['id'=>$meet[0]->user_id]);
        $company = DB::select('select name, email, id from companies where id = :id', ['id'=>$meet[0]->company_id]);
        $apply = DB::select('select job_id from job_apply where id = :id and state=1', ['id'=>$meet[0]->job_apply_id]);
        $slug = DB::select('select title from jobs where id = :id', ['id'=>$apply[0]->job_id]);
        $recruiters_id=DB::select('SELECT recruiter_id FROM recruiter_meetings WHERE meeting_id=:id AND recruiter_id!=:current;',
                                ["id"=>$id,"current"=>$currentRecru]);

        $job_apply_id=DB::delete('DELETE FROM meetings WHERE id=:id', ['id'=>$id]);         
        DB::delete('DELETE FROM recruiter_meetings WHERE meeting_id=:meeting_id', ['meeting_id'=>$id]);      

        $this->cancelNotifications($user,$company,$recruiters_id,$slug,$meet,$currentRecru);
    }

    private function cancelNotifications($user,$company,$recruiters_id,$slug,$meet,$currentRecru){
        $recruiter=[];
        foreach($recruiters_id as $value){
            array_push($recruiter, 
                    DB::select('select email, id from recruiters where id=:id', ["id"=>$value->recruiter_id])[0]->email
            );
        }
        
        //=========== Emails ==================
        $siteSetting = SiteSetting::findOrFail(1272);
        
        $data['name_user'] = $user[0]->first_name . ' ' . $user[0]->last_name;
        $data['companyName'] = $company[0]->name;
        $data['slugJob'] = $slug[0]->title;
        $data['usermail'] = $user[0]->email;
        $data['frommail'] = $siteSetting->mail_from_address;
        $data['fromname'] = $siteSetting->site_name;
        $data['siteSetting'] = $siteSetting;
        Mail::send(new CancelMeetingMail($data));

        $data['name_user'] = $user[0]->first_name . ' ' . $user[0]->last_name;
        $data['companyName'] = $company[0]->name;
        $data['slugJob'] = $slug[0]->title;
        $data['usermail'] = $company[0]->email;
        $data['frommail'] = $siteSetting->mail_from_address;
        $data['fromname'] = $siteSetting->site_name;
        $data['siteSetting'] = $siteSetting;
        Mail::send(new CancelMeetingMail($data));

        for($i=0; $i<count($recruiter); $i++){
            $data['name_user'] = $user[0]->first_name . ' ' . $user[0]->last_name;
            $data['companyName'] = $company[0]->name;
            $data['slugJob'] = $slug[0]->title;
            $data['usermail'] = $recruiter[$i];
            $data['frommail'] = $siteSetting->mail_from_address;
            $data['fromname'] = $siteSetting->site_name;
            $data['siteSetting'] = $siteSetting;
            Mail::send(new CancelMeetingMail($data));
        }
        
        //=========== Message ==================
        $from=Auth::guard('recruiter')->user()->recruiterType() ? 2 : 3 ;

        $dataMessage['user_id'] = $user[0]->id;
        $dataMessage['company_id'] = $company[0]->id;
        $dataMessage['type'] = 1;
        $dataMessage['message'] = "We are sorry to inform you that the meeting was canceled";
        $dataMessage['receivedfrom'] = $from;
        $dataMessage['meeting_id'] = $meet[0]->id;
        $meeting = new MeetingController();
        $meeting->sendNotification($dataMessage);

        $dataMessage['user_id'] = $currentRecru;
        $dataMessage['company_id'] = $company[0]->id;
        $dataMessage['type'] = 1;
        $dataMessage['message'] = "We are sorry to inform you that the meeting was canceled";
        $dataMessage['receivedfrom'] = $from;
        $dataMessage['meeting_id'] = $meet[0]->id;
        $meeting = new MeetingController();
        $meeting->sendNotification($dataMessage);

        if(count($recruiters_id)>0){
            foreach($recruiters_id as $value){
                $dataMessage['user_id'] = $currentRecru;
                $dataMessage['company_id'] = $company[0]->id;
                $dataMessage['type'] = 1;
                $dataMessage['message'] = "We are sorry to inform you that the meeting was canceled";
                $dataMessage['receivedfrom'] = $from;
                $dataMessage['meeting_id'] = $meet[0]->id;
                $meeting = new MeetingController();
                $meeting->sendNotification($dataMessage);
            }
        }
    }

    public function newComment(Request $request,$application_id,$from)
    {        
        $recruiter_id=Auth::guard('recruiter')->user()->id;
        $correcto=DB::insert('INSERT INTO comments_on_candidates(comment,apply_id,recruiter_id)
        VALUES(:comment,:apply_id,:recruiter_id )', ['comment'=> $request->input('comments'),'apply_id'=>$application_id,'recruiter_id'=>$recruiter_id]);
        if ($correcto) {
            flash(__('New comment saved'))->success();
        } else {
            flash(__('The comment was not saved'))->error();
        }
        return redirect()->route('recruiter.applicant.profile', ['application_id' => $application_id]);
    }

    public function showCommentCompany (Request $request)
    {
        $application_id=$request->input('application_id');
        // $array=DB::select('SELECT CC.id,CC.comment,CC.created_at,CC.recruiter_id,CC.company_id,R.name,R.lastname FROM comments_on_candidates CC 
        //                     join recruiters R on CC.recruiter_id=R.id
        // where apply_id=:application_id', ['application_id'=>$application_id]);
        $array=DB::select('SELECT id,comment,created_at,recruiter_id,company_id
                            FROM comments_on_candidates
                            where apply_id=:application_id', ['application_id'=>$application_id]
                    
        );
        $recruiter=Auth::guard('recruiter')->user();
        $isMaster=$recruiter->recruiterType();
        $recruiterId=$recruiter->id;
        $company_name=DB::select('SELECT name from companies where id=:id_Company',['id_Company'=>$recruiter->id_company])[0];
        //dd($array);
        $html="";
        if ($array) {
            foreach ($array as $comment => $value) {
                $html=$html."<div style='margin:1% ;
                -webkit-box-shadow: 1px 1px 1px #e5e5e5;
                box-shadow: 1px 1px 1px #e5e5e5;
                border-radius: 4px;
                padding: 5px 1px; '>
                <p style='margin:1%;' name='comments'>$value->comment</p>
                <p style='font-size: smaller'>$value->created_at</p>";

                if($value->recruiter_id){
                    $commentFromRecruiter=DB::select('SELECT name,lastname from recruiters where id=:recruiter_id',['recruiter_id'=>$value->recruiter_id])[0];
                    $html=$html."<p style='font-size: smaller'>Comment from: $commentFromRecruiter->name $commentFromRecruiter->lastname</p>";
                }elseif($value->company_id!=null){
                    $html=$html."<p style='font-size: smaller'>Comment from: $company_name->name</p>";
                }
                //restriccion para borrar mensajes:
                if($isMaster || $value->recruiter_id==$recruiterId){
                    $html=$html."<button onclick='deleteComment($value->id)' class='btn btn-danger'>Delete Commet</button>";
                }
                
                $html=$html."</div>";    
            }
        }else{
            $html="";
        }
        echo $html;
    }
    
    public function deleteCommentCompany (Request $request)
    {
        $application_id=$request->input('application_id');
        $id=$request->input('id');
        $delet=DB::delete('DELETE FROM comments_on_candidates
        WHERE id=:id', ['id'=>$id]);
        $array=DB::select('SELECT CC.id,CC.comment,CC.created_at,CC.recruiter_id,R.name,R.lastname FROM comments_on_candidates CC 
                            join recruiters R on CC.recruiter_id=R.id
        where apply_id=:application_id', ['application_id'=>$application_id]);
        $html="";
        if ($array) {
            foreach ($array as $comment => $value) {
                $html=$html."<div style='margin:1% ;
                -webkit-box-shadow: 1px 1px 1px #e5e5e5;
                box-shadow: 1px 1px 1px #e5e5e5;
                border-radius: 4px;
                padding: 5px 1px; '>
                <p style='margin:1%;' name='comments' >$value->comment</p>
                <p style='font-size: smaller'>$value->created_at</p>
                <p style='font-size: smaller'>Comment from: $value->name $value->lastname</p> <button onclick='deleteComment($value->id)' class='btn btn-danger'>Delete Commet</button>
                </div>";
            }
        }else{
            $html="";
        }

        echo $html;
    }

    public function showModalJobs($user_id){
        $jobs=Auth::guard('recruiter')->user()->jobs()->paginate(10);
        $user=$user_id;

        return view('user.modal_recommend_candidate')
            ->with("jobs",$jobs)
            ->with("userId",$user)
        ;
    }

    public function recommendCandidate(Request $request){
        $recruiter=Auth::guard('recruiter')->user();
        $userId=$request->input('user');
        $jobId=$request->input('job');
        $recruiterMsg=$request->input('msg');
        $recruitersMaster_id=DB::select('SELECT id FROM recruiters WHERE id_company=:company AND is_master=1',
                                        ['company'=>$recruiter->id_company]
                                    );
        
        $userName=DB::select('SELECT name FROM users WHERE id=:id',['id'=>$userId]);
        $jobName=DB::select('SELECT title FROM jobs WHERE id=:id',['id'=>$jobId]);
        $message="El reclutador '$recruiter->name $recruiter->lastname' ha recomendado al usuario '" . $userName[0]->name ."' para la plaza '" . $jobName[0]->title ."' y ha opinado: '$recruiterMsg'";

        if(!$recruiter->recruiterType()){
            $dataMessage['user_id'] = $recruiter->id;
            $dataMessage['company_id'] = $recruiter->id_company;
            $dataMessage['type'] = 1;
            $dataMessage['message'] = $message;
            $dataMessage['receivedfrom'] = 3;
            $dataMessage['meeting_id'] = null;
            $meeting = new MeetingController();
            $meeting->sendNotification($dataMessage);
            
            if(count($recruitersMaster_id)>0){
                foreach($recruitersMaster_id as $value){
                    $dataMessage['user_id'] = $value->id;
                    $dataMessage['company_id'] = $recruiter->id_company;
                    $dataMessage['type'] = 1;
                    $dataMessage['message'] = $message;
                    $dataMessage['receivedfrom'] = 3;
                    $dataMessage['meeting_id'] = null;
                    $meeting = new MeetingController();
                    $meeting->sendNotification($dataMessage);
                }  
            }  
        } 
    }
    
    public function changeMeeting(Request $request){
        $idMeeting=$request->input("id");
        $msg=$request->input("msg");
        $meet=DB::select('SELECT user_id,company_id FROM meetings WHERE id=:id',['id'=>$idMeeting]);
        $recruitersMaster_id=DB::select('SELECT recruiter_id 
                                    FROM recruiter_meetings rm
                                    INNER JOIN recruiters r
                                        ON rm.recruiter_id=r.id 
                                    WHERE rm.meeting_id=:id 
                                        AND r.is_master=1;',
                                    ['id'=>$idMeeting]);

        $dataMessage['user_id'] = $meet[0]->user_id;
        $dataMessage['company_id'] = $meet[0]->company_id;
        $dataMessage['type'] = 1;
        $dataMessage['message'] = $msg;
        $dataMessage['receivedfrom'] = 3;
        $dataMessage['meeting_id'] = $idMeeting;
        $meeting = new MeetingController();
        $meeting->sendNotification($dataMessage);

        if(count($recruitersMaster_id)>0){
            foreach($recruitersMaster_id as $value){
                $dataMessage['user_id'] = $value->recruiter_id;
                $dataMessage['company_id'] = $meet[0]->company_id;
                $dataMessage['type'] = 1;
                $dataMessage['message'] = $msg;
                $dataMessage['receivedfrom'] = 3;
                $dataMessage['meeting_id'] = $idMeeting;
                $meeting = new MeetingController();
                $meeting->sendNotification($dataMessage);
            }            
        }               
    } 

    public function showFavourites(Request $request) {
        $companyId = Auth::guard('recruiter')->user()->id_company;
        $users = DB::select('select F.id, F.user_id, U.first_name, U.last_name, U.image, CASE WHEN F.job_id = null THEN "General" WHEN F.job_id <> null THEN (SELECT title FROM jobs WHERE id = F.job_id) END as job
        FROM favourite_applicants F
        INNER JOIN users U ON F.user_id = U.id where F.company_id = :id_company;', ['id_company'=>$companyId]);
        return view('company.company_favourites')->with('users', $users);
    }
    public function deleteFromFavourites($user_id)
    {
       $company_id=Auth::guard('recruiter')->user()->id_company;
       FavouriteApplicant::where('user_id', $user_id)
                ->where('company_id', '=', $company_id)
                ->delete();

        flash(__('Job seeker has been removed from favorites list'))->success();
        return \Redirect::route('recruiter.company.favourites');
    }
}