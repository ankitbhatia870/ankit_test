@if($totalResult > 0)
<ul id="searchList">
  @foreach ($searchresult as $result)
    <li id="skill_{{$result['id']}}">
        <p>
          {{$result['name']}}
              <input type="hidden" value="{{$result['name']}}" id="skillname_{{$result['id']}}">          
              <input type="hidden" value="{{$result['id']}}" id="skillid_{{$result['id']}}"> 

        </p>       
     </li>
  @endforeach
</ul>
@endif
<script type="text/javascript">
  $(document).ready(function(){
      $('#searchList li').click(function(){

            var skillLength = $('.skills:checked').length;
            if(skillLength > 2){
                alert('You can not pick more than 3 skills.Please remove one skill and select again.');
                $('#searchskill').val('');
                $('div.searchReceiverresult').hide();
                return false;
            }
          var listVal = $(this).attr('id');
          var fullid = listVal.split('_');
          var id = fullid[1];
          var name = $('#skillname_'+id).val();
          var check = '0';
            var skillsIds = $("#SkillDisp input:checkbox:checked").map(function(){
            return $(this).val();
            }).get(); // <----
            $.each(skillsIds, function(index, val) {
               if(val == id){
                  check ='1';
               }
            });
            if(check == '1'){
              $('#searchskill').val('');
              $('div.searchReceiverresult').hide();
              return false;
            }
           $("#SkillDisp").append(' <div class="receiverNameDisp skilldisp_'+id+'" ><b style="">'+name+'</b><input type="checkbox" class = "skills" id="'+id+'" value = "'+id+'" checked name="skillTags[]">      <span onclick="removeskill('+id+')">x</span></div>')
          $('#searchskill').val('');
          $('div.searchReceiverresult').hide();
      });
  }); 
</script>