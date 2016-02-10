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

          var fname = name[0];
          var lname = name[1];
          var keyvalue   = name[2];
          var id = $('#karmaid_'+keyvalue).val();

          var userName = fname+'+'+lname;
         
          
        $('#searchGroupKeyword').val(fname+' '+lname);
        $('#searchGroupOption').val('People');

        
        var keyword = fname+' '+lname;
        var optionVal = $('#searchGroupOption').val();
        var groupId = $('#group-id').val();

        if(keyword == ''){
            return false;
        }
        else{
            $.ajax({ 
                    url: '<?php echo URL::to('/');?>/GroupSearch',
                    type: "post", 
                    cache:false,
                    data : { keyword : keyword, groupId : groupId, optionVal : optionVal },
                    async: true, 
                    beforeSend: function(){
                    },
                    complete: function(){
                    },
                    success: function(data){
                        $('div#searchgroupresult').hide(); 
                        $('div#searchgroupresult').removeAttr('style');
                        $('div#searchgroupdata').removeClass('displayNone');
                        $('#searchgroupdata').html(data);
                    }, 
                    error: function(){
                    }           
                   });

        }

          //window.location = url;

      });
  });
</script>