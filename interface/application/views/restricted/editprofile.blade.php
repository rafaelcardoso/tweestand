<?$user = Session::get('myauth_login')?>
<div class="container">
	<div class="container-fluid">
		<div class="row-fluid">
	    	<section class="section span12">
				<header class="section-header">
					<h1 class="pull-left">user profile</h1>
				</header>
				<div class="section-content clearfix">
					<form action="{{ URL::to('edit-profile') }}" method="post" accept-charset="UTF-8" id="edit-profile" class="span6">
						<fieldset class="row-fluid help-block">
							<legend>Edit your user account</legend>

							<div class="validation-messages">
								@if(isset($response_data))
									@if(array_key_exists('profile',$response_data))
										@include('includes.messages')
									@endif
								@else
									<div id="messages-list"></div>
								@endif
							</div>

							<div class="control-group">
								<label for="name">name</label>
								<div class="controls">
									<input class="span12" id="name" name="name" maxlength="20" type="text" value="{{$user['name']}}" autocomplete="off" />
								</div>
							</div>

							<div class="control-group">
								<label for="username">user name</label>
								<div class="controls">
									<input class="span12" id="username" name="username" maxlength="20" type="text" value="{{$user['username']}}" autocomplete="off" />
								</div>
							</div>
					
							<div class="control-group">
								<label for="user_email">email</label>
								<div class="controls">
									<input class="span12" id="user_email" name="email" maxlength="60" type="text" value="{{$user['email']}}" autocomplete="off" />
								</div>
							</div>

						</fieldset>
						<button class="btn btn-info" type="submit">Edit profile</button>
					</form>

					<form action="{{ URL::to('edit-password') }}" method="post" accept-charset="UTF-8" id="edit-password" class="span6">
						<fieldset class="row-fluid help-block">

							<legend>Change your password</legend>

							<div class="validation-messages">
								@if(isset($response_data))
									@if(array_key_exists('password',$response_data))
										@include('includes.messages')
									@endif
								@else
									<div id="messages-list"></div>
								@endif
							</div>

							<div class="control-group">
								<label for="current_pass">current password</label>
								<div class="controls">
									<input class="span12" id="current_pass" name="current_password" type="password" autocomplete="off" />
								</div>
							</div>

							<div class="control-group">
								<label for="user_pass1">new password</label>
								<div class="controls">
									<input class="span12" id="user_pass1" name="password" type="password" autocomplete="off" />
								</div>
							</div>

							<div class="control-group">
								<label for="user_pass2">retype your new password</label>
								<div class="controls">
									<input class="span12" id="user_pass2" name="repassword" type="password" autocomplete="off" />
								</div>
							</div>

						</fieldset>
						<button class="btn btn-info" type="submit">Edit password</button>
					</form>
				</div>

				<footer class="section-footer">
			
				</footer>
			</section>
	    </div>
	</div>
</div>

<script>
head.ready("jquery", function() {
 	head.js({validate: "{{ URL::base() }}/js/libs/jquery.validate.js"});
	head.ready("validate", function() {
		$("#edit-profile").validate({
			form: "#edit-profile",
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
				}
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
				}
			}
		});

		$("#edit-password").validate({
			form: "#edit-password",
			rules: {
				current_password: {
					required: true,
					minlength: 5
				},
				password: {
					required: true,
					minlength: 5
				},
				repassword: {
					required: true,
					equalTo: "#user_pass1"
				}
			},
			messages: {
				current_password: {
					required: "The current password field is required.",
					minlength: "The current password must be at least 5 characters long."
				},
				password: {
					required: "The password field is required.",
					minlength: "The password must be at least 5 characters long."
				},
				repassword: {
					required: "The repassword field is required.",
					equalTo: "The repassword and password must match."
				}
			}
		});
	});
});
</script>