<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    public function trancriptions()
    {
    	return $this->hasMany('\App\Transcription')->get();
    }
    public function new_transcriptions()
    {
    	return $this->hasMany('\App\Transcriptions')->where('status','new')->get();
    }
}
