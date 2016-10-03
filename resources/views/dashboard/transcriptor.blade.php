<div class="col-md-10">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="content-box-header">
                                <div class="panel-title">Transcription Queue</div>

                                <div class="panel-options">
                                    <a href="#" data-rel="collapse"><i class="glyphicon glyphicon-refresh"></i></a>
                                    <a href="#" data-rel="reload"><i class="glyphicon glyphicon-cog"></i></a>
                                </div>
                            </div>
                            <div class="content-box-large box-with-header">
                            <div class="row">
                            <div class="col-md-12">
                                        <table class="table" id="records-table">                      
                                    @foreach($records as $record)
                                    <tr>
                                    <td>{!! $record->created_at !!}</td>
                                    <td>
                                        {!!$record->time!!}                     
                                        </td>
                                        <td>
                                        <button class='btn btn-success' id='transcribe-button' record-id="{!!substr($record->filename,-19)!!}" record="{!! $record->id !!}" data-toggle="modal" data-target="#myModal"> transcribe record #{!!$record->id!!}</button>
                                        </td>
                                        <td>
                                        {!!$record->status!!}                     
                                        </td>
                                      </tr>  
                                    @endforeach
                                    </table>
                                    <button id="transcribe-button" class="hidden"></button>
                                </div>
                            </div>

                                <br /><br />
                            </div>
                        </div>
                                                        <div class="col-md-6">
                                <div class="content-box-header">
                                <div class="panel-title">Users transcriptions</div>
                                </div>
                                    <div class="content-box-large box-with-header">
                                            <table class="table table-bordered" id="transcriptions-table">
                                            @foreach (Auth::user()->transcription()->get() as $key=>$transcription)
                                                <tr>
                                                    <td>{!!$transcription->id!!}</td>
                                                    <td>{!!$transcription->body!!}</td>
                                                    <td>{!!$transcription->time!!}</td>
                                                    <td>@if ($transcription->status=='new') P 
                                                        @elseif($transcription->status=='declined') R 
                                                        @elseif($transcription->status=='accepted') C
                                                        @else F 
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </table>
                                    </div>
                                </div>
                    </div>
                </div>
</div>

<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
                          <div id="record_time"></div>
                    <div  class="row text-center" id="audio" hidden>
                        <audio controls src="" id="aud"></audio>
                    </div>
                    <div class="row text-center">
                    <label>Transcription text</label>
                    <textarea rows=5 class="form-control" name="transcription" hidden></textarea>
                    
                    <br/>
                    <button class="btn btn-success hidden" id='sub' data-dismiss="modal">Propose a transcription</button>
                                 </div>
                                 <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<div id="kostil"></div>