<?php

namespace App\Http\Controllers;

use App\videoApply;
use Auth;
use App\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Aws\S3\S3Client;
use AWS\Exception\AwsException;
use AWS\S3\ObjectUploader;
use App\Video;
use Illuminate\Http\Testing\File;
use Symfony\Component\Console\Input\Input;


class videoApplyController extends Controller
{
    private $fields = array('id', 'video', 'title', 'ext', 'dir', 'marca', 'id_user', 'is_main', 'is_active');

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['newVideoApplication']]);
    }
    
    /**
     * Funcion que retorna un arreglo con el video principal (main) de un candidato
     */
    public function getMainVideo() {
        $videos = DB::select('select id, video, title, ext, dir, marca, id_user, is_main, is_active from video_apply where id_user = :id_user and is_active = 1', ['id_user'=>Auth::user()->id]);
        $mainVideo = collect($videos)->where('is_main', '=', 1)->toArray();
        return $mainVideo;
    }

    /**
     * Funcion que retorna un arreglo con los videos (menos el principal) de un candidato
     */
    public function getVideos() {
        $videos = DB::select('select id, video, title, ext, dir, marca, id_user, is_main, is_active from video_apply where id_user = :id_user and is_active = 1', ['id_user'=>Auth::user()->id]);
        $videos = collect($videos)->where('is_main', '=', 0)->toArray();
        return $videos;
    }

    /**
     * Funcion que carga la vista de los videos del candidato
     */
    public function getVideoApplication(Request $request) {        
        session_start();

        // se cambia un video en especifico para que sea ahora el video main
        if ($request->input('Met') == 'PUT') {
            DB::update('update video_apply set is_main = 0 where id_user = :id', ['id'=>Auth::user()->id]);
            DB::update('update video_apply set is_main = 1 where id = :id', ['id'=>$request->input('video_id')]);
        }

        // si estaba en proceso de aplicacion a una plaza se redirige a esa plaza 
        // cuando a grabado nuevo video o realizado algun cambio
        if (isset($_SESSION["apply_slug"])) {
            $aux_slug = $_SESSION["apply_slug"];
            unset($_SESSION["apply_slug"]);
            session_destroy();
            return \Redirect::route('job.detail', [$aux_slug]);
        }
        else {
            if(null !== $request->input('slug')) {
                $_SESSION["apply_slug"] = $request->input('slug');
            }
        }

        $recommendation=DB::select('SELECT recomendations FROM forge.site_settings');

        $mainVideo = $this->getMainVideo();
        $videos = $this->getVideos();
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $type = '';
        $formal = '';

        return view('user.applicant_videos')
                    ->with('mainVideo', $mainVideo)
                    ->with('videos', $videos)
                    ->with('recommendation', $recommendation[0]->recomendations );

    }

    /**
     * Funcion que establece un video como main (principal)
     */
    public function setMainVideo($request) {
        $video_id = $request;
        DB::update('update video_apply set is_main = 1 where id = :id', ['id'=>$video_id]);
        DB::update('update video_apply set is_main = 0 where id <> :id and id_user = :user', ['id'=>$video_id, 'user'=> Auth::user()->id]);
    }

    /**
     * Funcion que guarda en la base de datos un nuevo video grabado por el candidato
     */
    public function newVideoApplication(Request $request) {
        $videoName = $request->input('name');
        $marca = $request->input('marca');
        $is_main = $request->input('is_main');  //is_main siempre sera 0, asi viene en la peticion
        // if ($is_main == 1) {
        //     DB::update('update video_apply set is_main = 0 where id_user=:user', ['user'=>Auth::user()->id]);
        // }

        // se verifica que el candidato tenga un video principal, si no lo tiene el nuevo video sera el principal
        if(count($this->getMainVideo())>0){
            DB::insert('insert into video_apply(video, title, ext, dir, marca, id_user, is_main,is_active) values(:video, :title, :ext, :dir, :marca, :user, :is_main, 1)', 
            ['video'=> $videoName, 'title'=> $videoName, 'ext'=>'mp4', 'dir' => 'videos', 'marca' => $marca, 'user'=>Auth::user()->id, 'is_main'=> 0]);  
        }else{
            DB::insert('insert into video_apply(video, title, ext, dir, marca, id_user, is_main,is_active) values(:video, :title, :ext, :dir, :marca, :user, :is_main, 1)', 
            ['video'=> $videoName, 'title'=> $videoName, 'ext'=>'mp4', 'dir' => 'videos', 'marca' => $marca, 'user'=>Auth::user()->id, 'is_main'=> 1]);  
        }
    }

    /**
     * Funcion que comprueba si el candidato cumple ls requisitos para aplicar a una plaza
     */
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
        if($msg!=""){
            flash(__($msg))->error();
        }

        return $aplicar;

        /*
            Se retorna un true si el candidato cumple con los requisitos
            o un false en caso de que no los cumpla.
            (Actualmente SIEMPRE retornara true)
        */
    }
    
    /**
     * Funcion que retorna los datos necesarios de un candidato al abrir la modal para escoger un video para aplicar
     */
    public function getVideoApply($job_slug){
        $videoMain = $this->getMainVideo();
        $video=$this->getVideos();
        $videos = array_merge($videoMain,$video);
        $job = Job::where('slug', 'like', $job_slug)->first();
        $recomendaciones=$job->video_recommendations;
        $aplicar=$this->compruebaAplicante($job);
  
        return view('job.modal_apply')
                    ->with('job',$job)
                    ->with('videos', $videos)
                    ->with('aplicar', $aplicar)
                    ->with('recomendaciones',$recomendaciones)
                    ->with('slug', $job_slug);

        /*
            Dado que actualmente el boton de "Aplicar ya" en un plaza siempre esta habilitado,
            al abrir la modal para escoger el video esta funcion se encarga de verificar
            que el candidato tenga videos para aplicar en caso que los tenga los retorna y los muestra
            en la modal para que el escoja con cual quiere aplicar a esa plaza.
        */
    }

    /**
     * Funcion que retorna la cantidad de videos que tiene actualmente un usuario
     */
    public function getCountVideo(){     
        $user = Auth::user();
        $count = DB::select('select count(*) as cuenta from video_apply where id_user = :id', ['id' => $user->id]);
        $valor = collect($count)->toArray();
        return $valor[0]->cuenta;
    }

    /**
     * Funcion que elimina un video del candidato
     */
    public function deleteVideoApplication(Request $request) {
        $name = DB::select('select video, marca, ext from video_apply where id = :id', 
        ['id'=>$request->input('video_id')]);
        DB::delete('delete from video_apply where id = :id', ['id'=>$request->input('video_id')]);
        return $name[0]->marca.$name[0]->video.'.'.$name[0]->ext;
    }

    /**
     * Funcion que elimina un video de la compañia
     */
    public function deleteVideoCompany(Request $request) {
        $name = DB::select('select video from jobs where id = :id', 
        ['id'=>$request->input('job_id')]);
        DB::delete('update jobs set video=null where id = :id', ['id'=>$request->input('job_id')]);
        return $name[0]->video;
    }

    /**
     * Funcion que encargada de guardar los videos con los que el usuario aplica a cada plaza
     */
    public function newVideoJob(Request $request) {
        $videoName = $request->input('name');
        $marca = $request->input('marca');
        $is_main = $request->input('is_main');
        if ($is_main == 1) {
            DB::update('update video_apply set is_main = 0 where id_user=:user', ['user'=>Auth::user()->id]);
        }
        DB::insert('insert into video_apply(video, title, ext, dir, marca, id_user, is_main,is_active) values(:video, :title, :ext, :dir, :marca, :user, :is_main, 1)', 
        ['video'=> $videoName, 'title'=> $videoName, 'ext'=>'mp4', 'dir' => 'videos', 'marca' => $marca, 'user'=>Auth::user()->id, 'is_main'=> $is_main]);  
        // s3 = \Storage::disk('s3')->put('videos/'.$title, js $request->query('_'));
        // return $s3;
    }
}
