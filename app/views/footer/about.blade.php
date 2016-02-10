@extends('common.masternomenu')
@section('content')
<section class="mainWidth">
        <div class="col-lg-9 col-md-10 col-sm-12 centralize pdding0">
            <!-- <div class="backlink clearfix">
                <a href="/dashboard" class="pull-right">Back to Karma Circle</a>
            </div> -->
        </div>    
        <div class="clr"></div>
        <!-- Team Block-->
        <div class="col-md-9 centralize aboutUs">
             @if(!empty($team))
            <div class="col-sm-12 borderPic">
            <h2>Team</h2>
            <div class="col-sm-3 marginBot">

                 @if ($team->piclink == '')
                  <img  alt="" src="/images/default.png">
                @else
                  <img alt="" src="{{$team->piclink}}" >
                @endif 
                <!-- <img src="/images/users/dkp.jpg" alt=""> -->

                <h4>{{$team->fname.' '.$team->lname}}</h4>
                   <div class="midicondetails">
                        <ul class="clearfix minNew">
                            <li><a href="{{$team->linkedinurl}}"><img alt="" src="images/linkdin.png"></a></li>
                            <li>
                                <a href="/profile/<?php echo strtolower($team->fname.'-'.$team->lname).'/'.$team->id ;?>"><img alt="" src="images/krmaicon.png"></a>
                                <span>{{$team->karmascore;}}</span>
                            </li>
                        </ul>
                    </div> 
                </div> 
                <div class="col-md-9 info pddingL">
                         <p>Deepak Goel is the founder & CEO of KarmaCircles. Prior to that, he was co-founder and Chief Technology Officer of Packback (a Chicago-based startup funded by Mark Cuban & other notable angel investors) where he built their product strategy, online marketing strategy and engineering team. <br>
                    Prior to that, he was the Chief Product Officer of IndiaHomes (backed by NEA, Foundation Capital & Helion Ventures) where he built & led product management, marketing and engineering teams. Before that, he was Director of Product Management at Coupons.com (COUP) where he created
                    & grew several multi-million dollar products. He has also been a senior executive at Microsoft & Nokia. </p> 
                </div>
            </div>
            @endif
        </div>
        <!-- Team Block/-->


        <!-- Adviser Block-->
        <div class="col-md-9 centralize aboutUs">
            <div class="col-sm-12 borderPic">
                <h2>Advisors</h2>
                @if(!empty($advisor)) 
                    @foreach($advisor as $advisor)
						<?php $name = $advisor->fname.' '.$advisor->lname; ?>
                    <div class="col-sm-3 marginBot">
                       <!--  <img src="/images/users/pk.jpg" alt=""> -->
                        @if ($advisor->piclink == '')
                         <img  alt="" src="/images/default.png">
                        @else
                         <img alt="" src="{{$advisor->piclink}}" >
                        @endif 
                        <h4>{{$name}}</h4>
                        <div class="midicondetails">
                            <ul class="clearfix minNew">
                                <li><a href="{{$advisor->linkedinurl}}"><img alt="" src="images/linkdin.png"></a></li>
                                <li>
                                    <a href="/profile/<?php echo strtolower($advisor->fname.'-'.$advisor->lname).'/'.$advisor->id ;?>"><img alt="" src="images/krmaicon.png"></a>
                                    <span>{{$advisor->karmascore;}}</span> 
                                </li>
                            </ul>
                        </div> 
                    </div> 
                        <div class="col-md-9 info pddingL">
							@if($name == 'Prasad Kaipa')
                            <p>Dr. Prasad Kaipa is an executive coach, mentor, author and founder and executive director (2007-2009) of the Centre for Leadership, Innovation and Change at the Indian School of Business. He also has been a part-time visiting faculty at Saybrook Graduate School. Since 1990, Dr. Kaipa has advised CEOs and coached Executive Teams in the areas of Innovation, Business Transformation, Decision Making, Strategic Thinking and Personal Mastery. He is an author of the book 'Discontinuous Learning: Igniting Genius Within by Aligning Self, Work, and Family'.</p> 
							@endif
							@if($name == 'Gokul Rajaram')
							<p>Gokul Rajaram is Product Engineering Lead at Square. Previously he was Product Director, Ads at Facebook. In this role, he drove the product roadmap and execution for Facebook's advertising products.Before Facebook, Gokul was Co-Founder and CEO of Chai Labs, a semantic technology startup he grew to profitability and a multi-million dollar annual revenue run-rate before its acquisition by Facebook.</p>
                    <p>Earlier in his career, Gokul was Product Management Director for Google AdSense. He helped conceive and crystallize the product in early 2003, and played a key role in its launch and growth from 2003 onwards into a multi-billion dollar product line. Gokul was also product lead for a number of acquisitions for Google, including DoubleClick (display advertising/ad serving), AdScape (in-game advertising) and dMarc (radio advertising).</p>
							@endif
							@if($name == 'Joseph Miller')
							<p>Joe Miller is an accomplished executive with more than 25 years of senior and C level executive, financial, and general management experience. He has directly managed P&L operations greater than $150 million and EBITA greater than $25 million. A passionate and communicative leader with a strong strategic sense, Joe actively crafts corporate culture, maximizes employee potential, and develops top leadership. His proven track record of consistent and measurable gains in revenue, profit, and ROIC, often outside the market wave, speaks for itself. He can quickly assess an organization's and market's strengths and opportunities and deploy resources to improve business practices and financial results. Joe has directly led sales and marketing initiatives growing businesses both organically and through acquisitions.</p>
							@endif
                        </div>
                    <hr>
                    @endforeach
                @endif 
            </div> 
        </div>
        <!-- Adviser Block/-->

        <!-- Investor Block-->
        <div class="col-md-9 centralize aboutUs">
            <div class="col-sm-12 borderPic">
                <h2>Partners</h2>
                <div class="col-sm-3 marginBot">
                    <img src="/images/users/logo_evon.png" alt="">
                    <h4>Evon Technologies</h4>
                    </div>  
                    <div class="col-md-9 info pddingL">
                        <p>Evon Technologies is an Offshore Software Development Company focused in developing solutions in the field of Web & Mobile.
Evon has been in operation since 1996 and currently employs a strong workforce of around 200 people. The clientele of Evon spans across the globe with major concentration in the USA. Evon's corporate office is in the IT park of Dehradun, a tier 2 city known for its proximity to nature and at the same time having ample modern amenities for people to enjoy the metropolitan life.</p>
                    </div>
                 
               
             
            </div>
        </div>
        <!-- Adviser Block/-->



    </section>
    <!-- /Main colom -->
@stop
    