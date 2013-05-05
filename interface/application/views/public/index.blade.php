<header class="hero-unit showcase">
    <div class="container">
        <div class="row pagination-centered">
            <hgroup>
                <h1>Stay tuned to what happens with your twitter</h1>
                <h3 class="muted">Tweestand makes easy keep updated with analytics information</h3>
            </hgroup>
            <img alt="screenshot of reports page" width="940" height="340" src="{{ URL::base() }}/img/screenshot.png" />
        </div>
    </div>
</header>

<section class="container">

    @if(Auth::guest())
    <div class="hero-unit">
        <h4 class="h1">Amazing twitter analysis system.</h4>
        <p>With tweestand you will have one new report per day with several information about your twitter account, including your engagement and influence on the social network. Sign up and manage your twitter metrics with ease, and the best: 
            <span class="text-success">it's free</span>.
        </p>
        <a href="{{URL::to('register')}}" class="btn btn-success btn-large">Get started for free <span class="hidden-phone">&raquo;</span></a>
    </div>
    @endif

    <ul class="span12 unstyled feature-list">

        <li class="span4 alpha item">
            <a href="{{URL::to('features')}}#engagement-report" class="link">
                <div class="clearfix">
                    <h2>Engagement report</h2>
                    <div class="span1 alpha">
                        <i class="icon-bullhorn icon-2x visible-tablet visible-phone yellow"></i>
                        <i class="icon-bullhorn icon-3x visible-desktop yellow"></i>
                    </div>
                    <div class="span3 alpha">
                        <p>Everything you did on twitter, like tweets, mentions and retweets sent. realize why your influence is decreasing or increasing.</p>
                    </div>        
                </div>
            </a>
        </li>

        <li class="span4 item">
            <a href="{{URL::to('features')}}#influence-report" class="link">
                <div class="clearfix">
                    <h2>Influence report</h2>
                    <div class="span1 alpha">
                        <i class="icon-globe icon-2x visible-tablet visible-phone light-blue"></i>
                        <i class="icon-globe icon-3x visible-desktop hidden-tablet hidden-phone light-blue"></i>
                    </div>
                    <div class="span3 alpha">
                        <p>Everything that you has received, like mentions and retweets. Understand the return on what you did on social network.</p>
                    </div>        
                </div>
            </a>
        </li>
        
        <li class="span4 item">
            <a href="{{URL::to('features')}}#followers-report" class="link">
                <div class="clearfix">
                    <h2>Followers report</h2>
                    <div class="span1 alpha">
                        <i class="icon-group icon-2x visible-tablet visible-phone muted"></i>
                        <i class="icon-group icon-3x visible-desktop hidden-tablet hidden-phone muted"></i>
                    </div>
                    <div class="span3 alpha">
                        <p>Allows you to track your number of followers, displaying the total number of followers, followers gains and lost.</p>    
                    </div>
                </div>
            </a>
        </li>

        <li class="span4 alpha both item">
            <a href="{{URL::to('features')}}#reporting-daily" class="link">
                <div class="clearfix">
                    <h2>Reporting daily</h2>
                    <div class="span1 alpha">
                        <i class="icon-calendar icon-2x visible-tablet visible-phone purple"></i>
                        <i class="icon-calendar icon-3x visible-desktop hidden-tablet hidden-phone purple"></i>
                    </div>
                    <div class="span3 alpha">
                        <p>Every day we generate the reports from the previous day.</p>
                    </div>        
                </div>
            </a>
        </li>
   
        <li class="span4 item">
            <a href="{{URL::to('features')}}#followers-map" class="link">
                <div class="clearfix">
                    <h2>Followers map</h2>
                     <div class="span1 alpha">
                        <i class="icon-map-marker icon-2x visible-tablet visible-phone red"></i>
                        <i class="icon-map-marker icon-3x visible-desktop hidden-tablet red"></i>
                    </div>
                    <div class="span3 alpha">
                        <p>Displays the location of the district, city, state or country of his followers with their information in a clear and expressive map.</p>
                    </div>
                </div>
            </a>
        </li>

        <li class="span4 item">
            <a href="{{URL::to('features')}}#free" class="link">
                <div class="clearfix">
                    <h2>Free</h2>
                    <div class="span1 alpha">
                        <i class="icon-money icon-2x visible-tablet visible-phone green"></i>
                        <i class="icon-money icon-3x visible-desktop hidden-tablet hidden-phone green"></i>
                    </div>
                    <div class="span3 alpha">
                        <p>Use for free. If you'd like, make a donation of how much you want and help us to improve this service.</p>
                    </div>
                </div>
            </a>
        </li>
    </ul>

    <a href="{{URL::to('features')}}" class="pull-left btn btn-info btn-large btn-block">See all features detailed</a>


</section>

<script>
head.ready("jquery", function() {
    $(function() {
        $(".feature-list li").hover(function(){
            $(this).find('.visible-desktop').addClass('animated').addClass('tada');
        }, function(){
            $(this).find('.visible-desktop').removeClass('animated').removeClass('tada');
        });
    });
});

</script>