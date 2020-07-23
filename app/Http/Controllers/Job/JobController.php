<?php

namespace App\Http\Controllers\Job;

use Auth;
use Input;
use Redirect;
use Mail;
use Carbon\Carbon;
use App\Job;
use App\JobApply;
use App\FavouriteJob;
use App\Company;
use App\JobSkill;
use App\JobSkillManager;
use App\Country;
use App\CountryDetail;
use App\State;
use App\City;
use App\CareerLevel;
use App\FunctionalArea;
use App\JobType;
use App\JobShift;
use App\Gender;
use App\JobExperience;
use App\DegreeLevel;
use App\ProfileCv;
use App\SiteSetting;
use App\Helpers\MiscHelper;
use App\Helpers\DataArrayHelper;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use DataTables;
use App\Http\Requests\JobFormRequest;
use App\Http\Requests\Front\ApplyJobFormRequest;
use App\Http\Controllers\Controller;
use App\Http\Controllers\IpClient;
use App\Traits\FetchJobs;
use App\Events\JobApplied;
use App\Mail\InvitationMail;
use App\Mail\CanceledJobApply;

class JobController extends Controller
{

    //use Skills;
    use FetchJobs;

    private $functionalAreas = '';
    private $countries = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['jobsBySearch', 'jobDetail','inviteCandidate','showInviteCandidate']]);

        $this->functionalAreas = DataArrayHelper::langFunctionalAreasArray();
        $this->countries = DataArrayHelper::langCountriesArray();
    }

    public function jobsBySearch(Request $request)
    {
        $search = $request->query('search', '');
        $job_titles = $request->query('job_title', array());
        $company_ids = $request->query('company_id', array());
        $industry_ids = $request->query('industry_id', array());
        $job_skill_ids = $request->query('job_skill_id', array());
        $functional_area_ids = $request->query('functional_area_id', array());
        $country_ids = $request->query('country_id', array());
        $country_id = null;
        if(!$country_ids){
            $country_id = IpClient::getIpClient();
            $country_ids = collect($country_id)->toArray();
        }         
        // $state_ids = $request->query('state_id', array());
        $state_ids = array();
        $city_ids = $request->query('city_id', array());
        $is_freelance = $request->query('is_freelance', array());
        $career_level_ids = $request->query('career_level_id', array());
        $job_type_ids = $request->query('job_type_id', array());
        $job_shift_ids = $request->query('job_shift_id', array());
        $gender_ids = $request->query('gender_id', array());
        $degree_level_ids = $request->query('degree_level_id', array());
        $job_experience_ids = $request->query('job_experience_id', array());
        $salary_from = $request->query('salary_from', '');
        $salary_to = $request->query('salary_to', '');
        $salary_currency = $request->query('salary_currency', '');
        $is_featured = $request->query('is_featured', 2);
        $order_by = $request->query('order_by', 'id');
        $confidential = $request->query('confidential', array());
        $limit = 10;
        $jobs = $this->fetchJobs($search, $job_titles, $company_ids, $industry_ids, $job_skill_ids,
         $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, $career_level_ids,
          $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, $job_experience_ids, $salary_from,
           $salary_to, $salary_currency, $is_featured, $order_by, $limit);
        
        /*         * ************************************************** */

        $jobTitlesArray = $this->fetchIdsArray($search, $job_titles, $company_ids, $industry_ids,
         $job_skill_ids, $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance,
          $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, $job_experience_ids,
           $salary_from, $salary_to, $salary_currency, $is_featured, 'jobs.title');

        /*         * ************************************************* */

        $jobIdsArray = $this->fetchIdsArray($search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, 
        $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, $career_level_ids, $job_type_ids, 
        $job_shift_ids, $gender_ids, $degree_level_ids, $job_experience_ids, $salary_from, $salary_to, 
        $salary_currency, $is_featured, 'jobs.id');

        /*         * ************************************************** */
        
        $skillIdsArray = $this->fetchSkillIdsArray($jobIdsArray);

        /*         * ************************************************** */

        $countryIdsArray = $this->fetchIdsArray($search, $job_titles, $company_ids, $industry_ids,
         $job_skill_ids, $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance,
          $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, $job_experience_ids,
           $salary_from, $salary_to, $salary_currency, $is_featured, 'jobs.country_id');

        /*         * ************************************************** */

        $stateIdsArray = $this->fetchIdsArray($search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, 
        $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, $career_level_ids, $job_type_ids,
         $job_shift_ids, $gender_ids, $degree_level_ids, $job_experience_ids, $salary_from, $salary_to, $salary_currency,
          $is_featured, 'jobs.state_id');

        /*         * ************************************************** */

        $cityIdsArray = $this->fetchIdsArray($search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, 
        $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, $career_level_ids, $job_type_ids,
         $job_shift_ids, $gender_ids, $degree_level_ids, $job_experience_ids, $salary_from, $salary_to, $salary_currency,
          $is_featured, 'jobs.city_id');

        /*         * ************************************************** */

        $companyIdsArray = $this->fetchIdsArray($search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, 
        $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, $career_level_ids, $job_type_ids, 
        $job_shift_ids, $gender_ids, $degree_level_ids, $job_experience_ids, $salary_from, $salary_to, $salary_currency, 
        $is_featured, 'jobs.company_id');

        /*         * ************************************************** */

        $industryIdsArray = $this->fetchIndustryIdsArray($companyIdsArray);

        /*         * ************************************************** */


        /*         * ************************************************** */

        $functionalAreaIdsArray = $this->fetchIdsArray($search, $job_titles, $company_ids, $industry_ids, $job_skill_ids,
         $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, $career_level_ids, $job_type_ids,
          $job_shift_ids, $gender_ids, $degree_level_ids, $job_experience_ids, $salary_from, $salary_to, $salary_currency,
           $is_featured, 'jobs.functional_area_id');

        /*         * ************************************************** */

        $careerLevelIdsArray = $this->fetchIdsArray($search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, 
        $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, $career_level_ids, $job_type_ids, 
        $job_shift_ids, $gender_ids, $degree_level_ids, $job_experience_ids, $salary_from, $salary_to, $salary_currency, 
        $is_featured, 'jobs.career_level_id');

        /*         * ************************************************** */

        $jobTypeIdsArray = $this->fetchIdsArray($search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, 
        $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, $career_level_ids, $job_type_ids, 
        $job_shift_ids, $gender_ids, $degree_level_ids, $job_experience_ids, $salary_from, $salary_to, $salary_currency, 
        $is_featured, 'jobs.job_type_id');

        /*         * ************************************************** */

        $jobShiftIdsArray = $this->fetchIdsArray($search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, 
        $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, $career_level_ids, $job_type_ids, 
        $job_shift_ids, $gender_ids, $degree_level_ids, $job_experience_ids, $salary_from, $salary_to, $salary_currency, 
        $is_featured, 'jobs.job_shift_id');

        /*         * ************************************************** */

        $genderIdsArray = $this->fetchIdsArray($search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, 
        $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, $career_level_ids, $job_type_ids, 
        $job_shift_ids, $gender_ids, $degree_level_ids, $job_experience_ids, $salary_from, $salary_to, $salary_currency, 
        $is_featured, 'jobs.gender_id');

        /*         * ************************************************** */

        $degreeLevelIdsArray = $this->fetchIdsArray($search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, 
        $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, $career_level_ids, $job_type_ids, 
        $job_shift_ids, $gender_ids, $degree_level_ids, $job_experience_ids, $salary_from, $salary_to, $salary_currency, 
        $is_featured, 'jobs.degree_level_id');

        /*         * ************************************************** */

        $jobExperienceIdsArray = $this->fetchIdsArray($search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, 
        $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, $career_level_ids, $job_type_ids, 
        $job_shift_ids, $gender_ids, $degree_level_ids, $job_experience_ids, $salary_from, $salary_to, $salary_currency, 
        $is_featured, 'jobs.job_experience_id');

        /*         * ************************************************** */

        $seoArray = $this->getSEO($functional_area_ids, $country_ids, $state_ids, $city_ids, $career_level_ids, $job_type_ids, 
        $job_shift_ids, $gender_ids, $degree_level_ids, $job_experience_ids);

        /*         * ************************************************** */

        $currencies = DataArrayHelper::currenciesArray();

        /*         * ************************************************** */

        $seo = (object) array(
                    'seo_title' => $seoArray['description'],
                    'seo_description' => $seoArray['description'],
                    'seo_keywords' => $seoArray['keywords'],
                    'seo_other' => ''
        );

        // $jobs->orderBy('created_at','desc')->orderBy('title', 'desc');

        return view('job.list')
                        ->with('functionalAreas', $this->functionalAreas)
                        ->with('countries', $this->countries)
                        ->with('country_id', $country_id)
                        ->with('currencies', array_unique($currencies))
                        ->with('jobs', $jobs)
                        ->with('jobTitlesArray', $jobTitlesArray)
                        ->with('skillIdsArray', $skillIdsArray)
                        ->with('countryIdsArray', $countryIdsArray)
                        ->with('stateIdsArray', $stateIdsArray)
                        ->with('cityIdsArray', $cityIdsArray)
                        ->with('companyIdsArray', $companyIdsArray)
                        ->with('industryIdsArray', $industryIdsArray)
                        ->with('functionalAreaIdsArray', $functionalAreaIdsArray)
                        ->with('careerLevelIdsArray', $careerLevelIdsArray)
                        ->with('jobTypeIdsArray', $jobTypeIdsArray)
                        ->with('jobShiftIdsArray', $jobShiftIdsArray)
                        ->with('genderIdsArray', $genderIdsArray)
                        ->with('degreeLevelIdsArray', $degreeLevelIdsArray)
                        ->with('jobExperienceIdsArray', $jobExperienceIdsArray)
                        ->with('seo', $seo);
    }

    public function compruebaAplicante($job){        
        $user = Auth::user();        
        $perfil=DB::select('select id, first_name, last_name, name, email, date_of_birth, gender_id, marital_status_id, nationality_id, national_id_card_number, country_id, state_id, city_id, industry_id, street_address, is_active, verified, image from users where id=:id_user',['id_user'=>$user->id]);
        $habilidades_job=DB::select('select job_skill_id from manage_job_skills where job_id=:id_job ',['id_job'=>$job->id]);
        $habilidades_user=DB::select('select job_skill_id from profile_skills where user_id=:id_user', ['id_user'=>$user->id]) ;
        $habilidades_job=collect($habilidades_job)->toArray();
        $habilidades_user=collect($habilidades_user)->toArray();
        $msg="";
        foreach($perfil as $valor){
            if ($valor==null  ){      
                $msg='Complete your profile';
                $cumplePerfil=false;                
            }
            else {
                $cumplePerfil=true;
            }
        }        
        $aplicar= true;
        foreach($habilidades_job as $valorJOb){
            if (in_array($valorJOb,$habilidades_user) ) {
                
            }
            else {              
                $msg="Your profile does not meet to apply to this job";
                $aplicar= false;
            }

        }
        // if($msg!=""){
        //     flash(__($msg))->error();
        // }
        return $aplicar;
    }

    public function jobDetail(Request $request, $job_slug)
    {
        
        $job = Job::where('slug', 'like', $job_slug)->firstOrFail();  
        /**creacion metas */
        $auxId = DB::select('select company_id from jobs where id = :id', ['id'=>$job->id]);
        $auxId = $auxId[0]->company_id;
        $jobcompany = Company::where('id', $auxId)->first();

        $ogtitle = $job->title .' - ' .$jobcompany->name;

        $meta = '<!-- Open Graph para Facebook -->
        <meta property="og:title" content="'.$ogtitle. '" />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="'.htmlspecialchars(\Request::fullUrl()).'" />
        <meta property="og:description" content="'. $job->description .'" />
        <meta property="og:site_name" content="Cloik" />
        <meta property="og:image" content="https://www.cloik.com/company_logos/'.$jobcompany->logo.'" />
        <meta property="og:image:width" content="200" />
        <meta property="og:image:height" content="200" />
        <meta property="fb:app_id" content="228856281650599" />
        
        <!-- Twitter Card -->
        <meta name="twitter:card" content="Cloik">
        <meta name="twitter:site" content="cloik.com">
        <meta name="twitter:title" content="'.$ogtitle. '">
        <meta name="twitter:description" content="'. $job->description .'">
        <meta name="twitter:image" content="https://www.cloik.com/company_logos/'.$jobcompany->logo.'" />
        <meta name="twitter:creator" content="@cloik">
        
        <!-- Schema.org para Google+ -->
        <meta itemprop="name" content="'.$ogtitle. '">
        <meta itemprop="description" content="'. $job->description .'">
        <meta itemprop="image" content="https://www.cloik.com/company_logos/'.$jobcompany->logo.'" />';

        if (!isset($_SESSION)) {
            session_start();
            $_SESSION["metaOG"] = $meta;
        }

        /*Comprobacion del inicio de secion */
        $company = Auth::guard('company')->check();
        $user = Auth::user();
        $recruiter=Auth::guard('recruiter')->check();

        if ((bool)$user === false && (bool)$company === false && (bool)$recruiter===false) {
            if (!isset($_SESSION)) {
                session_start();
            }
            $_SESSION["job_slug"] = $job_slug;
            flash(__('You must login first'))->error();
            // return redirect('/login');
        }
        else {
            if (!isset($_SESSION)) {
                session_start();
            }
            if(isset($_SESSION["job_slug"])){
                unset($_SESSION["job_slug"]);
                session_destroy();
            }
        }

        $invite=false;      
        if ($company) {
            $company_id = Auth::guard('company')->user()->id;
            if ($job->company_id===$company_id) {
                $invite=true;
            } else {
                $invite=false;
            }
        }elseif(Auth::guard('recruiter')->check()){
            $company_id = Auth::guard('recruiter')->user()->id_company;
            if ($job->company_id===$company_id) {
                $invite=true;
            } else {
                $invite=false;
            }
        }
        if($user){
            DB::statement('CALL sp_job_view(:job_id, :user_id)',['job_id'=>$job->id,'user_id'=>$user->id]);
        }


        /*         * ************************************************** */
        $search = '';
        $job_titles = array();
        $company_ids = array();
        $industry_ids = array();
        $job_skill_ids = (array) $job->getJobSkillsArray();
        $functional_area_ids = (array) $job->getFunctionalArea('functional_area_id');
        $country_ids = (array) $job->getCountry('country_id');
        $state_ids = (array) $job->getState('state_id');
        $city_ids = (array) $job->getCity('city_id');
        $is_freelance = $job->is_freelance;
        $career_level_ids = (array) $job->getCareerLevel('career_level_id');
        $job_type_ids = (array) $job->getJobType('job_type_id');
        $job_shift_ids = (array) $job->getJobShift('job_shift_id');
        $gender_ids = (array) $job->getGender('gender_id');
        $degree_level_ids = (array) $job->getDegreeLevel('degree_level_id');
        $job_experience_ids = (array) $job->getJobExperience('job_experience_id');
        $salary_from = 0;
        $salary_to = 0;
        $salary_currency = '';
        $is_featured = 2;
        $order_by = 'id';
        $limit = 5;
        $confidential=DB::select('select confidential from jobs where id=:id', [$job->id]);

        $relatedJobs = $this->fetchJobs($search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, $job_experience_ids, $salary_from, $salary_to, $salary_currency, $is_featured, $order_by, $limit);
        /*         * ***************************************** */
        
        $seoArray = $this->getSEO((array) $job->functional_area_id, (array) $job->country_id, (array) $job->state_id, (array) $job->city_id, (array) $job->career_level_id, (array) $job->job_type_id, (array) $job->job_shift_id, (array) $job->gender_id, (array) $job->degree_level_id, (array) $job->job_experience_id,$confidential[0]);

        /*         * ************************************************** */
        $seo = (object) array(
                    'seo_title' => $seoArray['description'],
                    'seo_description' => $seoArray['description'],
                    'seo_keywords' => $seoArray['keywords'],
                    'seo_other' => ''
        );        

        if ((bool)$user === true) {
            $aplica = $this->compruebaAplicante($job);
        }
        else {
            $aplica = false;
        }

        if((bool)$user === true){
            $haveVideos=$this->getVideos();
            $haveMainVideo=$this->getMainVideo();
        }else{
            $haveVideos=false;
            $haveMainVideo=false;
        }        

        return view('job.detail')
                        ->with('job', $job)
                        ->with('relatedJobs', $relatedJobs)
                        ->with('seo', $seo)
                        ->with('invite',$invite)
                        ->with('metaOG', $meta)
                        ->with('aplica', $aplica)
                        ->with('haveVideos',$haveVideos)
                        ->with('haveMainVideo',$haveMainVideo)
        ;

    }

    /*     * ************************************************** */

    public function addToFavouriteJob(Request $request, $job_slug)
    {
        $data['job_slug'] = $job_slug;
        $data['user_id'] = Auth::user()->id;
        $data_save = FavouriteJob::create($data);
        flash(__('Job has been added in favorites list'))->success();
        return \Redirect::route('job.detail', $job_slug);
    }

    public function removeFromFavouriteJob(Request $request, $job_slug)
    {
        $user_id = Auth::user()->id;
        FavouriteJob::where('job_slug', 'like', $job_slug)->where('user_id', $user_id)->delete();

        flash(__('Job has been removed from favorites list'))->success();
        return \Redirect::route('job.detail', $job_slug);
    }

    // public function applyJob(Request $request, $job_s, $video)
    public function applyJob($job_s, $video)
    {
        
        $job_slug=htmlspecialchars_decode($job_s);
        $user = Auth::user();
        $job = Job::where('slug', 'like', $job_slug)->first();
        $user_id = Auth::user()->id;
        $idVideo=$video;
        // DB::update('update video_apply_id set :idVideo where user_id = :user_id', ['idVideo'=>$idVideo,'user_id'=>$user_id]);
        if ((bool)$user->is_active === false) {
            flash(__('Your account is inactive contact site admin to activate it'))->error();
            return \Redirect::route('job.detail', $job_slug);
            exit;
        }
        
        if ((bool) config('jobseeker.is_jobseeker_package_active')) {
            if (
                    ($user->jobs_quota <= $user->availed_jobs_quota) ||
                    ($user->package_end_date->lt(Carbon::now()))
            ) {
                flash(__('Please subscribe to package first'))->error();
                return \Redirect::route('home');
                exit;
            }
        }
        if ($user->isAppliedOnJob($job->id)) {
            flash(__('You have already applied for this job'))->success();
            return \Redirect::route('job.detail', $job_slug);
            exit;
        }

        $myCvs = ProfileCv::where('user_id', '=', $user->id)->pluck('title', 'id')->toArray();

        return view('job.apply_job_form')
                        ->with('job_slug', $job_slug)
                        ->with('job', $job)
                        ->with('myCvs', $myCvs)
                        ->with('videoApply', $idVideo);
    }

    public function postApplyJob(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $job_slug=$request->input('job_slug');
        $job = Job::where('slug', 'like', $job_slug)->first();
        $cv_id = $request->input('cv_id');
        $video=$request->input('videoApply');
        $previousApplied=DB::select('SELECT id FROM job_apply where user_id=:user_id and job_id=:job_id and state=1 ORDER BY id desc',['user_id'=>$user_id,'job_id'=>$job->id]);
        
        if(count($previousApplied)!=0){
            $update=DB::update('UPDATE job_apply set state=1 where id=:id and user_id=:user_id and job_id=:job_id',['user_id'=>$user_id,'job_id'=>$job->id,'id'=>$previousApplied[0]->id]);
        }else{
            $inserta=DB::insert('INSERT INTO job_apply(user_id,job_id,cv_id,created_at,updated_at,video_apply_id)
            VALUES(:user_id,:job_id,:cv_id,CURDATE(),CURDATE(),:video_apply_id)', ['user_id'=>$user_id, 'job_id'=>$job->id,'cv_id'=>$cv_id,'video_apply_id'=>$video  ]);
        }
       
        
        if ((bool) config('jobseeker.is_jobseeker_package_active')) {
            $user->availed_jobs_quota = $user->availed_jobs_quota + 1;
            $user->update();
        }
        
        if (isset($inserta) || isset($update)) {
            /*         * ******************************* */
        // event(new JobApplied($job, $jobApply));

        flash(__('You have successfully applied for this job'))->success();
        return \Redirect::route('job.detail', $job_slug);
        } else {
            flash(__('Error'))->error();
            return \Redirect::route('job.detail', $job_slug);

        }
        
    }

    public function myJobApplications(Request $request)
    {
        $user=Auth::user();
        // $myAppliedJobIds = Auth::user()->getAppliedJobIdsArray();
        $myAppliedJobIds=array();
        $resultado = DB::select('select job_id from job_apply where user_id=:user_id and state=1 ', ['user_id'=>$user->id])  ;
        foreach ($resultado as $key => $value) {
            array_push($myAppliedJobIds,$value->job_id);
        }
        $jobs = Job::whereIn('id', $myAppliedJobIds)->paginate(10);
        return view('job.my_applied_jobs')
                        ->with('jobs', $jobs);
    }

    public function deleteJobApply(Request $request, $job_id) {
            
        $job=Job::where('id', 'like', $job_id)->first();
        $job_apply = DB::select('select id  from job_apply where job_id = :job_id and user_id = :user_id and state=1', ['job_id'=>$job_id, 'user_id'=>Auth::user()->id]);
        if(count($job_apply)>0){
            $update = DB::update('update job_apply set state=0 where id = :id', ['id'=>$job_apply[0]->id]);        
            $deleteMeeting = DB::delete('delete from meetings where user_id= :user_id and job_apply_id = :job_apply_id', ['user_id' =>Auth::user()->id, 'job_apply_id'=>$job_apply[0]->id]);
        }        
       
        if(isset($deleteMeeting)){            
            if($deleteMeeting){
                $alertMessage = DB:: insert('INSERT INTO messages_center (user_id,company_id,type,message, create_at,state,receivedfrom)
                VALUES(:user_id,:company_id,:type,:message,now(),0,0);', ['user_id'=>Auth::user()->id, 'company_id'=>$job->company_id,'type'=>1,'message'=>"the meeting was cancelled"]);

                $siteSetting = SiteSetting::findOrFail(1272);
                $user = DB::select('select first_name, last_name from users where id = :id', ['id'=>Auth::user()->id]);
                $company = DB::select('select name, email from companies where id = :id', ['id'=>$job->company_id]);
                $slug = DB::select('select title from jobs where id = :id', ['id'=>$job_id]);

                $data['name_user'] = $user[0]->first_name . ' ' . $user[0]->last_name;
                $data['companyName'] = $company[0]->name;
                $data['slugJob'] = $slug[0]->title;
                $data['usermail'] = $company[0]->email;
                $data['frommail'] = $siteSetting->mail_from_address;
                $data['fromname'] = $siteSetting->site_name;
                $data['siteSetting'] = $siteSetting;

                Mail::send(new canceledJobApply($data));
            }            
        }

        return \Redirect::route('my.job.applications');
    }

    public function myFavouriteJobs(Request $request)
    {
        $myFavouriteJobSlugs = Auth::user()->getFavouriteJobSlugsArray();
        $jobs = Job::whereIn('slug', $myFavouriteJobSlugs)->paginate(10);
        return view('job.my_favourite_jobs')
                        ->with('jobs', $jobs);
    }
    public function showInviteCandidate($id_job)
    {  
        $cadidatos=DB::select('select distinct U.id, U.name, U.image,U.email ,CO.country,CI.city from users U inner join profile_skills PS on U.id=PS.user_id inner join
        (select MJS.job_skill_id as job_skill_id, J.country_id as country_id from companies C inner join jobs J on J.company_id=C.id inner join manage_job_skills MJS on MJS.job_id=J.id where J.id=:id_job) as B
        on PS.job_skill_id=B.job_skill_id inner join countries CO on CO.country_id=U.country_id join cities CI on CI.city_id=U.city_id where U.country_id=B.country_id and CO.lang=:lang;',['id_job'=>$id_job,'lang'=>\App::getLocale()]);
        $invitados=DB::select('select user_id from users_invite where job_id =:id_job', ['id_job'=>$id_job]);
        $invitados=collect($invitados)->pluck('user_id')->toArray();
        return view('job.modal_invite_candidate')
                ->with('cadidatos',$cadidatos)
                ->with('id_job',$id_job)
                ->with('invitados',$invitados);
    }
    
    public function inviteCandidate($id_job,$user_id)
    {
        $job=Job::where('id', 'like', $id_job)->first();
        DB::insert('INSERT INTO messages_center (user_id,company_id,type,message, create_at,state,receivedfrom)
                    VALUES(:user_id,:company_id,:type,:message,CURDATE(),0,1);', ['user_id'=>$user_id, 'company_id'=>$job->company_id,'type'=>2,'message'=>"https://www.cloik.com/job/$job->slug"]);
        DB::insert('INSERT INTO users_invite(job_id,user_id) VALUES(:id_job,:user_id)', ['user_id'=>$user_id,'id_job'=>$id_job]);
        flash(__('Guest candidate'))->success();

        $siteSetting = SiteSetting::findOrFail(1272);
        $user = DB::select('select first_name, last_name, email from users where id = :id', ['id'=>$user_id]);
        $company = DB::select('select name from companies where id = :id', ['id'=>$job->company_id]);
        $slug = DB::select('select title from jobs where id = :id', ['id'=>$id_job]);

        $data['name_user'] = $user[0]->first_name . ' ' . $user[0]->last_name;
        $data['companyName'] = $company[0]->name;
        $data['slugJob'] = $slug[0]->title;
        $data['usermail'] = $user[0]->email;
        $data['frommail'] = $siteSetting->mail_from_address;
        $data['fromname'] = $siteSetting->site_name;
        $data['siteSetting'] = $siteSetting;
        $data['urlJob'] = "https://www.cloik.com/job/$job->slug";
        Mail::send(new InvitationMail($data));

        
        return \Redirect::route('job.detail', $job->slug);
    }

    public function getMainVideo() {
        $mainVideo = DB::select('select id from video_apply where id_user = :id_user and is_active = 1 and is_main=1', ['id_user'=>Auth::user()->id]);
        //$mainVideo = collect($videos)->where('is_main', '=', 1)->toArray();
        if(count($mainVideo)>0){
            return true;
        }else{
            return false;
        }
        
    }

    public function getVideos() {
        $videos = DB::select('select id from video_apply where id_user = :id_user and is_active = 1', ['id_user'=>Auth::user()->id]);
        //$videos = collect($videos)->where('is_main', '=', 0)->toArray();
        if(count($videos)>0){
            return true;
        }else{
            return false;
        }
    }
}
