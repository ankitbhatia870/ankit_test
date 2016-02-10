@if($totalResult > 0)
<ul id="searchList">
  @foreach ($searchresult as $result)

    <li id="{{$result['fname'].'_'.$result['lname'].'_'.$result['unique_id']}}">
      @if(!empty($result['piclink']))
        <img src="{{$result['piclink']}}">
      @else  
        <img src="/images/default.png">
      @endif  
        <p>
          {{$result['fname'].' '.$result['lname']}}
          @if(!empty($result['karmaProfileLink']))   
              <img src="/images/krmaicon.png" alt = "{{$result['fname']}}" title="{{$result['lname']}}">          
              <input type="hidden" value="1" id="karmauser_{{$result['unique_id']}}">
              <input type="hidden" value="{{$result['id']}}" id="karmaid_{{$result['unique_id']}}">
          @else 
              <input type="hidden" value="0" id="karmauser_{{$result['unique_id']}}">  
          @endif
        </p>
        <span>{{$result['headline']}}</span>
     </li>
  @endforeach
</ul>
@endif


<script type="text/javascript">
  $(document).ready(function(){
      $('#searchList li').click(function(){
          var listVal = $(this).attr('id');

          var name = listVal.split('_');

          var fname = name[0].toLowerCase();
          var lname = name[1].toLowerCase();
          var keyvalue   = name[2];
          var id = $('#karmaid_'+keyvalue).val();

          var userName = fname+'+'+lname;
          var chkKarmaUser = $('#karmauser_'+keyvalue).val();
          
          $('#searchKeyword').val(fname+' '+lname);
          $('#searchOption').val('People');

          if(chkKarmaUser != 1)
            var url = "<?php echo URL::to('/')?>/searchUsers?searchUser="+userName+"&searchOption=People";
          else 
            var url = "<?php echo URL::to('/')?>/profile/"+fname+"-"+lname+'/'+id;
         
          window.location = url;

      });
  });
</script>