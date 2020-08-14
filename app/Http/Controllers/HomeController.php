<?php

namespace App\Http\Controllers;

use Redirect;
use App\Traits\Cron;
use Illuminate\Http\Request;
use App\Helpers\ProfileArrayHelper;

class HomeController extends Controller
{

    use Cron;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->runCheckPackageValidity();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Comprueba el llenado del perfil del usuario candidato en caso de no cumplir redireccion a la vista correspondiente
     */
    public function index()
    {
        $perfil_completo=ProfileArrayHelper::comprueba();
        if ($perfil_completo['cumplePerfil'] & $perfil_completo['cumpleVideo'] ) {
            
            session_start();
            if(isset($_SESSION["job_slug"])){
                $slug = $_SESSION["job_slug"];
                return \Redirect::route('job.detail', [$slug]);
            }
            return view('home');
        }else {
            return \Redirect::route('home.profile');
        }
        
    }

}
