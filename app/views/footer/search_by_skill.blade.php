@extends('common.master')
@section('content')
<?php //print_r($skills);
$skill_count =0;
 ?>
    <section class="mainWidth profilepage clearfix">
        <div class="col-md-10 centralize">
        <!-- <div class="backlink clearfix">
                <a href="/dashboard" class="pull-right">Back to Karma Circle</a>
            </div> -->
            <div class="col-sm-12 profileDeatils">
                <div class="col-xs-12 pdding0">
                    <div class="row headMin">
                        <h2 class="pull-left">Skills & Expertise</h2>
                        <ul class="pull-right">
                            <?php
                            $azRange = range('A', 'Z');
                            foreach ($azRange as $letter)
                            {
                                $set = "getskill('$letter')";  
                                $url ='/directory/skills-'.strtolower($letter);
                             if($letter == strtoupper($alpha)) $class= 'active';else $class=""; 
                             echo  "<li class='".$class."' id=liskill-".$letter." ><a id=loadskill-".$letter." class='getskillo' href='".$url."'>".$letter."</a></li>"; 
                            }
                            ?>              
                        </ul>
                    </div>
                    <hr class="darkLine">
                    <div class="skill col-md-6 col-L">
                        @if(!empty($skills))
                        <ul class="grouplist" id="skilllist">  
                           @foreach ($skills as $element)
                            @if ($element['UserCount'] > '1') 
                           <li>
                                <a href="<?php echo URL::to('/').'/searchUsers?searchUser='.$element['name'].'&searchOption=Skills';?>"><label>{{$element['name']." (".$element['UserCount'].")"}}</label></a>  
                            </li>
                            <?php $skill_count++; ?>
                           @endif 
                          @endforeach
                        </ul>
                        @endif
                        @if($skill_count == 0)
                          No results found for "{{strtoupper($alpha)}}" in Skills. 
                        @endif
                    </div>

                    
                </div>
            </div>
        </div>
    </section>    
@stop