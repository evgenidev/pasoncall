$(document).ready(function(){
    window.addEventListener('touchstart', function() {

    // create empty buffer
    var buffer = myContext.createBuffer(1, 1, 22050);
    var source = myContext.createBufferSource();
    source.buffer = buffer;

    // connect to output (your speakers)
    source.connect(myContext.destination);

    // play the file
    source.noteOn(0);

}, false);
    var if_submitted=false;

    var record_time = 0;
    var record_int = null;
    var flag=false;
    $('#record').click(function(){
        flag=true;
        $('#google-div').hide();
        $('#transcription-div').hide();
        $('#record_time').show();
        Fr.voice.record();
        record_time = 0;
        $('#record').hide();
        $('#stop').show();
        show_timer();
        record_int = setInterval(function(){
            record_time++;
            show_timer();
        }, 1000);
    });

    function show_timer() {
        var minutes = Math.floor(record_time/60);
        var seconds = record_time - minutes * 60;

        $('#record_time').html(("0" + minutes).slice(-2)+' : '+("0" + seconds).slice(-2));
    }

    $('#stop').click(function(){
        Fr.voice.pause();
        $('#record_time').hide();
        clearInterval(record_int);
        var minutes = Math.floor(record_time/60);
        var seconds = record_time - minutes * 60;
        var time=("0" + minutes).slice(-2)+' : '+("0" + seconds).slice(-2);

        Fr.voice.export(function(url){
            $.post("/api/save_record", 
                { data: url,
                    time:time,
                  user_id: $('meta#user-id').attr('data-user-id'),
                 //_token: $('meta[name="csrf-token"]').attr('content') }, function(data){
                  _token: $('meta[name="_token"]').attr('content') }, function(data){
                $.post("/api/initial_transcription",
                    {
                        data:data
                    },function(data){
                var vars = $.parseJSON(data);
                $('#google-div').removeClass('hidden');
                $('#google-div').show();
                $('#g-vars ul').attr('record-id',vars[0]);
                $('#vars ul').attr('record-id',vars[0]);
                if(vars[1].length==0)
                {
                    $('#g-vars').html('<h2>No suggestions</h2><br/>');
                    $('button#accept').hide();
                    $('button#transcript').show();
                    // $('button#transcript').hide();
                    $('button#cancel').show();
                }
                else{
                for(i=0;i<1;i++){
                    $('#g-vars').html('<ul><li><input type="radio" selected hidden name="var" id="var'+i+'" transcription-id="'+vars[1][i][1]+'"><textarea id="var-textarea" class="form-control" readonly>'+vars[1][i][0]+'</textarea></li></ul>');
                }
                        $('button#accept').show();
        $('button#transcript').show();
        $('button#cancel').show();
            }
                    flag=false;

        $('#record').show();
            });

            });
            // $('button#accept-tr').attr('tr-id',vars[1][0][1]);
            // $('button#again').attr('tr-id',vars[1][0][1]);
            // $('button#decline-tr').attr('tr-id',vars[1][0][1]);
            $('#stop').hide();
            $('audio').attr('src', url);
            $('#audio').show();
        }, "base64");
    });
    $('button#cancel').click(function () {
        $('button#accept').hide();
        $('button#transcript').hide();
        $('button#cancel').hide();
        $.post('/api/cancel',
        {
            record_id:$('#vars ul').attr('record-id')
        },function(data)
        {
            window.location.href='/home';
        });
    });
    $('button#accept').click(function () {
                new Clipboard('.btn', {
            text: function() {
                return $('#var-textarea').html();
            }
            });
                $('button#accept').hide();
        $('button#transcript').hide();
        $('button#cancel').hide();
        $.post('/api/initial_transcription_accepted',{
            transcription_id:$('input[type="radio"]').attr('transcription-id')
        });
    });
    $('button#transcript').click(function () {
        $('button#accept').hide();
        $('button#transcript').hide();
        $('button#cancel').hide();
        $.post('/api/add_pending_record',
            {
                record_id:$('#vars ul').attr('record-id')
            });
    });
    var table_buffer='';
    $('body').on('click','button#transcribe-button',function(){
            $('#record_time').show();
            flag=true;
        record_time = 0;
        show_timer();
        record_int = setInterval(function(){
            record_time++;
            show_timer();
        }, 1000);
        table_buffer=$(this).parents('tr').html();
        $(this).parents('tr').remove();
        $.post('/api/processing_record',
            {
                record_id:$(this).attr('record')
            },function(data)
            {
                $('textarea[name="transcription"]').html(data['body']); 
            });
        $('button#sub').removeClass('hidden');
        $('textarea[name="transcription"]').removeAttr('hidden');
        $('div#audio').removeAttr('hidden');
        $('div#audio audio').attr('src',$(this).attr('record-id'));
        $('button#sub').attr('record',$(this).attr('record'));
    });
    $('button#sub').click(function () {
        if_submitted=true;
                $('#record_time').hide();
        clearInterval(record_int);
        if(record_time<300){
        $.post('/api/add_transcription_by_user',{
            body:$('textarea').val(),
            user_id:$('meta#user-id').attr('data-user-id'),
            record_id:$(this).attr('record')
        });
    } else
    {
        $.post('/api/timeout',{
            body:$('textarea').val(),
            user_id:$('meta#user-id').attr('data-user-id'),
            record_id:$(this).attr('record')
        });
    }
    })
    setTimeout(refresh,5000);
    function refresh()
    {
        if($('button#transcribe-button').length!=0)
        {
            if (flag==false){
            $.post('api/refresh',{
                user_id: $('meta#user-id').attr('data-user-id')
            },function(data)
                {
                                if (flag==false){
                    var str1='';
                    // var vars= $.parseJSON(data);
                    var vars= data;
                    for(i=0;i<vars[0].length;i++)
                    {
                        str1+='<tr><td>'+vars[0][i]["created_at"]+'</td><td>'+vars[0][i]["time"]+'</td><td><button class="btn btn-success" id="transcribe-button" record-id="'+vars[0][i]["filename"].substr(-19)+'" record="'+vars[0][i]["id"]+'" data-toggle="modal" data-target="#myModal" data-backdrop="static"> transcribe record #'+vars[0][i]["id"]+'</button></td><td>'+vars[0][i]["status"]+'</td></tr>';
                    }
                    $('#records-table').html(str1);
                    var str2='';
                    for(i=0;i<vars[1].length;i++)
                    {
                        if(vars[1][i]['status']=='new'){ vars[1][i]['status']='P' }
                        else if(vars[1][i]['status']=='accepted'){ vars[1][i]['status']='C' }
                        else if(vars[1][i]['status']=='declined'){ vars[1][i]['status']='R' }
                        else{ vars[1][i]['status']='S' }
                        str2+='<tr><td>'+vars[1][i]["id"]+'</td><td>'+vars[1][i]["body"]+'</td><td>'+vars[1][i]["time"]+'</td><td>'+vars[1][i]["status"]+'</td></tr>';
                    }
                    $('#transcriptions-table').html(str2);
                }});
        }
    }
        else {
            if (flag==false){
            $.post('/api/refresh_user',
                {
                    record_id:$('#vars ul').attr('record-id')
                },
                function(data){
                if(data!="zero"){
                                if (flag==false){
                    var vars = $.parseJSON(data);
                    if ( vars[0] != null ) {
                    $('#transcription-div').removeClass('hidden');
                    $('#transcription-div').show();
                    $('#latest-transcription-div').hide();
                    var str='';
                    str+=('<textarea class="form-control" readonly="" name="var" id="var" transcription-id="'+vars[0]['id']+'">'+vars[0]['body']+'</textarea>');
                    $('.btn').attr('tr-id',vars[0]['id']);
                    if(($('#vars ul').html() != str)&&(vars[0]['status']!='solved'))
                    {
                        $('button#accept-tr').show();
                       $('button#decline-tr').show();
                        $('button#again').show();
                    }
                    $('#vars ul').html(str);
                }
                    if( vars[1][0] != null ){
                        var str='';
                        for(i=0;i<vars[1].length;i++)
                        {
                            str+='<tr><td>'+vars[1][i]['id']+'</td><td><a href="/record/'+vars[1][i]['id']+'">view results for record '+vars[1][i]['id']+'</a></td></tr>';
                        }
                        if ($('table#users-records').html()!=str)
                        {
                            $('table#users-records').html(str);
                        }
                    }
            }
        }
        });
        }
    }
        setTimeout(refresh,5000);
    }

    $('button#accept-tr').click(function () {
        new Clipboard('.btn', {
            text: function() {
                return $('#var').html();
            }
            });
        $('button#accept-tr').hide();
        $('button#decline-tr').hide();
        $('button#again').hide();
        $.post('/api/initial_transcription_accepted',{
            transcription_id:$(this).attr('tr-id')
        }
        ,function(data)
        {
        });
    });
    $('button#decline-tr').click(function () {
        $('button#accept-tr').hide();
        $('button#decline-tr').hide();
        $('button#again').hide();
        $.post('/api/declined_transcription',
        {
            transcription_id:$(this).attr('tr-id')
        },function(data)
        {
        });
    });
    $('button#again').click(function () {
        $('button#accept-tr').hide();
        $('button#decline-tr').hide();
        $('button#again').hide();
        $.post('/api/declined_transcription',
        {
            transcription_id:$(this).attr('tr-id')
        },function(data)
        {
        });
        $.post('/api/add_pending_record',
            {
                record_id:$('#vars ul').attr('record-id')
            });
    });
    $('body').on('hidden.bs.modal',function () {
        $('audio#aud').stop();
        if($('#records-table').html().indexOf(table_buffer)!==-1){
        $('#records-table').append(table_buffer);}
        $('#record_time').hide();
        $('div#audio audio').attr('src','');
        $('textarea[name="transcription"]').html(''); 
        clearInterval(record_int);
        if (!if_submitted){
            $.post('/api/add_pending_record',
            {
                user_id: $('meta#user-id').attr('data-user-id'),
                record_id:$('button#sub').attr('record')
            });}
            if_submitted=false;
            flag=false;
        $('button#sub').attr('record','');
    });
});

