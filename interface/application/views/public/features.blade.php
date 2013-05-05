<div class="container"> 
	<section class="section">
		<header class="section-header">
			<h1 class="pull-left">Features</h1>
			<div class="btn-toolbar section-actions pull-right">
				<a href="http://localhost/interface/public/register" class="btn btn-success" id="sign-up" data-placement="bottom" data-original-title="it's free :)">Sign up</a>
			</div>
		</header>
		<div class="section-content clearfix">
			<ul class="span12 unstyled feature-list row-fluid">
		        <li class="span12 alpha item" id="engagement-report">
	            	<div class="clearfix">
	                	<h2>Engagement report</h2>
	                	<div class="span12 alpha help-block">
	                    	<div class="span1 alpha">
	                        	<i class="icon-bullhorn icon-2x visible-tablet visible-phone yellow"></i>
	                    		<i class="icon-bullhorn icon-3x visible-desktop yellow"></i>
	                    	</div>
	                    	<div class="span11 alpha">
	                        	<p>A great way to understand how your influence on twitter are behaving is analyzing your engagement. If your number of followers is falling, or if the number of mentions and retweets that you get previously is slowing, it may be because you do not behave as before. This feature helps you to track your behavior.</p>
	                    	</div>	
	                    </div>
	                    <div class="span12 alpha thumbnail hidden-phone">
                			<img alt="screenshot of engagement report" src="{{ URL::base() }}/img/tweestand-engagement-report.png" />
                		</div>
	                </div>
	        	</li>
	        	<li class="span12 alpha item" id="influence-report">
					<div class="clearfix">
	            		<h2>Influence report</h2>
						<div class="span12 alpha help-block">
							<div class="span1 alpha">
		                		<i class="icon-globe icon-2x visible-tablet visible-phone light-blue"></i>
		                    	<i class="icon-globe icon-3x visible-desktop hidden-tablet hidden-phone light-blue"></i>
	                    	</div>
		                    <div class="span11 alpha">
		                       	<p>With this report you can understand how are your influence on microblog. See the amount of mentions and retweets you get every day and compare these data with your engagement. With this feature is much easier to track your personal growth goals in twitter.</p>
		                    </div>	
	                	</div>
						<div class="span12 alpha thumbnail hidden-phone">
                			<img alt="screenshot of engagement report" src="{{ URL::base() }}/img/tweestand-influence-report.png" />
                		</div>
	            	</div>
	        	</li>
	        	<li class="span12 alpha item" id="followers-report">
	            	<div class="clearfix">
	                	<h2>Followers report</h2>
						<div class="span12 alpha help-block">
							<div class="span1 alpha">
		                		<i class="icon-group icon-2x visible-tablet visible-phone muted"></i>
		                        <i class="icon-group icon-3x visible-desktop hidden-tablet hidden-phone muted"></i>
		                    </div>
	                    	<div class="span11 alpha">
	                       		<p>Want know how many followers you gain and lose every day? we can tell you this. This feature is able to inform not only the followers you gain and lose daily but also your total number of followers, for you can track your growth rate.</p>
	                    	</div>	
	                	</div>
						<div class="span12 alpha thumbnail hidden-phone">
                			<img alt="screenshot of engagement report" src="{{ URL::base() }}/img/tweestand-followers-report.png" />
                		</div>
	                </div>
	        	</li>
	        	<li class="span12 alpha alpha both item" id="reporting-daily">
	            	<h2>Reporting daily</h2>
					<div class="span12 alpha help-block">
						<div class="span1 alpha">
	                		<i class="icon-calendar icon-2x visible-tablet visible-phone purple"></i>
	                        <i class="icon-calendar icon-3x visible-desktop hidden-tablet hidden-phone purple"></i>
	                    </div>
	                    <div class="span11 alpha">
	                       	<p>Every day we go to the twitter, collect everything that happened to you and store in a database. Then, you can choose the date range you want and analyze the reports.</p>
	                    </div>	
	                </div>
					<div class="span12 alpha thumbnail hidden-phone">
                		<img alt="screenshot of engagement report" src="{{ URL::base() }}/img/tweestand-reporting-daily.png" />
                	</div>
	        	</li>
	        	<li class="span12 alpha item" id="followers-map">
	        		<h2>Followers map</h2>
	        		<div class="span12 alpha help-block">
						<div class="span1 alpha">
	                		<i class="icon-map-marker icon-2x visible-tablet visible-phone red"></i>
	                    	<i class="icon-map-marker icon-3x visible-desktop hidden-tablet red"></i>
	                	</div>
	                	<div class="span11 alpha">
	               			<p>Want to know where your followers are? Yes, we can tell you.</p>
	                	</div>	
	            	</div>
					<div class="span12 alpha thumbnail hidden-phone">
	                	<img alt="screenshot of engagement report" src="{{ URL::base() }}/img/tweestand-followers-map.png" />
	                </div>
	        	</li>
	        	<li class="span12 alpha item" id="free">
					<h2>Free</h2>
	        		<div class="span12 alpha help-block">
						<div class="span1 alpha">
	                		<i class="icon-money icon-2x visible-tablet visible-phone green"></i>
	                        <i class="icon-money icon-3x visible-desktop hidden-tablet hidden-phone green"></i>
	                	</div>
	                	<div class="span11 alpha">
	               			<p>We do not charge any amount for you use this service. But we have expenses with development, hosting and storage. So if you like this service and want to help improve it, please visit our donation page.</p>
	                	</div>	
	            	</div>
	        	</li>
    		</ul>
    		<a href="{{URL::to('register')}}" class="pull-left btn btn-success btn-large btn-block">Get started for free »</a>
		</div>
		<footer class="section-footer"></footer>
	</section>
</div>

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
head.ready("bootstrap", function(){
$('#sign-up').tooltip();
});

</script>