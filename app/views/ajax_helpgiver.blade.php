<?php // echo "<pre>";print_r($giver_Info);echo "</pre>"; die; ?>

@if(!empty($giver_Info))                     
<div class="col-sm-1 newimgbox"> 
     @if ($giver_Info->piclink == "" || $giver_Info->piclink == 'null')
        <img alt="" src="/images/default.png" > 
    @else
        <img src="{{$giver_Info->piclink}}" > 
    @endif
    <!-- List popup -->
                <div class="noteBox tabtxt listpopUp w280">
                    <div class="col-xs-4">                                                        
                        @if ($giver_Info->piclink == "" || $giver_Info->piclink == 'null')
                            @if (isset($giver_Info->email))
                                <a href="/profile/<?php echo strtolower($giver_Info->fname.'-'.$giver_Info->lname);?>/{{$giver_Info->id}}">
                                 <img alt="" src="/images/default.png" >
                                </a>
                            @else
                                <img alt="" src="/images/default.png" >
                            @endif
                        @else
                            @if (isset($giver_Info->email))
                                <a href="/profile/<?php echo strtolower($giver_Info->fname.'-'.$giver_Info->lname);?>/{{$giver_Info->id}}">
                                    <img src="{{$giver_Info->piclink}}" >
                                </a>
                            @else
                                 <img src="{{$giver_Info->piclink}}" >
                            @endif
                        @endif
                    </div>
                    <div class="col-sm-8 col-xs-7">
                        @if (isset($giver_Info->email))
                            <a href="/profile/<?php echo strtolower($giver_Info->fname.'-'.$giver_Info->lname);?>/{{$giver_Info->id}}">
                                <h4>{{$giver_Info->fname." ".$giver_Info->lname}}</h4>
                            </a> 
                        @else
                            <h4>{{$giver_Info->fname." ".$giver_Info->lname}}</h4>
                        @endif 
                        <p>{{KarmaHelper::stringCut($giver_Info->headline,80)}}</p>
                        <p>{{$giver_Info->location}}</p>
                    </div> 
                    <div class="clr"></div>
                    <div class="borderPic">
                    <ul>
                        <li><a href="{{$giver_Info->linkedinurl}}" target="_blank"><img alt="" src="/images/linkdin.png"></a></li>
                        @if (isset($giver_Info->email))
                        <li>
                        <a href="/profile/<?php echo strtolower($giver_Info->fname.'-'.$giver_Info->lname);?>/{{$giver_Info->id}}"><img alt="" src="/images/krmaicon.png"></a>
                        <span>{{$giver_Info->karmascore}}</span>
                        </li>
                        @endif
                    </ul>
                    </div> 
                   
                </div>
                <!-- List popup -->
</div>
@endif