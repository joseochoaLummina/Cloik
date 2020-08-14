<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use ImgUploader;
use Input;
use Redirect;
use Auth;
use Mail;
use App\SiteSetting;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Recruiter;
use App\RecruiterInvitation;
use Illuminate\Support\Facades\Hash;
use App\Mail\RecruiterInvitationRegisterMail;

class GeneralController extends Controller
{
    /**
     * Retorna una vista de error en caso de fallar el registro de un reclutador nuevo
     */
    public function recruiterInvitationError(Request $request, $id) {
        $invitationId = $id;
        $invitation = RecruiterInvitation::where('id', $id)->update(['state'=>1]);


        return view('general.error_report_inv_recruiter');
    }
    /**
     * Al abrir un link de registro de reclutador, esta funcion verifica si esta activa la invitación y obtiene los datos para crear el registro en la tabla de reclutadores Y devuelve el formulario para que ingrese los campos que faltan
     */
    public function showRecruiterForm(Request $request, $id) {
        $invitationId = $id;
        $invitation = RecruiterInvitation::where('id', $id)->first();
        $mail = $invitation->email;
        $companyId = $invitation->company_id;
        $is_master = $invitation->is_master;
        $state = $invitation->state;

        if ($is_master) {
            $values = DB::select('select count(R.id) as cuenta, P.recruiters_master_limit as limite
            from recruiters R 
            inner join companies C on R.id_company = C.id
            inner join packages P on C.package_id = P.id
            where R.id_company = :id_company and R.is_master = 1', ['id_company' => $companyId]);
        }
        else {
            $values = DB::select('select count(R.id) as cuenta, P.recruiters_jr_limit as limite
            from recruiters R 
            inner join companies C on R.id_company = C.id
            inner join packages P on C.package_id = P.id
            where R.id_company = :id_company and R.is_master = 0', ['id_company' => $companyId]);
        }
        
        $enableForm = ($values[0]->cuenta < $values[0]->limite) ? true : false;

        return view('general.recruiter_form')->with('mail', $mail)
                                            ->with('invitation', $invitationId)
                                            ->with('is_master', $is_master)
                                            ->with('company', $companyId)
                                            ->with('state', $state)
                                            ->with('enableForm', $enableForm);
    }
    /**
     * Manda a los correos de invitacion para ser registrado como reclutador ingresados por una compnñia 
     */
    public function sendInvitationRecruiter(Request $request) 
    {
        $emailsMaster = $request->input('emailsMaster');
        $emailsJr = $request->input('emailsJrs');
        $company = Auth::guard('company')->user();

        if (isset($emailsMaster)) {
            foreach($emailsMaster as $email) {

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
            }
        }

        if (isset($emailsJr)) {
            foreach($emailsJr as $email) {

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
    }
    /**
     * Cambia el estado de un reclutador a inactivo cuando lo elimina una compañia
     */
    public function DeleteRecruiter($id) {
        DB::delete('update recruiters set is_active = 0 where id = :id', ['id' => $id]);
        return \Redirect::route('company.recruiters');
    }
    /**
     * Funcion para la creacion de nuevos reclutadores
     */
    public function newRecruiter(Request $request) {
        $cuenta = DB::select('select count(*) as cuenta from recruiters where email = (select email from recruiter_invitations where id = :id )', ['id' => $request->input('invitation')]);
        
        if ($cuenta[0]->cuenta == 0) {
            DB::update('update recruiter_invitations set state = 0 where id = :id', ['id'=> $request->input('invitation')]);
            $recruiter = new Recruiter();

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $fileName = ImgUploader::UploadImage('recruiters_images', $image, $request->input('name'), 300, 300, false);
                $recruiter->image = $fileName;
            }

            $recruiter->name = $request->input('name');
            $recruiter->lastname = $request->input('lastname');
            $recruiter->phone = $request->input('phone');
            $recruiter->id_company = $request->input('company');
            $recruiter->email = $request->input('email');
            $recruiter->is_master = $request->input('is_master');
            $recruiter->password = Hash::make($request->input('password'));

            $recruiter->save();
            if ($recruiter->id > 0) {
                return \Redirect::route('login');
            }
        }
        else {
            $company = DB::select('select C.name from companies C inner join recruiter_invitations R
                 on R.company_id = C.id where R.email = (select email from recruiter_invitations where id = :id )', ['id' => $request->input('invitation')]);
            return view('general.recruiter_exists')->with('company_name', $company[0]->name);
        }

    }
    /**
     * Verifica la exisitencia del reclutador antes de enviar invitacion
     */
    public function verifyExistEmail(Request $request){
        
        $companyId=Auth::guard('company')->user()->id;
        $email=$request->input('email');
        $lookInUsers=DB::select('SELECT true exist from users where email=:email',['email'=>$email]);
        $lookInCompanies=DB::select('SELECT true exist from companies where email=:email',['email'=>$email]);
        $lookInRecruiters=DB::select('SELECT true exist from recruiters where email=:email and id_company=:companyId',['email'=>$email,'companyId'=>$companyId]);

        if(count($lookInUsers)>0){
            return "user";

        }elseif(count($lookInCompanies)>0){
            return "company";

        }elseif(count($lookInRecruiters)>0){
            return "recruiter";

        }else{
            return "notFind";
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
