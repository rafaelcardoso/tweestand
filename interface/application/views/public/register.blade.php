<div class="container medium-box">
	<section class="section">
		<header class="section-header">
			<h1 class="pull-left header-h">Sign up</h1>
			<div class="btn-toolbar section-actions pull-right">
				<a href="{{ URL::to('login') }}" class="btn btn-success" id="sign-in" data-placement="bottom" data-original-title="already have an account?">Sign in</a>
			</div>
		</header>
		<div class="section-content">
			<form action="{{ URL::to('register') }}" method="post" accept-charset="UTF-8" id="register">
				<fieldset class="row-fluid help-block">

					<legend>Create your user account <small class="hidden-phone">(we will not share your data)</small></legend>

					<div class="validation-messages">
						@include('includes.messages')
					</div>

					<div class="control-group">
						<label for="name">name</label>
						<div class="controls">
							<input class="span12" id="name" name="name" maxlength="20" type="text" value="{{ (!isset($fields['name'])) ? null : $fields['name'] }}" autocomplete="off" />
						</div>
					</div>

					<div class="control-group">
						<label for="username">user name</label>
						<div class="controls">
							<input class="span12" id="username" name="username" maxlength="20" type="text" value="{{ (!isset($fields['username'])) ? null : $fields['username'] }}" autocomplete="off" />
						</div>
					</div>
					
					<div class="control-group">
						<label for="user_email">email</label>
						<div class="controls">
							<input class="span12" id="user_email" name="email" maxlength="60" type="text" value="{{ (!isset($fields['email'])) ? null : $fields['email'] }}" autocomplete="off" />
						</div>
					</div>

					<div class="control-group">
						<label for="user_pass1">password</label>
						<div class="controls">
							<input class="span12" id="user_pass1" name="password" type="password" value="{{ (!isset($fields['password'])) ? null : $fields['password'] }}" autocomplete="off" />
						</div>
					</div>

					<div class="control-group">
						<label for="user_pass2">retype your password &nbsp;<small>(just to make sure)</small></label>
						<div class="controls">
							<input class="span12" id="user_pass2" name="repassword" type="password" value="{{ (!isset($fields['repassword'])) ? null : $fields['repassword'] }}" autocomplete="off" />
						</div>
					</div>

					<div class="control-group">
						<div class="controls">
					    	<label class="checkbox">
					        	<input type="checkbox" name="terms" value="yes"/>I agree and accept the <a href="#">terms of service and privacy policy</a>
					      	</label>
					    </div>
					</div>
				
				</fieldset>
				<button class="btn btn-info" type="submit">Create my account</button>
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
 		head.js({validate: "{{ URL::base() }}/js/libs/jquery.validate.js"});
	});
</script>

<script>
head.ready("validate", function() {

	$("#register").validate({
		form: "#register",
		rules: {
			name: {
				required: true,
				alphaspace: true,
				minlength: 3,
				maxlength: 20
			},
			username: {
				required: true,
				minlength: 3,
				maxlength: 20,
				alphanum: true
			},
			email: {
				required: true,
				email: true
			},
			password: {
				required: true,
				minlength: 6
			},
			repassword: {
				required: true,
				equalTo: "#user_pass1"
			},
			terms: "required"
		},
		messages: {
			name: {
				required: "The name field is required.",
				alphaspace: "The name may only contain letters and spaces.",
				minlength: "The name must be between 3 - 20 characters.",
				maxlength: "The name must be between 3 - 20 characters."
			},
			username: {
				required: "The username field is required.",
				minlength: "The username must be between 3 - 20 characters.",
				maxlength: "The username must be between 3 - 20 characters.",
				alphanum: "The username may only contain letters and numbers."
			},
			email: {
				required: "The email field is required.",
				email: "Please enter a valid email address."
			},
			password: {
				required: "The password field is required.",
				minlength: "Your password must be at least 5 characters long."
			},
			repassword: {
				required: "The repassword field is required.",
				equalTo: "The repassword and password must match."
			},
			terms: "Accept the terms of service and privacy policy."
		}
	});

});
head.ready("bootstrap", function(){
	$('#sign-in').tooltip();
});
</script>