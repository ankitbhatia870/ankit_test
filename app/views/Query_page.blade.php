@extends('common.master')
@section('content')
<?php  
$get_permalink = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; 
?>
    <section class="mainWidth">
        <div class="col-lg-10 col-md-10 col-sm-12 centralize pdding0">
        <!-- <div class="backlink pull-right clearfix">
            <a href="/karmaqueries">Back to Karma Circle</a>

        </div> -->
         <div class="clr"></div>
        <div class="clearfix scheduleBtn"> 
            
            
            @if(Auth::check())
                @if($question->queryStatus != 'closed' && $question->user_id->id == Auth::user()->id)
                    {{ Form::open(array('url' => 'closeQuestion' , 'method' => 'post','onsubmit'=>'return closecheck()')) }}
                    {{Form::hidden('question_id', $question->id)}}                  
                    {{Form::submit('Close',array('class'=>'btn btn-warning querypage pull-right'));}}
                    {{Form::close()}} 
                @elseif( $question->queryStatus == 'closed')
                <div  class="btn btn-warning querypage pull-right  btn btn-success toggleBtn pending  btn-warning qrypag">Closed</div>
                @elseif($question->queryStatus == 'open' && $question->answered =='0' && $question->user_id->id != Auth::user()->id )              
                    {{ Form::open(array('url' => 'submitforhelp' , 'method' => 'post','onsubmit'=>'hidesubmit()')) }}
                    {{Form::hidden('question_id', $question->id)}}
                    {{Form::hidden('giver_id', Auth::user()->id)}}
                    {{Form::submit('Help',array('class'=>'btn btn-success minBtn pull-right'));}}
                    {{Form::close()}} 
                @endif
            @else
                @if( $question->queryStatus == 'closed')
                    <div  class="btn btn-warning querypage pull-right  btn btn-success toggleBtn pending  btn-warning qrypag">Closed</div>
                @elseif($question->queryStatus == 'open' && $question->answered =='0')               
                    {{ Form::open(array('url' => 'submitforhelp' , 'method' => 'post','onsubmit'=>'hidesubmit()')) }}
                    {{Form::hidden('question_id', $question->id)}}                  
                    {{Form::submit('Help',array('class'=>'btn btn-success minBtn pull-right'));}}
                    {{Form::close()}} 
                @endif
            @endif        

            
        </div> 
          <div class="clr"></div>
            <div class="col-sm-12 centralize clearfix pull-left question">
                <div class="col-sm-12  borderContainer">
                    <div class="col-sm-12 col-md-1 minImg">
                        @if ($question->user_id->piclink == "" || $question->user_id->piclink == 'null')
                            <img alt="" src="/images/default.png" >
                        @else
                            <img src="{{$question->user_id->piclink}}" >
                        @endif

                    </div>
                    <div class="col-sm-12 col-md-11">
                        <h4>{{$question->subject}}</h4>
                        <div>
                            <span class="simTxt">{{date('F d, Y', strtotime($question->created_at))}}</span>
                            <ul class="grouplist tag">
                                 @if($question->skills != '')
                                    @foreach ($question->skills as $skills)
                                        <a href="<?php echo URL::to('/').'/searchUsers?searchUser='.$skills->name.'&searchOption=Skills';?>"><li>{{$skills->name}}</li></a>
                                    @endforeach  
                                @endif     
                            </ul>
                             
                        </div>   
                            <p class="lighttxt">{{$question->description}}</p>
                    </div>  
                </div>


                <div class="clr"></div>
                   
                    @if(!$question->giver_Info->isEmpty()) 
					<h2 class="helpHeading">People willing to help</h2>
                    @foreach ($question->giver_Info as $giver_Info)
                        <div class="col-sm-4 col-xs-12 noteBox tabtxt">
                            <div class="tabtxt listpopUp">
                                <div class="col-xs-4">
                                    @if ($giver_Info->user_id->piclink == "" || $giver_Info->user_id->piclink == 'null')
                                        <img alt="" src="/images/default.png" >
                                    @else
                                        <img src="{{$giver_Info->user_id->piclink}}" >
                                    @endif
                                </div>
                                <div class="col-sm-8 col-xs-7">
                                   <h4>{{$giver_Info->user_id->fname." ".$giver_Info->user_id->lname}}</h4>
                                    <p>{{KarmaHelper::stringCut($giver_Info->user_id->headline,80)}}</p>
                                    <p>{{$giver_Info->user_id->location}}</p>
                                </div>
                                <div class="clr"></div>
                                <div class="borderPic">
                                    <ul>
                                        <li><a href="{{$giver_Info->user_id->linkedinurl}}" target="_blank"><img alt="" src="/images/linkdin.png"></a></li>
                                        @if (isset($giver_Info->user_id->email))
                                        <li>
                                        <a href="/profile/<?php echo strtolower($giver_Info->user_id->fname.'-'.$giver_Info->user_id->lname);?>/{{$giver_Info->user_id->id}}"><img alt="" src="/images/krmaicon.png"></a>
                                        <span>{{$giver_Info->user_id->karmascore}}</span>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                                @if(Auth::check())
                                     @if($giver_Info->user_id->id != $CurrentUser->id)
                                     <a href="/CreateKarmaMeeting/{{$giver_Info->user_id->id}}"><button type="button" class="btn btn-success btnicon meeting minwidthBtn minbtnN">Request Meeting</button></a>
                                    @endif
                                @else
                                    <a href="/CreateKarmaMeeting/{{$giver_Info->user_id->id}}"><button type="button" class="btn btn-success btnicon meeting minwidthBtn minbtnN">Request Meeting</button></a>
                                @endif
                          </div>
                        </div>
                    @endforeach
                    @endif               
            </div>
        </div>    
    </section>
    <!-- /Main colom -->
<script type="text/javascript">
    function  closecheck () {
        if (confirm("Are you sure that you want to close this question?") == true) {
                return;
            } else {
               return false;
            }
    }
</script>

@stop