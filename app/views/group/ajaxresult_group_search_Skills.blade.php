@if($totalResult > 0)
  <ul id="searchList" class="newTab">
    @foreach ($searchresult as $result)
      <li id="{{$result['name']}}">     
          <p>
            {{$result['name']}}          
                <input type="hidden" value="{{$result['name']}}" id="{{$result['name']}}">          
          </p>        
       </li>     
    @endforeach
  </ul>
@endif
<script type="text/javascript">
  $(document).ready(function(){
      $('#searchList li').click(function(){
          var name = $(this).attr('id');
          var keyword = name;
          var optionVal = $('#searchGroupOption').val();
          var groupId = $('#group-id').val(); 
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
          //var url = "<?php echo URL::to('/')?>/searchUsers?searchUser="+name+"&searchOption=<?php echo $searchCat;?>";
          //window.location = url;
      });
  });
</script>