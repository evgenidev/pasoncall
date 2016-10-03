<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Transcription;
use App\Record;
use Illuminate\Support\Facades\Input;

class TranscriptionController extends Controller
{
    public function initial_transcription()
    {
    	$data=Input::get('data');
    	$data=json_decode($data,1);
    	// dd($data);
        foreach ($data[1] as $key => $transcription) {
        	$t=new Transcription();
        	$t->record_id=$data[0];
        	$t->status='google';
        	$t->body=$transcription;
        	$t->save();
        	$data[1][$key]=array($data[1][$key],$t->id);
        }
        return json_encode($data);
    }
    public function initial_transcription_accepted()
    {
        $transcription_id=Input::get('transcription_id');
        $transcription=Transcription::findorfail($transcription_id);
        $transcription->status='accepted';
        $transcription->save();
        $record=Record::findorfail($transcription->record_id);
        $record->status='solved';
        $record->save();
        return(['success'=>'1']);
    }
    public function add_transcription_by_user()
    {
    	$input=Input::except('_token');
        $record=Record::findorfail($input['record_id']);
        if($record->status != 'solved'){
        $record->status='hasnewtrans';
        $record->save();
    	$tr=new Transcription();
    	$tr->body=$input['body'];
    	$tr->transcriptor_id=$input['user_id'];
    	$tr->record_id=$input['record_id'];
        $tr->time=Record::findorfail($input['record_id'])->time;
    	$tr->save();
    	return(['success'=>'1']);}
        else
        {
            return(['error'=>'record is already solved']);
        }
    }
    public function decline_transcription()
    {
    	$id=Input::get('transcription_id');
    	$tr=Transcription::findorfail($id);
    	$tr->status='declined';
    	$tr->save();
    	return (['success'=>1]);
    }
    public function refresh()
    {
    	$user_id=Input::get('user_id');
        $records = Record::where('status','pending')->get();
        $transactions= Transcription::where('transcriptor_id',$user_id)->get();
    	return([$records,$transactions]);
    }
    public function timeout()
    {

        $input=Input::except('_token');
        $record=Record::findorfail($input['record_id']);
        if($record->status != 'solved'){
        $record->status='hasnewtrans';
        $record->save();
        $tr=new Transcription();
        $tr->body=$input['body'];
        $tr->transcriptor_id=$input['user_id'];
        $tr->record_id=$input['record_id'];
        $tr->time=Record::findorfail($input['record_id'])->time;
        $tr->status='timeout';
        $tr->save();
        return(['success'=>'1']);
        }
        else
        {
            return (['error'=>'record is already solved']);
        }
    }
        public function add_empty_transcription()
    {
        $input=Input::except('_token');
        $record=Record::findorfail($input['record_id']);
        if($record->status != 'solved'){
        $record->status='pending';
        $record->save();
        $tr=new Transcription();
        $tr->body='';
        $tr->transcriptor_id=$input['user_id'];
        $tr->record_id=$input['record_id'];
        $tr->time=Record::findorfail($input['record_id'])->time;
        $tr->status='P';
        $tr->save();
        return(['success'=>'1']);}
        else
        {
            return(['error'=>'record is already solved']);
        }
    }
}
