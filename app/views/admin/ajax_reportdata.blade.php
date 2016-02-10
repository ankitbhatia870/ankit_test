<?php $linkedUrl = "";?>  
<section class="mainWidth">
        <div class="col-md-10 centralize profilepage pdding0 clearfix note">
                <div class="tabbed">    
                <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                      <li class="active"><a href="#home" role="tab" data-toggle="tab">KarmaNotes</a></li>
                      <li><a href="#profile" role="tab" data-toggle="tab">Meetings</a></li>
                   
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <!--1st-->
                        <div id="home" class="tab-pane meetingsentresult active">    
                            @if(!empty($karmaTrail)) 
                            <div class="UserDetail" >
                                <table class="table">
                                    <thead>
                                        <tr>
                                        <th>#</th>
                                        <th>Meeting Id</th>
                                        <th>Receiver</th>
                                        <th>Giver</th>
                                        <th>Created</th>
                                        <th>Note</th>
                                        </tr> 
                                    </thead>
                                <tbody id="userData">
                                        @foreach ($karmaTrail as $key => $element)  
                                        @if(isset($element['user_idreceiver']['fname']) && isset($element['user_idgiver']['fname']))
                                            <tr>                 
                                                <td>{{$key+1}}</td>
                                                <td>
												<a target="_blank" href="<?php echo '/meeting/'.strtolower($element['user_idreceiver']['fname'].'-'.$element['user_idreceiver']['lname'].'-'.$element['user_idgiver']['fname'].'-'.$element['user_idgiver']['lname']).'/'.$element['req_id'];?>">{{$element['req_id']}}
												</a></td>

                                                <td><a href= "<?php echo '/profile/'.strtolower($element['user_idreceiver']['fname'].'-'.$element['user_idreceiver']['lname']).'/'.$element['user_idreceiver']['id'];?>" target="_blank"><img src="/images/krmaicon.png" alt="" width="21" height="21" ><span>{{$element['user_idreceiver']['fname']}}</span></a></td>
                                                @if(isset($element['user_idgiver']['email']))
                                                   <td> <a href= "<?php echo '/profile/'.strtolower($element['user_idgiver']['fname'].'-'.$element['user_idgiver']['lname']).'/'.$element['user_idgiver']['id'];?>" target="_blank"><img src="/images/krmaicon.png" alt="" width="21" height="21"><span>{{$element['user_idgiver']['fname']}}</span></a></td>
                                                @else
                                                   <td> <a href="{{$element['user_idgiver']['linkedinurl']}}" target="_blank"><img src="/images/linkdin.png" alt="" width="21" height="21"><span>{{$element['user_idgiver']['fname']}}</span></a></td>
                                                @endif
                                                <td> {{$element['created_at']}}</td> 
                                                <td><a class="viewnote_detail fancybox.ajax" href="/admin/viewnote_detail/{{$element['req_id']}}" >View</a></td>
                                            </tr>
                                            @endif
                                        @endforeach
                                </tbody>
                                </table>
                            </div> 
                            @else
                            <div class="centerText">
                                    <p>No Karmanotes.</p>
                            </div>
                            @endif
                               
                             
                            <!--Received list-->
                        </div>
                        <!--1st-->
                        <!--2nd-->
                        <div id="profile" class="tab-pane meetingReceivedresult">
                            <!--sent list-->                           
                                @if(!empty($karmaTrailMeet)) 
                            <div class="UserDetail" >
                                <table class="table">
                                    <thead>
                                        <tr>
                                        <th>#</th>
                                        <th>Meeting Id</th>
                                        <th>Receiver</th>
                                        <th>Giver</th>
                                        <th>Introducer</th>
                                        <th>Request</th>
                                        <th>Request Status</th>
                                        <th>Created</th>
                                        <th>Acceptance</th>
                                        </tr>
                                    </thead>
                                <tbody id="userData"> 
                                        @foreach ($karmaTrailMeet as $key => $element)  
										@if(isset($element->user_id_receiver['fname']) && isset($element->user_id_giver['fname']))  
										<?php //echo"<pre>";print_r($element);echo"</pre>";?> 
                                           <?php ?><tr>                  
                                                <td>{{$key+1}}</td>
                                                <td> 
                                                    @if($element->status == 'completed')
                                                    <a class="viewnote_detail fancybox.ajax" href="/admin/viewnote_detail/{{$element->id}}" >{{$element->id}}</a>
													@else
														@if(isset($element->user_id_receiver['fname']) || isset($element->user_id_receiver['lname']) || isset($element->user_id_giver['fname']) || isset($element->user_id_giver['fname']))
                                                       <a target="_blank" href="<?php echo '/meeting/'.strtolower($element->user_id_receiver['fname'].'-'.$element->user_id_receiver['lname'].'-'.$element->user_id_giver['fname'].'-'.$element->user_id_giver['lname']).'/'.$element->id;?>"> {{$element->id}}</a> 
														@endif 
													@endif
                                                </td>  
 
                                                <td><a href= "<?php echo '/profile/'.strtolower($element->user_id_receiver['fname'].'-'.$element->user_id_receiver['lname']).'/'.$element->user_id_receiver['id'];?>" target="_blank"><img src="/images/krmaicon.png" alt="" width="21" height="21" ><span>{{$element->user_id_receiver['fname']}}</span></a></td>
                                                @if(isset($element->user_id_giver['email']))
                                                   <td> <a href= "<?php echo '/profile/'.strtolower($element->user_id_giver['fname'].'-'.$element->user_id_giver['lname']).'/'.$element->user_id_giver['id'];?>" target="_blank"><img src="/images/krmaicon.png" alt="" width="21" height="21"><span>{{$element->user_id_giver['fname']}}</span></a></td>
                                                @else 
													<?php if(isset($element->user_id_giver['linkedinurl'])) {
														$linkedUrl = $element->user_id_giver['linkedinurl']; ?> 
														<td> <a href="{{$linkedUrl}}" target="_blank"><img src="/images/linkdin.png" alt="" width="21" height="21"><span>{{$element->user_id_giver['fname']}}</span></a></td>
													<?php } ?>
                                                @endif 

                                                <td>
                                                @if(!empty($element->user_id_introducer))
                                                <a href= "<?php echo '/profile/'.strtolower($element->user_id_introducer->fname.'-'.$element->user_id_introducer->lname).'/'.$element->user_id_introducer->id;?>" target="_blank"><img src="/images/krmaicon.png" alt="" width="21" height="21" ><span>{{$element->user_id_introducer->fname}}</span></a>
                                                @endif
                                                </td> 

                                                <td><a class="viewreq_detail fancybox.ajax" href="/admin/viewreq_detail/{{$element->id}}/request" >View</a></td>

                                                <td>{{$element->status}}</td>
                                                <td>{{date("M d, y",strtotime($element->created_at))}}</td> 
                                                <td><a class="viewreq_detail fancybox.ajax" href="/admin/viewreq_detail/{{$element->id}}/accept" >View</a></td>
                                            </tr> 
											<?php ?>
											
                                            @endif
                                        @endforeach

                                </tbody>
                                </table>
                            </div> 
                            @else
                            <div class="centerText">
                                    <p>No Requests.</p>
                            </div>
                            @endif        
                            <!--Sent list-->
                        </div>
                        <!--2nd-->
                    </div>
                </div>
            </div>
<script type="text/javascript">

$(".viewnote_detail, .viewreq_detail").fancybox({
maxWidth    : 600,
maxHeight   : 600,
fitToView   : false,
autoSize    : true,
closeClick  : false,
openEffect  : 'none',
closeEffect : 'none',
close  : [27]
});
</script>
    </section> 