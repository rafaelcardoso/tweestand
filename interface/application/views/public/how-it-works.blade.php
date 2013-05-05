<div class="container"> 
	<section class="section">
		<header class="section-header">
			<h1 class="pull-left">How it works</h1>
			<div class="btn-toolbar section-actions pull-right">
				<a href="http://localhost/interface/public/register" class="btn btn-success" id="sign-up" data-placement="bottom" data-original-title="it's free :)">Sign up</a>
			</div>
		</header>
		<div class="section-content clearfix">
			
			<div class="row-fluid">

			  <div class="span9">
				<h2>Let's meet the actors who make this service works:</h2>	

			  	<dl>
		            <dt><h3>Twitter Social Network</h3></dt>
		            <dd>This is the service that twitter offers and you use.</dd>
		            <dt><h3>Updater</h3></dt>
		            <dd>This is a tweestand's service that connects daily with twitter and stores in a database everything that happened with your twitter account.</dd>
		            <dt><h3>Database</h3></dt>
		            <dd>Is where are stored all reports generated for your twitter account.</dd>
		            <dt><h3>Tweestand</h3></dt>
		            <dd>Is the system that connects to the database, order, organizes your reports and displays in the screen of your computer, tablet or smartphone.</dd>
		            <dt><h3>User</h3></dt>
		            <dd>This is you (:</dd>
          		</dl>


			  </div>
			  <div class="span3">
			  	
				<img src="{{ URL::base() }}/img/how-tweestand-works.png">	
			
			  </div>
			</div>


			
			
    		
		</div>
		<footer class="section-footer"></footer>
	</section>
</div>

<script>
head.ready("bootstrap", function(){
$('#sign-up').tooltip();
});

</script>