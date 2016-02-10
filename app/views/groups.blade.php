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
            <div class="col-md-12 clearfix listBox">
                <h4>{{$val->name}}</h4>
                <p>{{$val->description}}</p>
                <a target="_blank" href="searchUsers?searchUser={{$val->name}}&searchOption=Groups"><button class="btn btn-success pull-right" type="button">Members</button></a>
                <a target="_blank" href="/updateGroup"><button class="btn btn-success pull-right" type="button">Add</button></a>
            </div>
            @endforeach
        @endif

        
    </div>    
    </section>
    <!-- /Main colom -->
@stop
   
