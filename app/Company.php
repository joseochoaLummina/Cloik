<?php

namespace App;

use Auth;
use App;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Traits\Active;
use App\Traits\Featured;
use App\Traits\JobTrait;
use App\Traits\CountryStateCity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CompanyResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Company extends Authenticatable
{

    use Active;
    use Featured;
    use Notifiable;
    use JobTrait;
    use CountryStateCity;

    protected $table = 'companies';
    public $timestamps = true;
    protected $guarded = ['id'];
    //protected $dateFormat = 'U';
    protected $dates = ['created_at', 'updated_at', 'package_start_date', 'package_end_date'];
    protected $fillable = [
        'name', 'email', 'password',
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CompanyResetPassword($token));
    }

    public function printCompanyImage($width = 0, $height = 0)
    {
        $logo = (string) $this->logo;
        $logo = (!empty($logo)) ? $logo : 'no-no-image.gif';
        return \ImgUploader::print_image("company_logos/$logo", $width, $height, '/admin_assets/no-image.png', $this->name);
    }

    public function jobs()
    {
        return $this->hasMany('App\Job', 'company_id', 'id');
    }

    public function openJobs()
    {
        return Job::where('company_id', '=', $this->id)->notExpire();
    }

    public function getOpenJobs()
    {
        return $this->openJobs()->get();
    }

    public function countOpenJobs()
    {
        return $this->openJobs()->count();
    }

    public function industry()
    {
        return $this->belongsTo('App\Industry', 'industry_id', 'id');
    }

    public function getIndustry($field = '')
    {
        $industry = $this->industry()->lang()->first();
        if (null === $industry) {
            $industry = $this->industry()->first();
        }
        if (null !== $industry) {
            if (!empty($field)) {
                return $industry->$field;
            } else {
                return $industry;
            }
        }
    }

    public function ownershipType()
    {
        return $this->belongsTo('App\OwnershipType', 'ownership_type_id', 'id');
    }

    public function getOwnershipType($field = '')
    {
        $ownershipType = $this->ownershipType()->lang()->first();
        if (null === $ownershipType) {
            $ownershipType = $this->ownershipType()->first();
        }
        if (null !== $ownershipType) {
            if (!empty($field)) {
                return $ownershipType->$field;
            } else {
                return $ownershipType;
            }
        }
    }

    public function countFollowers()
    {
        return FavouriteCompany::where('company_slug', 'like', $this->slug)->count();
    }

    public function getFollowerIdsArray()
    {
        return FavouriteCompany::where('company_slug', 'like', $this->slug)->pluck('user_id')->toArray();
    }

    public function countCompanyMessages()
    {
        return CompanyMessage::where('company_id', '=', $this->id)->where('is_read', '=', 0)->count();
    }

    public function getSocialNetworkHtml()
    {
        $html = '';
        if (!empty($this->facebook))
            $html .= '<a href="' . $this->facebook . '" target="_blank"><i class="fa fa-facebook-square" aria-hidden="true"></i></a>';

        if (!empty($this->twitter))
            $html .= '<a href="' . $this->twitter . '" target="_blank"><i class="fa fa-twitter-square" aria-hidden="true"></i></a>';

        if (!empty($this->linkedin))
            $html .= '<a href="' . $this->linkedin . '" target="_blank"><i class="fa fa-linkedin-square" aria-hidden="true"></i></a>';

        if (!empty($this->google_plus))
            $html .= '<a href="' . $this->google_plus . '" target="_blank"><i class="fa fa-google-plus-square" aria-hidden="true"></i></a>';

        if (!empty($this->pinterest))
            $html .= '<a href="' . $this->pinterest . '" target="_blank"><i class="fa fa-pinterest-square" aria-hidden="true"></i></a>';

        return $html;
    }

    public function isFavouriteApplicant($user_id, $company_i)
    {
        $company_id=Auth::guard('company')->user()->id;
        $return = false;
        if (Auth::guard('company')->check()) {
            $count = FavouriteApplicant::where('user_id', $user_id)
                    ->where('company_id', $company_id)
                    ->count();
            if ($count > 0)
                $return = true;
        }
        return $return;
    }

    public function package()
    {
        return $this->hasOne('App\Package', 'id', 'package_id');
    }

    public function getPackage($field = '')
    {
        $package = $this->package()->first();
        if (null !== $package) {
            if (!empty($field)) {
                return $package->$field;
            } else {
                return $package;
            }
        }
    }

    public function countMessageNotRead()
    {
        $countMNR = DB::select('Select count(*) as msgNotRead FROM messages_center where company_id = :id and state = 0 and receivedfrom = 0;', ['id'=>$this->id]);
        return $countMNR[0]->msgNotRead;
    }

    public function countMasterRecruiter() 
    {
        $masterCount = DB::select('select count(*) as cuentaMaster from recruiters where id_company = :id and is_active = 1 and is_master = 1', ['id'=>$this->id]);
        return $masterCount[0]->cuentaMaster;
    }

    public function countJrRecruiter() 
    {
        $jrCount = DB::select('select count(*) as cuentaJr from recruiters where id_company = :id and is_active = 1 and is_master = 0', ['id'=>$this->id]);
        return $jrCount[0]->cuentaJr;
    }

    public function limitMasterRecruiter() 
    {
        $masterCount = DB::select('select P.recruiters_master_limit from packages P
        inner join companies C on C.package_id = P.id
        where C.id = :id', ['id'=>$this->id]);
        return $masterCount[0]->recruiters_master_limit;
    }

    public function limitJrRecruiter() 
    {
        $jrCount = DB::select('select P.recruiters_jr_limit from packages P
        inner join companies C on C.package_id = P.id
        where C.id = :id', ['id'=>$this->id]);
        return $jrCount[0]->recruiters_jr_limit;
    }

    public function countPendingMeetings() 
    {
        $countPM = DB::select('select count(*) as pm from meetings where company_id = :id and state = 0 and ( salon IS NULL or salon = "") and current_date() <= planned_date', ['id'=>$this->id]);
        return $countPM[0]->pm;
    }
    
    public function isBlackList($user_id, $company_id)
    {
        $return = false;
        if (Auth::guard('company')->check()) {
            $count = BlackListApplicant::where('id_candidato', $user_id)
                    ->where('id_empresa', $company_id)
                    ->count();
            if ($count > 0)
                $return = true;
        }
        return $return;
    }

    public function isVerified($company_id) {
        $return = false;
        if (Auth::guard('company')->check()) {
            $verified = DB::select('select verified from companies where id = :id', ['id'=> $company_id]);
            return $verified[0]->verified;
        }
    }

}
