@if($totalResult > 0)
<ul id="searchList">
  @foreach ($searchresult as $result)

    <li id="{{$result['fname'].'_'.$result['lname'].'_'.$result['unique_id']}}">
      @if(!empty($result['piclink']))
        <img id="imgsrc_{{$result['unique_id']}}" src="{{$result['piclink']}}">
      @else  
        <img id="imgsrc_{{$result['unique_id']}}" src="/images/default.png">
      @endif  
        <p>
          {{$result['fname'].' '.$result['lname']}}
          @if(!empty($result['karmaProfileLink']))   
              <img src="/images/krmaicon.png" alt = "{{$result['fname']}}" title="{{$result['lname']}}">          
              <input type="hidden" value="1" id="karmauser_{{$result['unique_id']}}">
              <input type="hidden" value="{{$result['id']}}" id="karmaid_{{$result['unique_id']}}">
               <input type="hidden" value="{{$result['connection_id']}}" id="karmaconn_{{$result['unique_id']}}">
               <input type="hidden" value="{{$result['linkedinurl']}}" id="linkedinurl_{{$result['unique_id']}}">
          @else 
              <input type="hidden" value="0" id="karmauser_{{$result['unique_id']}}">  
              <input type="hidden" value="{{$result['connection_id']}}" id="karmaconn_{{$result['unique_id']}}">
          @endif
        </p>
        <span>{{KarmaHelper::stringCut($result['headline'],80)}}</span>
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
          var id = $('#karmaid_'+keyvalue).val();
          var connection_id = $('#karmaconn_'+keyvalue).val();
          var linkedinurl = $('#linkedinurl_'+keyvalue).val();

          var userName = fname+'+'+lname;
          var chkKarmaUser = $('#karmauser_'+keyvalue).val();
          var picSrc = $('#imgsrc_'+keyvalue).attr('src');


          $("#ReceiverDisp").html(' <div class="receiverNameDisp" > <img id="imgsrc" height="25" width="25" src="'+picSrc+'"><b style="">'+fname+' '+lname+'</b><input name="receiver_conn_id"  id="receiver_conn_id" type="hidden" value="'+connection_id+'"><input name="Receiverfname" id="Receiverfname" type="hidden" value="'+fname+'"><input name="receiver_id" type="hidden" value="'+id+'"><span onclick="removeNameRec()">x</span></div>')
          $('#searchReceiverKeyword').val('');                    
          
          $('div.searchReceiverresult').hide();

          $('#receiverImage').html("<a href='"+linkedinurl+"' target='_blank'><img  src='/images/linkdin.png' style='margin-top: 6px; width: 20px;''></a>"); 

          var check= $('#GiverDisp').html();
          if(check != ''){
            var Giverfname = $('#giverfname').val(); 
            $('#Introsubject').val(Giverfname+', could you please give good karma to '+fname);
              $("#returnpara").html('In return, '+fname+' will do the following -');
              $('.IntroContent').css('display','block');
          }
      });
  });
</script>