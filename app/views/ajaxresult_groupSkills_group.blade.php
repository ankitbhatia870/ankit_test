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
        var name = $(this).attr('id');
        var id = $(this).attr('class');
       // alert(name); alert(id);
         //var url = "<?php echo URL::to('/')?>/searchUsers?searchUser="+name+"&searchOption=<?php echo $searchCat;?>";
        var url = "<?php echo URL::to('/')?>"+'/groups/'+name+'/'+id;
        window.location = url; 
      });
  });
</script>