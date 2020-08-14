<?php

namespace App\Http\Controllers\Admin;

use Hash;
use File;
use ImgUploader;
use Auth;
use DB;
use Input;
use Redirect;
use Mail;
use App\Package;
use App\Recruiter;
use App\Country;
use App\Company;
use App\State;
use App\City;
use App\Industry;
use App\OwnershipType;
use App\SiteSetting;
use Carbon\Carbon;
use App\Helpers\MiscHelper;
use App\Helpers\DataArrayHelper;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use DataTables;
use App\Http\Controllers\Controller;
use App\RecruiterInvitation;
use App\Mail\RecruiterInvitationRegisterMail;

class RecruiterController extends Controller
{
 /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Funcion que retorna la vista de los reclutadores en el admin
     */
    public function indexRecruiters()
    {
        return view('admin.recruiter.index');
    }

    /**
     * Funcion que recoleta la informacion de los reclutadores
     */
    public function fetchRecruitersData(Request $request)
    {
        // $recruiters=DB::select('select R.id,R.name,R.lastname,C.name as Cname,R.id_company,R.email,R.image,R.is_active,R.is_master from recruiters R join companies C on R.id_company=C.id ');
        
        // Arreglo con la informacion de la tabla recruiters incluyendo el nombre el nombre de la empresa al que pertenece
        $recruiters = Recruiter::
        join('companies','companies.id','=','recruiters.id_company')
        ->select([
                    'recruiters.id',
                    'recruiters.name',
                    'recruiters.lastname',
                    'recruiters.id_company',
                    'companies.name as Cname',
                    'recruiters.email',
                    'recruiters.image',
                    'recruiters.is_active',
                    'recruiters.is_master',                    
        ]);
        
        // Se retorna cada tipo de informacion correspondiente a cada reclutador
        return Datatables::of($recruiters)
                        ->filter(function ($query) use ($request) {
                            if ($request->has('name') && !empty($request->name)) {
                                $query->where('recruiters.name', 'like', "%{$request->get('name')}%")
                                    ->orWhere('recruiters.lastname', 'like', "%{$request->get('name')}%");
                            }
                            
                            if ($request->has('email') && !empty($request->email)) {
                                $query->where('recruiters.email', 'like', "%{$request->get('email')}%");
                            }
                            if ($request->has('company') && !empty($request->company)) {
                                $query->where('companies.name', 'like', "%{$request->get('company')}%");
                            }
                            if ($request->has('is_master') && $request->is_master != -1) {
                                $query->where('recruiters.is_master', '=', "{$request->get('is_master')}");
                            }
                        })
                        ->addColumn('is_master', function ($recruiters) {
                            return ((bool) $recruiters->is_master) ? 'Yes' : 'No';
                        })
                        ->addColumn('action', function ($recruiters) {
                            /*                             * ************************* */
                            $activeTxt = 'Make Master';
                            $activeHref = 'makeMaster(' . $recruiters->id . ','.$recruiters->id_company.');';
                            $activeIcon = 'square-o';
                            if ((int) $recruiters->is_master == 1) {
                                $activeTxt = 'Make Junior';
                                $activeHref = 'makeJunior(' . $recruiters->id . ','.$recruiters->id_company.');';
                                $activeIcon = 'check-square-o';
                            }                           
                            return '
				<div class="btn-group">
					<button class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action
						<i class="fa fa-angle-down"></i>
					</button>
					<ul class="dropdown-menu">
						<li>
							<a href="' . route('list.jobs', ['company_id' => $recruiters->id_company]) . '" target="_blank"><i class="fa fa-list" aria-hidden="true"></i>List Jobs</a>
						</li>					
						<li>
							<a type="button" id="deleteRecruiterLink" onClick="deleteRecruiter(' . $recruiters->id . ')" ><i class="fa fa-trash-o" aria-hidden="true"></i>Delete</a>
						</li>
						
<li><a href="javascript:void(0);" onClick="' . $activeHref . '" id="onclickActive' . $recruiters->id . '"><i class="fa fa-' . $activeIcon . '" aria-hidden="true"></i>' . $activeTxt . '</a></li>
					</ul>
				</div>';
                        })
                        ->rawColumns(['action', 'is_master'])
                        ->setRowId(function($recruiters) {
                            return 'recruiterDtRow' . $recruiters->id;
                        })
                        ->make(true);
        //$query = $dataTable->getQuery()->get();
        //return $query;
    }
    /**
     * Funcion para eliminar un reclutador 
     */
    public function deleteRecruiter(Request $request)
    {
        $recruiter_id=$request->get('id');
        try {
            $recruiter = Recruiter::findOrFail($recruiter_id);
            $recruiter->delete();
            return 'ok';
        } catch (ModelNotFoundException $e) {
            return 'notok';
        }
    }
    /**
     * Funcion para Hacer un reclutador Jr un reclutador Master
     */
    
    public function makeMaster(Request $request)
    {
        $recruiter_id = $request->input('id');
        $recruiter = Recruiter::findOrFail($recruiter_id);
        $limit=DB::select('select true from companies C join packages P on C.package_id=P.id join recruiters R on R.id_company=C.id where (select count(id) from recruiters where is_master=1 and id_company=:company_id)<P.recruiters_master_limit group by C.id', ['company_id'=>$recruiter->id_company]);
        if ($limit) {
            try {
            
                $recruiter->is_master = 1;
                $recruiter->update();
                echo 'ok';
            } catch (ModelNotFoundException $e) {
                echo 'notok';
            }
        }else{
            return 'This company has exempted the maximum of this type of recruiters assigned to its payment package';
        }
    }
    /**
     * Funcion para Hacer un reclutador Master un reclutador JR
     */
    
    public function makeJunior(Request $request)
    {
        $recruiter_id = $request->input('id');
        $recruiter = Recruiter::findOrFail($recruiter_id);
        $limit=DB::select('select true from companies C join packages P on C.package_id=P.id join recruiters R on R.id_company=C.id where (select count(id) from recruiters where is_master=0 and id_company=:company_id)<P.recruiters_jr_limit group by C.id', ['company_id'=>$recruiter->id_company]);
        if ($limit) {
            try {
                $recruiter->is_master = 0;
                $recruiter->update();
                echo 'ok';
            } catch (ModelNotFoundException $e) {
                echo 'notok';
            }
        }else{
            return 'This company has exempted the maximum of this type of recruiters assigned to its payment package';
        }
    }
    /**
     * Funcion que redirecciona a la vista para crear un nuevo reclutador 
     */
    
    public function newRecruiter(Request $request)
    {
        $companies = DataArrayHelper::companiesArray();
        // $package = Package::find($package_id);
        return view('admin.recruiter.newRecruiter')
                ->with('companies',$companies);
    }
    /**
     * Recoleta la informacion sobre los reclutadores de una empresa en especifico al intentar crear uno nuevo
     */
    public function fetchDataRecruitersCompanies(Request $request)
    {
        $numMaster=0;
        $numJr=0;
        $array=array();
        $company_id=$request->input('company_id');
        $company_package = Company::findOrFail($company_id)->package;
        $recruiter=Recruiter::where('id_company','=',$company_id)->get();
        foreach ($recruiter as $key) {
            if($key->is_master){
                $numMaster++;
            }else{
                $numJr++;
            }
        }
        
        array_push($array,$company_package,$numMaster,$numJr);
        return $array;

        /* El objetivo de esta funcion es obtener informacion sobre los tipos de reclutadores
            - la cantidad actual de reclutadores 
            - las cantidad disponbile
            
            Esta informacion es de una empresa en especifico que ha sido seleccionada
        */
    }
    /**
     * Funcion que envia una invitacion por correo para registrarse como un nuevo reclutador    
     */    
    public function sendInvitationRecruiter($is_master,$company_id,$email) 
    {       
        $company = Company::findOrFail($company_id);
        if ($is_master) {
            $invitation = new RecruiterInvitation;
            $invitation->email = $email;
            $invitation->company_id = $company->id;
            $invitation->is_master = 1;
            $invitation->save();
            $siteSetting = SiteSetting::findOrFail(1272);

            $data['companyName'] = $company->name;
            $data['logo'] = $company->logo;
            $data['companyId'] = $company->id;
            $data['usermail'] = $email;
            $data['frommail'] = $siteSetting->mail_username;
            $data['fromname'] = $siteSetting->mail_from_name;
            $data['invitationRecluterId'] = $invitation->id;
            $data['siteSetting'] = $siteSetting;
            Mail::send(new RecruiterInvitationRegisterMail($data));
        }else {
            $invitation = new RecruiterInvitation;
            $invitation->email = $email;
            $invitation->company_id = $company->id;
            $invitation->is_master = 0;
            $invitation->save();
            $siteSetting = SiteSetting::findOrFail(1272);

            $data['companyName'] = $company->name;
            $data['logo'] = $company->logo;
            $data['companyId'] = $company->id;
            $data['usermail'] = $email;
            $data['frommail'] = $siteSetting->mail_username;
            $data['fromname'] = $siteSetting->mail_from_name;
            $data['invitationRecluterId'] = $invitation->id;
            $data['siteSetting'] = $siteSetting;
            Mail::send(new RecruiterInvitationRegisterMail($data));
        }
    }
    /**
     * Se verifica que el correo al que se desea enviar una invitacion es valido
     */
    
    public function verifyExistEmail(Request $request){
        
        $companyId=$request->input('company_id');
        $email=$request->input('email');
        $lookInUsers=DB::select('SELECT true exist from users where email=:email',['email'=>$email]);
        $lookInCompanies=DB::select('SELECT true exist from companies where email=:email',['email'=>$email]);
        $lookInRecruiters=DB::select('SELECT true exist from recruiters where email=:email and id_company=:companyId',['email'=>$email,'companyId'=>$companyId]);
        $is_master = $request->input('is_master');

        if (count($lookInUsers)>0 || count($lookInCompanies)>0 || count($lookInRecruiters)>0) {
            return 'true';
        } else if(!$email){
            return 'false';
        }else{
            $this->sendInvitationRecruiter($is_master,$companyId,$email);
            return 'ok';
        }
        
        /*
            Esta funcion realiza varias consultas a la base de datos
            para saber si el correo que el usuario intenta invitar
            como un nuevo recluta NO ES:
            -Un candidato
            -una compañia
            -un recluta de la compañia que intenta enviar la invitacion
        */
        
    }
    
}