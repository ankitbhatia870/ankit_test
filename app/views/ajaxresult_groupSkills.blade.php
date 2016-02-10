@if($totalResult > 0)
  <ul id="searchList" class="newTab">
    @foreach ($searchresult as $result)
      <?php //$trimedName = trim(str_replace(' ', '-', $result['name']));?>
      <li id="{{$result['name']}}" >  
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
       
       // alert(name); alert(id);
         var url = "<?php echo URL::to('/')?>/searchUsers?searchUser="+name+"&searchOption=<?php echo $searchCat;?>";
        //var url = "<?php echo URL::to('/')?>"+'/group/'+id+'/'+name;
        window.location = url;
      });
  });
</script>