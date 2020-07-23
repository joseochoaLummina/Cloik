<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Request;
use Symfony\Component\Console\Input\Input;
use App\Company;
use Auth;

class ProfileArrayHelper {
    
    public static function comprueba(){        
        $user = Auth::user();        
        $perfil=DB::select('select id, first_name, last_name, name, email, date_of_birth, gender_id, marital_status_id, nationality_id, country_id, state_id, city_id, industry_id, street_address, image from users where id=:id_user',['id_user'=>$user->id]);
        $videos=DB::select('select count(*) as cantidad from video_apply where id_user=:id_user', ['id_user'=>$user->id]);
        $cumplePerfil=true;
        $cumpleVideo=true;
        foreach($perfil[0] as $valor){
            if ($valor===null || ""){
                $cumplePerfil=false;
            }
        }
        if ($videos[0]->cantidad===0 ) {
            $cumpleVideo=false;
        }
        $array = array('cumplePerfil' => $cumplePerfil,'cumpleVideo'=>$cumpleVideo );
        return $array;
    }
    
    public static function compruebaCompany(){
        $company = Company::findOrFail(Auth::guard('company')->user()->id);
        $perfil=DB::select('select name,email,industry_id,description,location,no_of_offices,established_in,phone,logo,country_id,state_id,city_id 
        FROM forge.companies where id=:id_company', ['id_company'=>$company->id]);
        $cumplePerfil=true;
        foreach($perfil[0] as $valor){
            if ($valor===null || $valor==='' ){
                $cumplePerfil=false;
            }
        }
        return $cumplePerfil;
    }
}