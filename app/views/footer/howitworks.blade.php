@extends('common.masternomenu')
@section('content')
 <style type="text/css">
  @media screen and (max-width:991px) {
    .img-responsive{display: block;margin:0 auto;}
  }
  @media screen and (max-width:500px) {
    .mainWidth{padding: 0 10px;}
    }
    </style>

  <section class="mainWidth">
        <div class="col-lg-10 col-md-10 col-sm-12 centralize pdding0">
        <!-- <div class="backlink clearfix">
                <a href="/dashboard" class="pull-right">Back to Karma Circle</a>
            </div> -->
            <h3 class="greenTitle">How can you give and receive good karma on KarmaCircles?</h3>
            <figure>
                <img src="images/procesHIW.png" alt="" class="img-responsive centrIMG">
            </figure>
        </div>
        
        <!-- Step 1 section -->
        <hr class="greenLine">
        <div class="col-md-12 col-sm-12 pdding0">
            <h3 class="greenTitle">Step 1- Request a Karma Meeting</h3>
            <div class="col-md-6 col-sm-12 pdding0">
                <img src="images/step001.png" class="img-responsive roundImg">
            </div>
            <div class="col-md-6 col-sm-12 pdding0">
                <div class="sendnoteBox newChange">
                    <h2>Request sent by Rachel to George on July 24, 2014<br> Subject: Marketing strategy review</h2>
                    <p class="grayColor">Hi George</p>
                    <p class="grayColor">I run a startup which targets students. Given your extensive
                    experience in marketing, I would love to review my marketing
                    strategy with you. 2-6pm GMT works best for me on most days.</p>
                    <p  class="grayColor">Thanks Rachel</p>
                    <ul class="iconList col-md-11 pdding0 margin0">
                    <p>In gratitude, I will do the following - -</p>
                        <li>I'll pay it forward</li>
                        <li>I'll send you a KarmaNote.</li>
                        <li>I'll buy you coffee (in-person meetings only).</li>
                    </ul>                          
                </div>
            </div>
        </div>
        <div class="clr"></div>
        <!-- Step 1 section/ -->

        <!-- Step 2 section-->
        <hr class="greenLine">
        <div class="col-md-12 col-sm-12 pdding0">
            <h3 class="greenTitle">Step 2- Receive Good Karma</h3>
            <div class="col-md-6 col-sm-12 pdding0">
                <img src="images/step002.png" class="img-responsive roundImg">
            </div>
            <div class="col-md-6 col-sm-12 pdding0">
                <div class="sendnoteBox newChange">
                    <h2>Request accepted by George on  July 25, 2014</h2>
                    <div class="action fullWidth">
                        <ul>
                        <li><span class="glyphicon glyphicon-tasks"></span>  1 Hour</li>
                        <li><span class="glyphicon glyphicon-calendar"></span> 26-7-2014</li>
                        <li><span class="glyphicon glyphicon-time"></span> 4.00 PM, GMT</li>
                        </ul>
                    </div>
                    <div class="mb65 clearfix">
                        <div class="col-xs-12 skype">
                            <h4>Skype</h4>
                            <p> george.123</p>
                        </div>
                        <div class="col-xs-12 mailID">
                            <span class="glyphicon glyphicon-envelope"></span>
                            <p>george9@gmail.com</p>
                        </div>
                    </div>
                    <p class="grayColor">Rachel, happy to help you over a skype call. Please send me
                    your marketing plan by emai before the call. - George</p>
                </div>
            </div>
        </div>
        <div class="clr"></div>
        <!-- Step 2 section/ -->
        <!-- Step 3 section-->
        <hr class="greenLine">
        <div class="col-md-12 col-sm-12 pdding0">
            <h3 class="greenTitle">Step 3- Thank by sending KarmaNote</h3>
            <div class="col-md-6 col-sm-12 pdding0">
                <img src="images/step003.png" class="img-responsive roundImg">
            </div>
            <div class="col-md-6 col-sm-12 newChange">
                <div class="tabtxt">
                      <h4>Karma Note Sent to George by Rachel <span>(Met on July 26, 2014)</span> </h4>
                      <p>George, many thanks for your feedback on my marketing plan.
                        As you suggested, I will focus more on affiliate marketing than SEM.
                        I will keep you updated as my startup grows.</p>
                      <ul class="tag">
                        <li>Marketing</li>
                        <li>SaaS</li>
                        <li>E-commerce</li>
                      </ul>
                      <div class="action pull-right">
                        <p></p>
                        <p style="color:#38bb95">July 27, 2014</p>
                      </div>
                  </div>
            </div>
        </div>
        <div class="clr"></div>
       
        <!-- Step 3 section/ -->
    </section>
@stop
    