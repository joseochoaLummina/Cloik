<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Country;
use App\SiteSetting;

class IpClient extends Controller
{
    public function __construct()
    {
        
    }

    public static function getIpClient()
    {
        $ip = $_SERVER['REMOTE_ADDR']; // Esto contendrá la ip de la solicitud.
        if($ip == "::1" || $ip == "127.0.0.1"){
            $ip = "8.8.8.8";  // De prueba ya que lee la ip local
        }
        $connected = @fsockopen("www.google.com", 80); 
        if($connected){
            fclose($connected);
            $dataIp = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));
            $countryName = $dataIp->geoplugin_countryName;
            $country_id = Country::where('country', 'like', $countryName.'%')->pluck('id')->first();
            return  $country_id;
        }else{
            $id = 1272;
            $siteSetting = SiteSetting::findOrFail($id);
            $country_id = $siteSetting->default_country_id; 
            return  $country_id;
        }
    }

}
