@extends('common.master')
@section('content')

    <!-- Main colom -->
    <section class="mainWidth profilepage clearfix">
    <div class="col-md-10 centralize clearfix">
        <div class="col-md-12 pZero centralize  ">
            <h4>Karma Groups</h4>
            <p>KarmaGroups allow you to run a peer-to-peer knowledge sharing platform for a group. Members can request meetings from other group members and can also post queries. In response to queries, group members can offer to help via a KarmaMeeting.
                <p>
            <p>Here are some of the groups that you could add yourself to. If you would like to create a group, please send us an email at 
            <a href="mailto:help@KarmaCircles.com"><?php echo "help@KarmaCircles.com"?></a></p>
        </div> 
        @if(!empty($group)) 
            @foreach($group as $val)   
            <?php
                    $trimedName =  strtolower(trim(str_replace(' ', '-', $val->name)));
                ?>
            <div class="col-md-12 clearfix listBox">
                <a href= "/groups/{{$trimedName}}/{{$val->id}}" class="GroupBox">
                <h4>{{$val->name}}</h4>
                <p>{{$val->description}}</p> 
                <!-- <a href= "/groups/{{$val->id}}/{{$val->name}}"><button class="btn btn-success pull-right" type="button">View</button></a> -->
                <a  href="/groups/{{$trimedName}}/{{$val->id}}"><button class="btn btn-success pull-right" type="button">Members</button></a>
                <?php $inGroup = '';  if(!empty($user_all_groups)) $inGroup = in_array($val->id, $user_all_groups) ;?>
                @if(!empty($CurrentUser))
                    @if($inGroup == 1)
                     <div id="leave-{{$val->id}}"><button class="btn btn-success pull-right gbutton" onclick="updategroup({{$val->id}},'leave');" id="leave-{{$val->id}}" type="button">Leave</button></div>
                    @else
                    <div id="join-{{$val->id}}"><button id="join-{{$val->id}}" class="btn btn-success pull-right gbutton" onclick="updategroup({{$val->id}},'join');" type="button">Join</button> </div>
                    @endif 
                @else 
                    <button class="btn btn-success pull-right" onclick="openboxmodel('GroupLeave','/updateGroup');" type="button">Join</button> 
                @endif
            </a>
            </div>
            @endforeach
        @endif
    </div>    
    <input type="hidden" value="" id="gselect">
    <input type="hidden" value="list" id="gpage">
    <input type="hidden" value="" id="gaction"> 
    <div class="modal" style="display:none" id="GroupLeave">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button aria-label="Close" onclick="modelClose('GroupLeave');" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Please sign in!</h4>
          </div>
          <div class="modal-body">
            <p>You need to be signed in to perform this action. Please sign in using Linkedin.</p>
          </div>
          <div class="modal-footer">
            <a href="" id="popupUrl"><button data-dismiss="modal" class="btn btn-default linkfullBTN newBluBtn pull-right" type="button">Sign in with Linkedin</button></a>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div>

    <div class="modal" style="display:none" id="oneGroup">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button aria-label="Close" onclick="modelClose('oneGroup');" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">&times;</span></button>
            <h4>You have to be a part of atleast one group.</h4>
          </div>
          <div class="modal-footer">  
              <button data-dismiss="modal" class="btn btn-default linkfullBTN gpBtn pull-right" type="button" onclick="modelClose('oneGroup');">Close</button> 
            </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div>

    <div class="modal" style="display:none" id="UpdateGroup">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button aria-label="Close" onclick="modelClose('UpdateGroup');" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Are you sure?</h4>
            </div>
            <input type="hidden" id="group-id" value="">
            <div class="modal-body group-body" >
              <p>Please join this group only if you belong to this group. If you don't belong to this group, your membership may be cancelled. Are you sure that you want to join?</p>
            </div>
            <div class="modal-footer">  
             
              <button data-dismiss="modal" class="btn btn-default linkfullBTN gpBtn pull-right" id = "noButton" type="button" onclick="cancelcalled();">No</button> 
               <button data-dismiss="modal" class="btn btn-default linkfullBTN gpBtn pull-right" id = "yesButton" type="button" onclick="okcalled();">Yes</button>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog --> 
      </div>
    </section>
    <!-- /Main colom -->
    <SCRIPT TYPE="text/javascript">

    </SCRIPT>
@stop
   
