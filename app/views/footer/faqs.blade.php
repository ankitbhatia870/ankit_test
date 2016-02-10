@extends('common.masternomenu')
@section('content')
    <section class="mainWidth">
        <div class="col-lg-9 col-md-10 col-sm-12 centralize pdding0">
          <!--   <div class="backlink clearfix">
                <a href="/dashboard" class="pull-right">Back to Karma Circle</a>
            </div> -->
        </div>    
        <div class="clr"></div>
        <!-- FAQs Block-->
        <div class="faqBlock col-md-10 centralize ">
           <h2>Frequently Asked Questions</h2>
           <p>Many of your questions may be answered by our FAQs below. If anything is still not answered, please send us an email at <a href="mailto:help@karmacircles.com">help@KarmaCircles.com</a> Click a question to see the answer.</p>
            <div class="faqBlock">
                <ul class="topnav">
                    <li id="KarmaCircles"><a href="#">KarmaCircles</a>
                        <ul>
                             <li id="KarmaCircles_1"><a href="#">What is KarmaCircles?</a>
                                <ul>
                                    <li><img src="/images/ans.png">KarmaCircles is a platform to search for skilled people, request them for an online/phone/in-person meeting and then thank them for the time & help given by them. People give their time out of the goodness of their heart and to build their online reputation around various skills.
                                    </li>
                                </ul>
                            </li>
                            <li id="KarmaCircles_2"><a href="#">What all can I do on KarmaCircles?</a>
                                <ul>
                                    <li><img src="/images/ans.png">After you register on KarmaCircles using your Linkedin credentials, you can search for people who are on Karma Platform or on your Linkedin network by name, skills, location & industry. Then 
                                    <p>You can request a KarmaMeeting from anyone registered on Karma Platform.</p>
                                    <p>You can request a KarmaMeeting from your first-level Linkedin connections.</p>
                                    <p>You can thank people on Karma Platform for a KarmaMeeting in the past.</p>
                                    <p>You can thank your first-level Linkedin connections for a KarmaMeeting in the past. The person may or may not be registered on KarmaCircles platform.</p>
                                    </li>
                                </ul>
                            </li>                      
                        </ul>
                    </li>
                    <li id="Search"><a href="#">Search</a>
                        <ul>
                             <li id="Search_1"><a href="#">What all profiles can I search for?</a>
                                <ul>
                                    <li><img src="/images/ans.png">A visitor is able to search within Karma profiles only. A logged in user can search within Karma profiles and his/her first level Linkedin connections.
                                    </li>
                                </ul>
                            </li>                       
                        </ul>
                    </li>
                    <li id="KarmaMeetings"><a href="#">KarmaMeetings</a>
                        <ul>
                             <li id="KarmaMeetings_1"><a href="#">What is a KarmaMeeting?</a>
                                <ul>
                                    <li><img src="/images/ans.png">KarmaMeeting is a meeting of a Karma Giver and a Karma Receiver. Karma Giver is someone who is skilled and willing to help. Karma Receiver is someone who is seeking help around a specific topic. There is no financial motivation on either side.
                                    </li>
                                </ul>
                            </li>
                            <li id="KarmaMeetings_2"><a href="#">Who all can I request KarmaMeeting from?</a>
                                <ul>
                                    <li><img src="/images/ans.png">You can send Karma Meeting Request to any one on KarmaCirles or your first-level Linkedin connections.                                
                                    </li>
                                </ul>
                            </li>      
                             <li id="KarmaMeetings_3"><a href="#">I received a KarmaMeeting request but I am pretty busy right now. What do I do?</a>
                                <ul>
                                    <li><img src="/images/ans.png">You should archive the request. KarmaCircles will let the requester know that they should reach out to someone else. You can always go back to archived requests and accept them later when you are free.                      
                                    </li>
                                </ul>
                            </li>                        
                        </ul>
                    </li>
                    <li id="KarmaNotes"><a href="#">KarmaNotes</a>
                        <ul>
                             <li id="KarmaNotes_1"><a href="#">What is a KarmaNote?</a>
                                <ul>
                                    <li><img src="/images/ans.png"> KarmaNote is a form of thank-you note (message of appreciation) for taking the time to help you with something. PLEASE NOTE THAT IT IS NOT FEEDBACK. One should be thankful to the KarmaGiver for the time that they spent with you. It doesn’t matter whether their advice was useful or not. You also get the opportunity to endorse the KarmaGiver for up to three skills.
                                    </li>
                                </ul>
                            </li>
                            <li id="KarmaNotes_2"><a href="#">Why should I send a KarmaNote to my KarmaGiver?</a>
                                <ul>
                                    <li><img src="/images/ans.png">Your KarmaGiver took time out of his/her busy schedule to help you with something. So, it makes sense to thank that person publicly. It takes 30 seconds to write a KarmaNote and goes a long way to build your credibility with the KarmaGiver. You also get 2 Karma points for sending a KarmaNote to someone. 
                                    </li>
                                </ul>
                            </li>
                             <li id="KarmaNotes_3"><a href="#">Who all can I send KarmaNote to?</a>
                                <ul>
                                    <li><img src="/images/ans.png">You can send KarmaNote to anyone who did a KarmaMeeting with you. You can also send a KarmaNote to any of your first-level Linkedin connections for a “KarmaMeeting” that you had with them in the past.
                                    </li>
                                </ul>
                            </li>
                             <li id="KarmaNotes_4"><a href="#">Can I hide a KarmaNote?</a>
                                <ul>
                                    <li><img src="/images/ans.png">You can change status of a KarmaNote from visible to hidden (or viceversa) at any point of time. It will no longer be visible on your profile but it could still be visible on the other person’s profile.
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li id="KarmaPoints"><a href="#">Karma Points</a>
                        <ul>
                             <li id="KarmaPoints_1"><a href="#">What are Karma Points?</a>
                                <ul>
                                    <li><img src="/images/ans.png">Karma points are the way to measure someone’s reputation on the Karma platform. The higher the score, the better it is.
                                    </li>
                                </ul>
                            </li>
                            <li id="KarmaPoints_2"><a href="#">How do I earn Karma Points?</a>
                                <ul>
                                    <li><img src="/images/ans.png">You get 10 points when you receive a KarmaNote for a KarmaMeeting. You also get two points when you send a KarmaNote to someone.
                                    </li>
                                </ul>
                            </li>                       
                        </ul>
                    </li>
                    <li id="Privacy"><a href="#">Privacy</a>
                        <ul>
                             <li id="Privacy_1"><a href="#">What all is shared on KarmaCircles platform?</a>
                                <ul>
                                    <li><img src="/images/ans.png">
                                        <p>The message that you send to a KarmaGiver requesting a KarmaMeeting is visible only to you.</p>
                                         <p>The response that you receive from KarmaGiver is visible only to you.</p>
                                          <p>The KarmaNote you send to a KarmaGiver is visible on your profile and the KarmaGiver’s profile. You can always hide it on your profile at any point of time.</p>                                
                                    </li>
                                </ul>
                            </li>                       
                        </ul>
                    </li>
                     <li id="KarmaGroups"><a href="#">KarmaGroups</a>
                        <ul>
                             <li id="KarmaGroups_1"><a href="#">What are KarmaGroups?</a>
                                <ul>
                                    <li><img src="/images/ans.png">
                                        KarmaGroups allow you to limit the KarmaMeeting requests from members of a specific group only.                      
                                    </li>
                                </ul>
                            </li>   
                            <li id="KarmaGroups_2"><a href="#">How to create your KarmaGroup?</a>
                                <ul>
                                    <li><img src="/images/ans.png">
                                        Contact us at <a href="mailto:help@karmacircles.com">help@karmacircles.com</a>                            
                                    </li>
                                </ul>
                            </li>   
                             <li id="KarmaGroups_3"><a href="#">How to add yourself to a KarmaGroup?</a>
                                <ul>
                                    <li><img src="/images/ans.png">
                                        You can add yourself to a KarmaGroup by editing your profile.                         
                                    </li>
                                </ul>
                            </li>                      
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <!-- FAQs Block/-->
        <script type="text/javascript">
            $(document).ready(function(){
                var category =  '<?php echo $category ;?>';
                var question = '<?php echo $question ;?>';
                if(category != ''){
                    $("#"+category+" ul").css('display', 'block');
                    $("#"+category+" ul ul").css('display', 'none');    
                    if(question != ''){
                        $('{{ "#".$category."_".$question." ul" }}').css('display','block'); 
                    }
                }
            });
            </script>
    </section>
    <!-- /Main colom -->
@stop
    