<div class="container medium-box"> 
	<section class="section">
		<header class="section-header">
			<h1 class="pull-left">Registered</h1>
		</header>
		<div class="section-content">
				
			<div id="messages-list">
				<div class="alert alert-success" data-field="name">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<span>first stage completed, now you need to go to your inbox and open the email that we send to activate your account. After that, you can login. (if you do not see the email, check spam box)</span>
				</div>
			</div>

		</div>
		<footer class="section-footer">
			<div>
				<i class="icon-envelope"></i>
				<a href="{{ URL::to('confirmation') }}">resend confirmation email</a>
			</div>
		</footer>
	</section>
</div>