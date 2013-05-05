<div class="container medium-box"> 
	<section class="section">
		<header class="section-header">
			<h1 class="pull-left">Log in</h1>
			<div class="btn-toolbar section-actions pull-right">
				<a href="{{ URL::to('register') }}" class="btn btn-success" id="sign-up" data-placement="bottom" data-original-title="it's free :)">Sign up</a>
			</div>
		</header>
		<div class="section-content">
			<form action="{{ URL::to('login') }}" method="post" accept-charset="UTF-8" id="login">
				<fieldset class="row-fluid help-block">

					<legend>who are you? <small class="hidden-phone"></small></legend>

					<div class="validation-messages">
						@include('includes.messages')
					</div>
						
					<input class="span12" id="username" name="username" maxlength="20" type="text" value="{{ (!isset($fields['username'])) ? null : $fields['username'] }}" placeholder="username" autocomplete="off" />
					<input class="span12" id="user_pass1" name="password" type="password" value="{{ (!isset($fields['password'])) ? null : $fields['password'] }}" placeholder="password" autocomplete="off" />
					
					<button class="btn btn-info" type="submit">Log in</button>
				</fieldset>
				
			</form>
		</div>
		<footer class="section-footer">
			<div class="help-block">
				<i class="icon-user"></i>
				<a href="{{ URL::to('forgot') }}">forgot your password?</a>
			</div>
			<div>
				<i class="icon-envelope"></i>
				<a href="{{ URL::to('confirmation') }}">resend confirmation email</a>
			</div>
		</footer>
	</section>
</div>

<script>

head.ready("jquery", function() {

	head.js("{{ URL::base() }}/js/libs/jquery.validate.js", function (){

		$("#login").validate({
			form: "#login",
			rules: {
				username: {
					required: true,
					minlength: 3,
					maxlength: 20,
					alphanum: true
				},
				password: {
					required: true,
					minlength: 6
				}
			},
			messages: {
				username: {
					required: "The username field is required.",
					minlength: "The username must be between 3 - 20 characters.",
					maxlength: "The username must be between 3 - 20 characters.",
					alphanum: "The username may only contain letters and numbers."
				},
				password: {
					required: "The password field is required.",
					minlength: "Your password must be at least 6 characters long."
				}
			}
		});

	});

});

head.ready("bootstrap", function(){
	$('#sign-up').tooltip();
});

</script>