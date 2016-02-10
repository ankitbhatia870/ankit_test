@if($totalResult > 0)
  <ul id="searchList" class="newTab">
    @foreach ($searchresult as $result)
     <?php $trimedName = strtolower(trim(str_replace(' ', '-', $result['name'])));?>
      <li id="{{$trimedName}}" class="{{$result['id']}}">     
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
          //$('#searchGroupKeyword').val(' '); 
          $('#searchgroupresult').hide();
          var name = $(this).attr('id');
          var id = $(this).attr('class');
          var keyword = name;
          $('#group-searchd-id').val(id); 
          var optionVal = $('#searchGroupOption').val(); 
          var groupId = $('#group-id').val();

          //var url = "<?php echo URL::to('/')?>"+'/groups/'+name+'/'+id;
          //window.location = url; 
           $.ajax({ 
                    url: '<?php echo URL::to('/');?>/GroupSearch',
                    type: "post", 
                    cache:false,
                    data : { keyword : keyword, groupId : groupId, optionVal : optionVal,group_searchId : id, },
                    async: true, 
                    beforeSend: function(){
                    },
                    complete: function(){
                     $('#searchgroupdata').show();
                    },
                    success: function(data){
                        $('#searchgroupdata').html(data);
                    }, 
                    error: function(){
                    }           
                   });
      });
  });
</script>