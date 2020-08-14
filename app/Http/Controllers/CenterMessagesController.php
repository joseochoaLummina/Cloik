<?php

namespace App\Http\Controllers;

use App\Company;
use Auth;
use Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use App\Http\Controllers\MeetingController;
use App\Mail\UserContactMail;
use App\User;
use App\SiteSetting;

class CenterMessagesController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth'], ['except'=>[
            'getCompanyCenterMessages', 'replyMessage', 'deleteMessage', 'ChangeMessagesToRead', 'sendMessageToCandidate'
            ]
        ]);
    }
    /** 
     * Funcion:getListMessages
     * Obtiene una lista de mensajes recibidos, enviados y notificaciones de parte de una empresa o un usuario candidato
    */
    public function getListMessages($type) {
        if (Auth::guard('company')->check()) {
            $company_id = Auth::guard('company')->user()->id;
            if ($type == 100) {
                $messages = DB::select('select CM.id, CM.user_id, U.name as emisor, CM.company_id, C.name as receptor, CM.type, CM.message, CM.create_at, 
                CM.state, CM.receivedfrom, CM.meeting_id from messages_center CM inner join users U on CM.user_id = U.id 
                inner join companies C on CM.company_id = C.id 
                where CM.company_id = :id and CM.type = 0 and CM.receivedfrom = 1 order by CM.state asc, CM.create_at desc;', ['id'=>$company_id]);
                
            }
            else {
                $messages = DB::select('select CM.id, CM.user_id, U.name as emisor, CM.company_id, C.name as receptor, CM.type, CM.message, CM.create_at, 
                CM.state, CM.receivedfrom, CM.meeting_id from messages_center CM inner join users U on CM.user_id = U.id 
                inner join companies C on CM.company_id = C.id 
                where CM.company_id = :id and CM.type = :type and CM.receivedfrom = 0 order by CM.state asc, CM.create_at desc;', ['id'=>$company_id, 'type' => $type]);
            }
            
        }
        else if (Auth::check()) {
            $user_id = Auth::user()->id;
            $messages = DB::select('select CM.id, CM.user_id, U.name as receptor, CM.company_id, C.name as emisor, CM.type, CM.message, CM.create_at, 
            CM.state, CM.receivedfrom, CM.meeting_id, CM.accepted from messages_center CM inner join users U on CM.user_id = U.id 
            inner join companies C on CM.company_id = C.id 
            where CM.user_id = :id and CM.type = :type and CM.receivedfrom = 1 order by CM.state asc, CM.create_at desc;', ['id'=>$user_id, 'type' => $type]);
        }
        
        return $messages;
    }
    /**
     * Redirecciona a vista correspondiente para el centro de mensajes segun el tipo de mensaje a visualizar
     */
    public function getCenterMessages(Request $request){
        if (!Auth::check()) {
            return Redirect::route('login');
        }

        $messageType = $request->input('messageType');
        $messages = $this->getListMessages($messageType);
        $messages = collect($messages)->toArray();

        return view('message_center')
                ->with('messages', $messages)
                ->with('type', 'messages');
    }
    /**
     * Redirecciona a vista correspondiente para el centro de mensajes segun el tipo de mensaje a visualizar
     */
    public function getCompanyCenterMessages(Request $request){
        if (!Auth::guard('company')->check()) {
            return Redirect::route('login');
        }

        $messageType = $request->input('messageType');
        $messages = $this->getListMessages($messageType);
        $messages = collect($messages)->toArray();

        return view('company.company_message_center')
                ->with('messages', $messages)
                ->with('type', 'messages');
    }
    /**
     * Marca un mensaje como leido
     */
    public function ChangeMessagesToRead(Request $request) {
        $id_message = $request->input('id');
        $type_message = $request->input('typeMessage');

        DB::update('update messages_center set state = 1 where id = :id;', ['id'=>$id_message]);
        $messages = $this->getListMessages($type_message);

        foreach($messages as $message) {
            $message->json = json_encode($message,TRUE);
        }

        return $messages;
    }
    /**
     * Obtiene los mensajes segun el tipo seleccionado
     */
    public function changeMessagesType(Request $request) {        
        $type_message = $request->input('typeMessage');
        $messages = $this->getListMessages($type_message);
        foreach($messages as $message) {
            $message->json = json_encode($message,TRUE);
        }

        
        $messages = collect($messages)->toArray();

        return view('message_center')
                ->with('messages', $messages)
                ->with('type', 'messages');
    }
    /**
     * Acepta una invitacion a un reunion programada enviada de parte de un compañia hacia un usuario candidato
     */
    public function acceptMeeting(Request $request) {
        
        $meeting_id = $request->input('id_meeting');
        $message_id=$request->input('id_message');
        DB::update('update meetings set state = 0 where id = :id', ['id'=>$meeting_id]);
        DB::update('update messages_center set accepted = 1 where id = :id', ['id'=>$message_id]);

        $data = DB::select('select A.user_id, A.company_id, U.name, J.title 
        from meetings A inner join users U 
        on A.user_id = U.id inner join job_apply JA 
        on A.job_apply_id = JA.id
        inner join jobs J on JA.job_id = J.id where A.id = :id;', ['id'=>$meeting_id]);

        $dataMessage['user_id'] = $data[0]->user_id;
        $dataMessage['company_id'] = $data[0]->company_id;
        $dataMessage['type'] = 1;
        $dataMessage['message'] = $data[0]->name." has accepted the meeting regarding his application to the position ".$data[0]->title." position.";
        $dataMessage['receivedfrom'] = 0;
        $dataMessage['meeting_id'] = $meeting_id;
        $meeting = new MeetingController();
        $meeting->sendNotification($dataMessage);
    }
    /**
     * Rechaza una invitacion a un reunion programada enviada de parte de un compañia hacia un usuario candidato
     */
    public function denyMeeting(Request $request) {

        $meeting_id = $request->input('id_meeting');
        $message_id=$request->input('id_message');

        //DB::update('update meetings set state = 1 where id = :id', ['id'=>$meeting_id]); 
        DB::update('UPDATE messages_center set accepted = 2 where id = :id', ['id'=>$message_id]);

        $data = DB::select('SELECT A.user_id, A.company_id, U.name, J.title 
        from meetings A inner join users U 
        on A.user_id = U.id inner join job_apply JA 
        on A.job_apply_id = JA.id
        inner join jobs J on JA.job_id = J.id where A.id = :id;', ['id'=>$meeting_id]);

        DB::delete('DELETE FROM meetings WHERE id=:id', ['id'=>$meeting_id]);
        DB::delete('DELETE FROM recruiter_meetings WHERE meeting_id=:meeting_id', ['meeting_id'=>$meeting_id]); 

        $dataMessage['user_id'] = $data[0]->user_id;
        $dataMessage['company_id'] = $data[0]->company_id;
        $dataMessage['type'] = 1;
        $dataMessage['message'] = $data[0]->name." has deny the meeting regarding his application to the position ".$data[0]->title." position.";
        $dataMessage['receivedfrom'] = 0;
        $dataMessage['meeting_id'] = $meeting_id;
        $meeting = new MeetingController();
        $meeting->sendNotificationMeetingCanceled($dataMessage);
    }
    /**
     * Elimina un mensaje seleccionado
     */
    public function deleteMessage(Request $request) {
        $id = $request->input('id');
        return DB::delete('delete from messages_center where id = :id', ['id'=>$id]);
    }
    /**
     * 
     */
    public function replyMessage(Request $request) {
        $id = $request->input('id');
        $msg = $request->input('msg');

        if ($msg == null) {
            return response()->json(['status'=>'not message text']);
        }

        $mdata = DB::select('select id, user_id, company_id from messages_center where id = :id;', ['id'=>$id]);

        $dataMessage['user_id'] = $mdata[0]->user_id;
        $dataMessage['company_id'] = $mdata[0]->company_id;
        $dataMessage['type'] = 0;
        $dataMessage['message'] = $msg;
        $dataMessage['receivedfrom'] = 0;
        $dataMessage['meeting_id'] = null;
        $meeting = new MeetingController();
        $meeting->sendNotification($dataMessage);

        return response()->json(['status'=>'ok']);
    }
    /**
     * Envia un mensaje a un candidato desde en una compañia o un reclutador
     */
    public function sendMessageToCandidate(Request $request) {

        if (Auth::guard('company')->check()) {
            $company_id= Auth::guard('company')->user()->id;
        } elseif(Auth::guard('recruiter')->check()) {
            $company_id= Auth::guard('recruiter')->user()->id_company;
        }
        
        $dataMessage['user_id'] = $request->input('user_id');
        $dataMessage['company_id'] = $company_id;
        $dataMessage['type'] = 0;
        $dataMessage['message'] = $request->input('msg');
        $dataMessage['receivedfrom'] = 1;
        $dataMessage['meeting_id'] = null;
        $meeting = new MeetingController();
        $meeting->sendNotification($dataMessage);

        $siteSetting = SiteSetting::findOrFail(1272);
        $user = DB::select(' select name, email from users where id = :id', ['id'=>$request->input('user_id')]);
        $company = DB::select(' select name from companies where id= :id', ['id' => $company_id]);
        $data['from_email'] = $siteSetting->mail_from_address;
        $data['from_name'] = $siteSetting->site_name;
        $data['to_email'] = $user[0]->email;
        $data['to_name'] = $user[0]->name;
        $data['subject'] = "Message from " . $company[0]->name;
        $data['message_txt'] = $request->input('msg');        
        $data['siteSetting'] = $siteSetting;

        Mail::send(new UserContactMail($data));

        return response()->json(['status'=>'ok']);
    }
}
