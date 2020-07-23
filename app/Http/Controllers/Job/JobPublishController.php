<?php

namespace App\Http\Controllers\Job;

use Auth;
use DB;
use App\Job;
use Input;
use Redirect;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use App\Traits\JobTrait;
// use App\Traits\Skills;

class JobPublishController extends Controller
{

    use JobTrait;
    //use Skills;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('recruiter')->except(['storeFrontJob','updateFrontJob','updateVideo','deleteJob','updateFullTextSearch','assignJobValues','createJob','storeJob','editJob','updateJob','createFrontJob','editFrontJob','countNumJobs','scopeNotExpire','isJobExpired']); 
        $this->middleware('company')->except(['storeFrontJob','updateFrontJob','updateVideo','deleteJob','updateFullTextSearch','assignJobValues','createJob','storeJob','editJob','updateJob','createFrontJob','editFrontJob','countNumJobs','scopeNotExpire','isJobExpired']); 
    }
    // public function storeFrontJob(Request $request){
    //     if (is_null($request->input('videoJobURL')) || empty($request->input('videoJobURL'))) {
    //         $video= "";
    //     }
    //     else  {
    //         $video = $request->input('videoJobURL');
    //     }
    //     $title=$request->input('title');
    //     $slug=str_replace(" ","-",$title);
    //     $description=$request->input('description');
    //     $country_id=$request->input('country_id');
    //     $state_id=$request->input('state_id');
    //     $city_id=$request->input('city_id');
    //     $salary_from=$request->input('salary_from');
    //     $salary_to=$request->input('salary_to');
    //     $salary_currency=$request->input('salary_currency');
    //     $salary_period_id=$request->input('salary_period_id');
    //     $hide_salary=$request->input('hide_salary');
    //     $career_level_id=$request->input('career_level_id');
    //     $functional_area_id=$request->input('functional_area_id');
    //     $job_type_id=$request->input('job_type_id');
    //     $job_shift_id=$request->input('job_shift_id');
    //     $num_of_positions=$request->input('num_of_positions');
    //     $gender_id=$request->input('gender_id');
    //     $expiry_date=$request->input('expiry_date');
    //     $degree_level_id=$request->input('degree_level_id');
    //     $job_experience_id=$request->input('job_experience_id');
    //     $is_freelance=$request->input('is_freelance');
    //     $recomendations=$request->input('recomendations');
    //     $company_id=Auth::guard('company')->user()->id;
    //     $confidential=$request->input('confidential');
    //     $is_active=1;
    //     $is_featured=1;
        
    //     $consulta = DB::insert('insert into jobs (company_id,title,description,country_id,state_id,city_id,is_freelance,career_level_id,salary_from,salary_to,hide_salary,salary_currency,salary_period_id,functional_area_id,job_type_id,job_shift_id,num_of_positions,gender_id,expiry_date,degree_level_id,job_experience_id,is_active,is_featured,created_at,search,slug,video_recommendations, videoJobURL,confidential)
    //      values (:company_id,:title,:description,:country_id,:state_id,:city_id,:is_freelance,:career_level_id,:salary_from,:salary_to,:hide_salary,:salary_currency,:salary_period_id,:functional_area_id,:job_type_id,:job_shift_id,:num_of_positions,:gender_id,:expiry_date,:degree_level_id,:job_experience_id,:is_active,:is_featured,CURDATE(),:search,:slug,:video_recommendations, :video, :confidential)',
    //       ['company_id'=>$company_id,'title'=>$title,'description'=>$description,'country_id'=>$country_id,'state_id'=>$state_id,'city_id'=>$city_id,'is_freelance'=>$is_freelance,'career_level_id'=>$career_level_id,'salary_from'=>$salary_from,'salary_to'=>$salary_from,'hide_salary'=>$hide_salary,'salary_currency'=>$salary_currency,'salary_period_id'=>$salary_period_id,'functional_area_id'=>$functional_area_id,'job_type_id'=>$job_type_id,'job_shift_id'=>$job_shift_id,'num_of_positions'=>$num_of_positions,'gender_id'=>$gender_id,'expiry_date'=>$expiry_date,'degree_level_id'=>$degree_level_id,'job_experience_id'=>$job_experience_id,'is_active'=>$is_active,'is_featured'=>$is_featured,'search'=>$title,'slug'=>$slug,'video_recommendations'=>$recomendations, 'video'=>$video,'confidential'=>$confidential]);
    //       dd($consulta);
    //     flash('Job has been added!')->success();
    //     return redirect('/posted-jobs');
    // }
    public function updateFrontJob(Request $request){
        
        if (is_null($request->input('videoJobURL')) || empty($request->input('videoJobURL'))) {
            $video= "";
        }
        else  {
            $video = $request->input('videoJobURL');
        }
        $id_job=$request->input('idHidden');
        $title=$request->input('title');
        $slug=str_replace(" ","-",$title);
        $description=$request->input('description');
        $country_id=$request->input('country_id');
        $state_id=$request->input('state_id');
        $city_id=$request->input('city_id');
        $salary_from=$request->input('salary_from');
        $salary_to=$request->input('salary_to');
        $salary_currency=$request->input('salary_currency');
        $salary_period_id=$request->input('salary_period_id');
        $hide_salary=$request->input('hide_salary');
        $career_level_id=$request->input('career_level_id');
        $functional_area_id=$request->input('functional_area_id');
        $job_type_id=$request->input('job_type_id');
        $job_shift_id=$request->input('job_shift_id');
        $num_of_positions=$request->input('num_of_positions');
        $gender_id=$request->input('gender_id');
        $expiry_date=$request->input('expiry_date');
        $degree_level_id=$request->input('degree_level_id');
        $job_experience_id=$request->input('job_experience_id');
        $is_freelance=$request->input('is_freelance');
        $recomendations=$request->input('recomendations');
        $confidential=$request->input('confidential');

        $description = str_replace("\"", "'", $description);

        if( Auth::guard('company')->user() ) {            
            $company_id = Auth::guard('company')->user()->id;
        } elseif(Auth::guard('recruiter')->user()->recruiterType()) {
            $company_id =Auth::guard('recruiter')->user()->id_company;
        }
        $is_active=1;
        $is_featured=1;
        DB::update('update forge.jobs
        SET
        company_id = :company_id,
        title = :title,
        description = :description,
        country_id = :country_id,
        state_id = :state_id,
        city_id = :city_id,
        is_freelance = :is_freelance,
        career_level_id = :career_level_id,
        salary_from = :salary_from,
        salary_to = :salary_to,
        hide_salary = :hide_salary,
        salary_currency = :salary_currency,
        salary_period_id = :salary_period_id,
        functional_area_id = :functional_area_id,
        job_type_id = :job_type_id,
        job_shift_id = :job_shift_id,
        num_of_positions = :num_of_positions,
        gender_id = :gender_id,
        expiry_date = :expiry_date,
        degree_level_id = :degree_level_id,
        job_experience_id = :job_experience_id,
        is_active = :is_active,
        is_featured = :is_featured,
        updated_at = CURDATE(),
        search = :search,
        slug = :slug,
        video_recommendations = :video_recommendations,
        videoJobURL = :video,
        confidential=:confidential
        WHERE id = :id_job', ['company_id'=>$company_id,'title'=>$title,'description'=>$description,'country_id'=>$country_id,'state_id'=>$state_id,'city_id'=>$city_id,'is_freelance'=>$is_freelance,'career_level_id'=>$career_level_id,'salary_from'=>$salary_from,'salary_to'=>$salary_from,'hide_salary'=>$hide_salary,'salary_currency'=>$salary_currency,'salary_period_id'=>$salary_period_id,'functional_area_id'=>$functional_area_id,'job_type_id'=>$job_type_id,'job_shift_id'=>$job_shift_id,'num_of_positions'=>$num_of_positions,'gender_id'=>$gender_id,'expiry_date'=>$expiry_date,'degree_level_id'=>$degree_level_id,'job_experience_id'=>$job_experience_id,'is_active'=>$is_active,'is_featured'=>$is_featured,'search'=>$title,'slug'=>$slug,'video_recommendations'=>$recomendations, 'video'=>$video,'confidential'=>$confidential ,'id_job'=>$id_job ]);

        $skills = $request->input('skills');

        if (!is_null($skills)) {
            $countSkills = count($skills);

            if ($countSkills == 1) {
                $newSkills = '('.$id_job.','.$skills[0].')';
            }
            else if ($countSkills > 1) {
                $newSkills = '';
                for($i = 0; $i < $countSkills; $i++) {
                    if ($i === $countSkills-1){
                        $newSkills = $newSkills . '('.$id_job.','.$skills[$i].')';
                    }
                    else {
                        $newSkills = $newSkills . '('.$id_job.','.$skills[$i].'), ';
                    }
                }
            }

            $delete = DB::delete('delete from manage_job_skills where job_id = :job_id;', ['job_id'=>$id_job]);
            $consulta = 'insert into manage_job_skills(job_id, job_skill_id) values '.$newSkills.';';
            $saveSkills = DB::insert($consulta);
        }
        else {
            $delete = DB::delete('delete from manage_job_skills where job_id = :job_id;', ['job_id'=>$id_job]);
        }
        flash('Job has been updated!')->success();
        return redirect('/edit-front-job/'.$id_job);
    }
    public function updateVideo(Request $request)
    {
        $video=$request->input('nameFile');
        $id_job=$request->input('idJob');

        DB::update('update forge.jobs SET videoJobURL = :video WHERE id = :id_job', [ 'video'=>$video, 'id_job'=>$id_job ]);
    }
}

