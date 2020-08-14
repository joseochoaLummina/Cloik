<?php

namespace App\Http\Controllers;

use Auth;
use App\CompanyMessage;
use App\ApplicantMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MeetingController extends Controller
{
    public function __construct() {        
    }

    /**
     * Funcion que carga la vista de las reuniones para un usuario
     */
    public function getUserMeetings(Request $request) {
        date_default_timezone_set('America/El_Salvador');
        $dateNow = date('Y-m-d', time());
        $meetings = DB::select('SELECT A.id, A.planned_date, A.planned_time, B.name, 
        B.location, B.logo from meetings A inner join companies B
        on A.company_id = B.id inner join messages_center C
		on C.meeting_id=A.id where A.planned_date >= :date and 
        A.user_id = :id_user and A.state = 0 and C.accepted=1 order by A.planned_date ASC', 
        ['date' => $dateNow, 'id_user'=>Auth::user()->id]);
        $meetings = collect($meetings);
        return view('user.meetings')
                    ->with('meetings', $meetings);
    }

    /**
     * Funcion que carga la vista de las reuniones para una compañia
     */
    public function getCompanyMeetings(Request $request) {
        date_default_timezone_set('America/El_Salvador');
        $dateNow = date('Y-m-d', time());
        $meetings = DB::select('SELECT A.id, A.planned_date, A.planned_time, B.image,
        B.name, B.email, D.title from meetings A inner join users B on 
        A.user_id = B.id inner join job_apply C on C.id = A.job_apply_id 
        inner join jobs D on C.job_id=D.id inner join messages_center E
		on E.meeting_id=A.id where A.company_id = :id_company 
        and A.state = 0 and E.accepted=1 order by A.planned_date ASC', 
        ['id_company'=>Auth::guard('company')->user()->id]);
        $meetings = collect($meetings);
        return view('company.meetings')
                    ->with('meetings', $meetings);
    }

    /**
     * Funcion que genera una sala para una reunion
     */
    public function generateRoom(Request $request) {
        $id=$request->input('id');
        $meet = DB::select('select user_id, company_id, planned_date, planned_time from meetings where id = :id', ['id'=> $id]);
        $meet = collect($meet)->toArray();
        $meet = $meet[0];
        $room = "C".$meet->company_id."U".$meet->user_id."M".$meet->planned_date."_".str_replace(":", "-", $meet->planned_time);
        
        DB::update('update meetings set salon = :room where id = :id', ['room'=>$room, 'id'=>$id]);
        return $room;
    }

    /**
     * genera una sala para la videollamada en una reunion
     */
    public function generateRoomIntern($id) {
        $meet = DB::select('select user_id, company_id, planned_date, planned_time from meetings where id = :id', ['id'=> $id]);
        $meet = collect($meet)->toArray();
        $meet = $meet[0];
        $room = "C".$meet->company_id."U".$meet->user_id."M".$meet->planned_date."_".str_replace(":", "-", $meet->planned_time);
        
        DB::update('update meetings set salon = :room where id = :id', ['room'=>$room, 'id'=>$id]);
        return $room;
    }
    
    /**
     * Funcion que carga la vista de la videollamada para una reunion
     */
    public function getMeetingCall(Request $request) {
        $meet_id = $request->input('id');
        $room = $this->getVideoCallRoomIntern($meet_id);
        return view('videocall')->with('room', $room)
                                ->with('id', $meet_id);
    }

    /**
     * Funcion que carga la vista de la videollamada para una reunion
     */
    public function getVideoCallRoom(Request $request) {
        $meet_id = $request->input('id');        
        $room = $this->getVideoCallRoomIntern($meet_id);
        return view('videocall')->with('room', $room)
                                ->with('id', $meet_id);
    }

    /**
     * retorna la sala de una reunion, en caso de que no se haya generado se crea una y se retorna
     */
    public function getVideoCallRoomIntern($meet_id) {
        $room = DB::select('select salon from meetings where id = :id', ['id'=>$meet_id]);
        $room = collect($room)->toArray();
        $room = $room[0]->salon;

        if ($room == null) {
            $room = $this->generateRoomIntern($meet_id);            
        }
        
        return $room;
    }

    /**
     * Funcion encargada de enviar mensajes internos sobre alguna reunion
     */
    public function sendNotification($data) {
        DB::insert('insert into messages_center (user_id, company_id, type, message, state, receivedfrom, meeting_id) values(:user_id, :company_id, :type, :message, 0, :receivedfrom, :meeting_id);', 
        ['user_id'=>$data['user_id'], 'company_id'=>$data['company_id'], 'type'=>$data['type'], 'message'=>$data['message'], 'receivedfrom'=>$data['receivedfrom'], 'meeting_id'=>$data['meeting_id']]);
    }

    /**
     * Funcion encargada de enviar mensajes internos sobre la cancelacion de alguna reunion
     */
    public function sendNotificationMeetingCanceled($data) {
        DB::insert('INSERT INTO messages_center (user_id, company_id, type, message, state, receivedfrom, meeting_id,accepted) values(:user_id, :company_id, :type, :message, 0, :receivedfrom, :meeting_id,2);', 
        ['user_id'=>$data['user_id'], 'company_id'=>$data['company_id'], 'type'=>$data['type'], 'message'=>$data['message'], 'receivedfrom'=>$data['receivedfrom'], 'meeting_id'=>$data['meeting_id']]);
    }
}