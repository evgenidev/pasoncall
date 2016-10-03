<?php

namespace App\Http\Controllers;

use App\Record;
use Auth;
use App\Transcription;
use App\User;
use Illuminate\Http\Request;

class IndexController extends Controller
{

    public function index() {
        return view('index', $this->data);
    }

    public function save_record(Request $request){
        $user = Auth::user() ? Auth::user()->id : 0;

        $record = Record::create([
            'user_id' => $user,
        ]);
        file_put_contents(public_path('wav/'.$record->id.'.wav'), base64_decode(str_replace('data:audio/wav;base64,', '', $request->data)));

        $stturl = "https://www.google.com/speech-api/v2/recognize?output=json&lang=en-us&key=".env(GOOGLE_KEY)."&client=chromium";
        $filename = public_path('wav/'.$record->id.'.wav');
        $upload = file_get_contents($filename);
        //echo $upload;
        $data = array(
            "Content_Type"  =>  "audio/l16; rate=44100",
            "Content"       =>  $upload,
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $stturl);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array("Content-Type: audio/l16; rate=16000"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        curl_close($ch);
        dd($result);
        $result = json_decode(str_replace(['{"result":[]}', 'bool(true)', 'int(1)'], ['', '', ''], $result));
        $vars = [];

        foreach($result->result[0]->alternative as $var){
            $vars[] = $var->transcript;
        }

        echo json_encode($vars);

        die;
    }
    public function show($id)
    {
         $users = User::all();
         // $transcription=Transcription::where('record_id',$id)->where('transcriptor_id','!=',null)->latest()->first();
         // if(empty($transcription))
         // {
            $transcription=Transcription::where('record_id',$id)->latest()->first();
         // }
         // else
         // {
         //    $transcription=null;
         // }
        $records = Record::where('user_id',Auth::user()->id)->where('status','!=','solved')->latest()->get();
        $record=Record::findorfail($id);
        if($record->status=='solved'||$record->status=='canceled')
            { return redirect('/home');
    }else{
        return view('home',compact('record','users','records','transcription'));}
    }
}