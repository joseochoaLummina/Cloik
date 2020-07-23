<?php

namespace App;

use Auth;
use App;
use App\Traits\Active;
use App\Traits\Featured;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Recruiter extends Authenticatable
{

    use Active;
    use Featured;
    use Notifiable;

    protected $table = 'recruiters';
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $dates = ['created_at', 'updated_at'];
    protected $fillable = [
        'name', 'email', 'password',
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function printRecruiterImage($width = 0, $height = 0)
    {
        $logo = (string) $this->image;
        $logo = (!empty($logo)) ? $logo : 'no-no-image.gif';
        return \ImgUploader::print_image("recruiters_images/$logo", $width, $height, '/admin_assets/no-image.png', $this->name);
    }

    public function recruiterType()
    {
        $recruiter_id=$this->id;
        $type=DB::select('select is_master from recruiters where id=:recruiter_id', ["recruiter_id"=>$recruiter_id]);
        return $type[0]->is_master;
    }
    public function countPendingMeetings() 
    {
        $countPM = DB::select('select count(*) as pm from recruiter_meetings RM join meetings M on RM.meeting_id=M.id where RM.recruiter_id = :id and M.state = 0 and ( M.salon IS NULL or M.salon = "") and current_date() <= M.planned_date ', ['id'=>$this->id]);
        return $countPM[0]->pm;
    }
    public function isVerifiedCompany() 
    {
        $company_id=$this->id_company;
        $verified = DB::select('select verified from companies where id = :id', ['id'=> $company_id]);
        return $verified[0]->verified;
    }
    public function openJobs()
    {
        return Job::where('company_id', '=', $this->id_company)->notExpire();
    }
    public function getOpenJobs()
    {
        return $this->openJobs()->get();
    }
    public function countOpenJobs()
    {
        return $this->openJobs()->count();
    }
    public function countFollowers()
    {
        $slug=DB::select('SELECT slug FROM companies where id=:id_company', ['id_company'=>$this->id_company]);
        return FavouriteCompany::where('company_slug', 'like', $slug[0]->slug)->count();
    }
    public function countRecruiterMessages()
    {
        return CompanyMessage::where('company_id', '=', $this->id)->where('is_read', '=', 0)->count();
    }
    

    public function jobs()
    {
        return $this->hasMany('App\Job', 'company_id', 'id_company');
    }

    public function isBlackList($user_id, $company_id)
    {
        $return = false;
        if (Auth::guard('recruiter')->check()) {
            $count = BlackListApplicant::where('id_candidato', $user_id)
                    ->where('id_empresa', $company_id)
                    ->count();
            if ($count > 0)
                $return = true;
        }
        return $return;
    }
    public function isFavouriteApplicant($user_id, $company_i)
    {
        $company_id=$this->id_company;
        $return = false;
        if (Auth::guard('recruiter')->check()) {
            $count = FavouriteApplicant::where('user_id', $user_id)
                    ->where('company_id', $company_id)
                    ->count();
            if ($count > 0)
                $return = true;
        }
        return $return;
    }
}
