<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transcription extends Model
{
    public function transcriptor()
    {
    	if( !is_null($this->transcriptor_id))
    	{
    		return $this->hasOne('\App\User','transcriptor_id');
    	}
    	else
    	{
    		return null;
    	}
    }
    public function parent_record()
    {
        return $this->belongsTo('\App\Record')->first();
    }
}
