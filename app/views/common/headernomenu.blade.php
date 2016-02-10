

<div class="sideNavOverlay"></div>
<div class="container">
    <nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">
        <a href="#"><span><img src="/images/search.png"></span>Search</a>
        <a href=""><span><img src="/images/karma.png"></span>My Karma</a>
        <a href="/karma-queries"><span><img src="/images/ask.png"></span>Ask Query</a>
        <a href="/groupsAll"><span><img src="/images/group.png"></span>Groups</a>
        <a href="/karma-intro"><span><img src="/images/intro.png"></span>Karma Intro</a>
       <!-- <a href="#"><span><img src="/images/network.png"></span>Karma Network</a> -->
        <a href="/directory/skills-a"><span><img src="/images/skill.png"></span>Skills</a>
        <a href="/FAQs"><span><img src="/images/faq.png"></span>FAQs</a>
        <a href="/how-it-works"><span><img src="/images/hiw.png"></span>How it works</a>
        <a href="/logout"><span><img src="/images/logout.png"></span>Logout</a>
    </nav>
    <header class="newHeader clearfix ">
        <div class=" ">
            <div class="col-xs-1">
                <button class="sideNav" id="showLeft"></button>
            </div>
            <div class="col-xs-10 text-center logoBlock">
                <img src="/images/logo_new.png"/>
            </div>
        </div>
    </header>



</div>

<!-- /Header -->



<script>
            var menuLeft = document.getElementById( 'cbp-spmenu-s1' ),
                
                body = document.body;

            showLeft.onclick = function() {
                classie.toggle( this, 'active' );
                classie.toggle( menuLeft, 'cbp-spmenu-open' );
                disableOther( 'showLeft' );
            };
            
            function disableOther( button ) {
                if( button !== 'showLeft' ) {
                    classie.toggle( showLeft, 'disabled' );
                }
            }
</script>