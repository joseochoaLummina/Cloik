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
    
    public function recruiterInvitationError(Request $request, $id) {
        $invitationId = $id;
        $invitation = RecruiterInvitation::where('id', $id)->update(['state'=>1]);


        return view('general.error_report_inv_recruiter');
    }

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

    public function DeleteRecruiter($id) {
        DB::delete('update recruiters set is_active = 0 where id = :id', ['id' => $id]);
        return \Redirect::route('company.recruiters');
    }
    
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
}
