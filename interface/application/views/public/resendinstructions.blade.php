<div class="container medium-box"> 
	<section class="section">
		<header class="section-header">
			<h1 class="pull-left">Confirmation email</h1>
		</header>
		<div class="section-content">

			<form action="{{ URL::to('confirmation') }}" method="post" id="resend" accept-charset="UTF-8" id="login">
				<p class="muted">If you registered and did not receive the confirmation email, please enter your email and we will try again.</p>
				<fieldset class="row-fluid help-block">

					<div class="validation-messages">
						@include('includes.messages')
					</div>

					<div class="control-group">
						<div class="controls">
							<input class="span12" id="email" name="email" maxlength="60" type="text" value="{{ (!isset($fields['email'])) ? null : $fields['email'] }}" placeholder="email address" autocomplete="off" />
						</div>
					</div>
					
				</fieldset>
				<button class="btn btn-info" type="submit">Resend confirmation email</button>
			</form>
		</div>
		<footer class="section-footer">
			<div>
				<i class="icon-user"></i>
				<a href="{{ URL::to('forgot') }}">forgot your password?</a>
			</div>
		</footer>
	</section>
</div>

<script>
head.ready("jquery", function() {
	head.js("{{ URL::base() }}/js/libs/jquery.validate.js", function (){
		$("#resend").validate({
			form: "#resend",
			rules: {
				email: {
					required: true,
					email: true
				}
			},
			messages: {
				email: {
					required: "The email field is required.",
					email: "Please enter a valid email address."
				}
			}
		});
	});
});	
</script>
