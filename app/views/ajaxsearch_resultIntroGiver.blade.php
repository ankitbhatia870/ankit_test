@if($totalResult > 0)
<ul id="searchList">
  @foreach ($searchresult as $result)

    <li id="{{$result['fname'].'_'.$result['lname'].'_'.$result['unique_id']}}">
      @if(!empty($result['piclink']))
        <img id="Giverimgsrc_{{$result['unique_id']}}" src="{{$result['piclink']}}">
      @else  
        <img id="Giverimgsrc_{{$result['unique_id']}}" src="/images/default.png">
      @endif  
        <p> 
          {{$result['fname'].' '.$result['lname']}}
          @if(!empty($result['karmaProfileLink']))   
              <img src="/images/krmaicon.png" alt = "{{$result['fname']}}" title="{{$result['lname']}}">          
              <input type="hidden" value="1" id="Giverkarmauser_{{$result['unique_id']}}">
              <input type="hidden" value="{{$result['id']}}" id="Giverkarmaid_{{$result['unique_id']}}">  
               <input type="hidden" value="{{$result['connection_id']}}" id="Giverkarmaconn_{{$result['unique_id']}}">      
                <input type="hidden" value="{{$result['linkedinurl']}}" id="linkedinurlgiver_{{$result['unique_id']}}">      
          @else 
               <input type="hidden" value="{{$result['connection_id']}}" id="Giverkarmaconn_{{$result['unique_id']}}">
              <input type="hidden" value="0" id="Giverkarmauser_{{$result['unique_id']}}">  
              <input type="hidden" value="{{$result['linkedinurl']}}" id="linkedinurlgiver_{{$result['unique_id']}}">  
          @endif
        </p>
        <span>{{KarmaHelper::stringCut($result['headline'],40)}}</span>
     </li>
  @endforeach
</ul>
@endif
  
<script type="text/javascript">
  $(document).ready(function(){
      $('#searchList li').click(function(){
          var listVal = $(this).attr('id'); 
          var name = listVal.split('_');
          var fname = name[0];
          var lname = name[1];
          var keyvalue   = name[2];
          var id = $('#Giverkarmaid_'+keyvalue).val();
          var connection_id = $('#Giverkarmaconn_'+keyvalue).val();
          var userName = fname+'+'+lname;
          var chkKarmaUser = $('#Giverkarmauser_'+keyvalue).val();
          var picSrc = $('#Giverimgsrc_'+keyvalue).attr('src');
          var linkedinurl = $('#linkedinurlgiver_'+keyvalue).val();
          var checkMsgLimit = "<?php echo $checkMsgLimit?>";
           $("#GiverDisp").html(' <div class="receiverNameDisp" > <img id="imgsrc" height="25" width="25" src="'+picSrc+'"><b style="">'+fname+' '+lname+'</b><input name="giver_conn_id"  id="giver_conn_id" type="hidden" value="'+connection_id+'"><input name="giverfname"  id="giverfname" type="hidden" value="'+fname+'"><input name="giver_id" type="hidden" value="'+id+'"><span onclick="removeNameGiver()">x</span></div>') 
          $('#searchGiverKeyword').val('');
        
          $('div.searchGiverresult').hide();

          if(chkKarmaUser == 0)
            {
               if(checkMsgLimit == 0) 
                {
                  $('#Limitboxmsg').html("Please specify the email address of "+fname+" "+lname+" to send this message.");
                  openboxmodel('LimitBox','');
                  $("#giverMailboxRequired input").attr("required", "true");
                  $('#giverMailboxRequired').css("display","block"); 
                }
               else $('#giverMailbox').css("display","block");
            }
          else
          {
            $('#giverMailbox').css("display","none"); 
            $('#giverMailboxRequired').css("display","none"); 

          }

          $('#giverImage').html("<a href='"+linkedinurl+"' target='_blank' ><img  src='/images/linkdin.png' style='margin-top: 6px; width: 20px;''></a>");
          $('#giver_email').attr('placeholder','Please specify email address of '+fname+' '+lname); 



          var check= $('#ReceiverDisp').html();
          if(check != ''){
             var Receiverfname = $('#Receiverfname').val(); 
            $('#Introsubject').val(fname+', could you please give good karma to '+Receiverfname);
            $("#returnpara").html('In gratitude, '+Receiverfname+' will do the following -');
            $('.IntroContent').css('display','block');
          }
      });
  }); 
</script>