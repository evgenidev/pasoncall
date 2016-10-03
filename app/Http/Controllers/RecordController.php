<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Record;
use App\Transcription;
use Illuminate\Support\Facades\File;

class RecordController extends Controller
{
    public function save_record()
    {
    	$user_id=Input::get('user_id');
    	$url=Input::get('data');
        $time=Input::get('time');
    	$filename=public_path('wav/'.time().'.wav');
    	File::put($filename,file_get_contents($url));
    	$record=new Record();
    	$record->user_id=$user_id;
        $record->time=$time;
    	$record->filename=$filename;
    	$record->save();
    	/*
        // dd($record);
    	$stturl = "https://www.google.com/speech-api/v2/recognize?output=json&lang=en-us&key=".env('GOOGLE_KEY');
        $upload = file_get_contents($filename);
        //echo $upload;
        $data = array(
            "Content_Type"  =>  "audio/l16; rate=44100",
            "Content"       =>  $upload,
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $stturl);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array("Content-Type: audio/l16; rate=44100"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        curl_close($ch);
        // dd($result);
        $result = json_decode(str_replace(['{"result":[]}', 'bool(true)', 'int(1)'], ['', '', ''], $result));
*/

        // 2016/09/06 Update Dmitry
        $stturl = "https://speech.googleapis.com/v1beta1/speech:syncrecognize?key=".env('GOOGLE_KEY');
        $upload = file_get_contents($filename);        
        $data = array(
            "config"    =>  array(
                //"Content_Type"  =>  "audio/l16; rate=44100",                
                "encoding"      =>  "LINEAR16",
                "sampleRate"    =>  16000,
                "languageCode"  =>  "en-US",
                //"maxAlternatives"   =>  30                
            ),
            "audio"     =>  array(
                "content"       =>  $upload,
            )
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $stturl);
        // curl_setopt( $ch, CURLOPT_HTTPHEADER, array("Content-Type: audio/l16; rate=44100"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        curl_close($ch);
        dd($result);
        $result = json_decode(str_replace(['{"result":[]}', 'bool(true)', 'int(1)'], ['', '', ''], $result));        



        $vars = [];
        if (!empty($result->result[0])){
        foreach($result->result[0]->alternative as $var){
            $vars[] = $var->transcript;
        }
        }
        return json_encode([$record->id,$vars]);
    }
    public function add_pending_record()
    {
    	$record_id=Input::get('record_id');
    	$record=Record::findOrFail($record_id);
        if($record->status!='solved'){
    	$record->status='pending';
    	$record->save();
    }
    }
    public function refresh_user()
    {
    	$record_id=Input::get('record_id');
        $record=Record::findOrFail($record_id);
    	if(!is_null($record_id))
    	{
    		$transcriptions=Transcription::where('record_id',$record_id)->where('transcriptor_id','!=',null)->latest()->first();
            $records=Record::where('user_id',$record->user_id)->where('status','!=','solved')->latest()->get();
    		if(!empty($transcriptions)||!empty($records)){
                $response=array($transcriptions,$records);
    			return json_encode($response);
    		}else
    		{return ('zero');}
    	}
    	else
    	{
    		return('zero');
    	}
    }
    public function processing()
    {
        $record_id=Input::get('record_id');
        $record=Record::findorfail($record_id);
        if($record->status!='solved'){
        $record->status='processing';
        $record->save();}
        $tr=$record->trancriptions()->first();
        return($tr);
    }
        public function cancel()
    {
        $record_id=Input::get('record_id');
        $record=Record::findorfail($record_id);
        if($record->status!='solved'){
        $record->status='canceled';
        $record->save();}
        $tr=$record->trancriptions()->first();
        return($tr);
    }

}
