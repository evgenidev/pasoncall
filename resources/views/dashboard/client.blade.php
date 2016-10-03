<div class="col-md-10">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="content-box-header">
                                <div class="panel-title">Add new audio recored for transcription</div>
                            </div>
                            <div class="content-box-large box-with-header">
                                <div class="">
                                     <div class="panel-body">
                                         <div class="col-md-6">
                @if(!isset($record))
                    <a href="#" id="record"><i class="glyphicon glyphicon-record" style="font-size: 70px"></i></a>
                    <a href="#" id="stop"><i class="glyphicon glyphicon-stop" style="font-size: 70px"></i></a>
                    </br>
                    </br>
                    <div id="audio">
                        <audio controls src=""></audio>
                    </div>
                    <div id="record_time"></div>
                @else
                    </br>
                    </br>
                    <div id="audio">
                        <audio controls src="{!!substr($record->filename,-19)!!}"></audio>
                    </div>
                    <div id="record_time"></div>

                    @endif
                    </div>
                </div>   
                                    </div>  
                                    </div>
                                    </div>               

                        <div class="col-md-6">
                            <div class="content-box-header">
                                <div class="panel-title">Unaccepted records</div>
                            </div>
                            <div class="content-box-large box-with-header">
                              <div class="">
                    <!-- <div class="text-center "><h2> Unaccepted records </h2></div> -->
                        <table class="table" id="users-records">
                            @foreach ($records as $key => $rec)
                                <tr>
                                    <td>
                                        {!!$rec->id!!}
                                    </td>
                                    <td>
                                        <a href="/record/{!! $rec->id !!}">view results for record {!! $rec->id !!}</a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>


                                <br /><br />
                                <div class="row" id="user_tr"><ul></ul></div>
                            </div>
                        </div>
                    </div>
                    </div>
                <div class="row">
                <div class="col-md-12 hidden" id='google-div'>
                    <div class="content-box-header">
                        <div class="panel-title">Google transcription</div>
                    </div>
                    <div class="content-box-large box-with-header">
                        <div class="">
                                                <div id="g-vars">

                    </div>
                        <ul record-id="{!! isset($record) ?  $record->id : '' !!}"> 

                        </ul>
                        @if(!isset($record)||(isset($record)&&$record->status!='solved'))
                        <button class="btn btn-success" id="accept">accept</button>
                        <button class="btn btn-warning" id="transcript">transcribe</button>
                        <button class="btn btn-default" id="cancel">cancel</button>
                        @endif
                        </div>
                    </div>
                </div>
            </div>
            @if(isset($transcription)&&!empty($transcription))
                        <div class="row">
                <div class="col-md-12" id='latest-transcription-div'>
                    <div class="content-box-header">
                        <div class="panel-title">Last Transcription</div>
                    </div>
                    <div class="content-box-large box-with-header">
                        <div class="">
                                                <div id="vars">
                        <ul record-id="{!! isset($record) ?  $record->id : '' !!}"> 
                            <textarea class="form-control" readonly="" name="var" id="var" transcription-id="{!! $transcription->id !!}">{!! $transcription->body !!}</textarea>
                        </ul>
                        @if(!isset($record)||(isset($record)&&$record->status!='solved'))
                        <button class="btn btn-success" id="accept-tr" tr-id="{!! $transcription->id !!}">accept</button>
                        <button class="btn btn-warning" id="again" tr-id="{!! $transcription->id !!}">transcribe again</button>
                        <button class="btn btn-danger" id="decline-tr" tr-id="{!! $transcription->id !!}">reject</button>
                        @endif
                    </div>
                        </div>
                    </div>
                </div>
            </div>
                        @elseif((isset($transcription)&&empty($transcription))||(isset($record)&&!isset($transcription)))
                        <div class="row">
                <div class="col-md-12" id='latest-transcription-div'>
                    <div class="content-box-header">
                        <div class="panel-title">Last Transcription</div>
                    </div>
                    <div class="content-box-large box-with-header">
                        <div class="">
                                                <div id="vars">
                        <ul record-id="{!! isset($record) ?  $record->id : '' !!}"> 
                           <h2>No suggestions.</h2>
                        </ul>
                        @if(!isset($record)||(isset($record)&&$record->status!='solved'))
                        <button class="btn btn-warning" id="transcript">transcribe</button>
                        <button class="btn btn-default" id="cancel">cancel</button>
                        @endif
                    </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="row">
                <div class="col-md-12 hidden" id='transcription-div'>
                    <div class="content-box-header">
                        <div class="panel-title">User Transcription</div>
                    </div>
                    <div class="content-box-large box-with-header">
                        <div class="">
                                                <div id="vars">
                        <ul record-id="{!! isset($record) ?  $record->id : '' !!}"> 

                        </ul>
                        @if(!isset($record)||(isset($record)&&$record->status!='solved'))
                        <button class="btn btn-success" id="accept-tr">accept</button>
                        <button class="btn btn-warning" id="again">transcribe again</button>
                        <button class="btn btn-danger" id="decline-tr">reject</button>
                        @endif
                    </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            </div>