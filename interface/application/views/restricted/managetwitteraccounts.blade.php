<?$user = Session::get('myauth_login')?>
<div class="container">
	<div class="container-fluid">
		<div class="row-fluid">
	    	<section class="section span12">
				<header class="section-header">
					<h1 class="pull-left">twitter accounts</h1>
				</header>
				<div class="section-content">

					<div id="user-confirm" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-header">
					    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					    	<h3>Are you sure?</h3>
					  	</div>
					  	<div class="modal-body">
						    
						</div>
						<div class="modal-footer">
							<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
						    <a href="#" class="btn">Go ahead!</a>
						</div>
					</div>

                	@include('includes.messages')
					
					@if(empty(Session::get('myauth_login')['twitter_accounts']))
						<div class="alert alert-info alert-block">
  							<button type="button" class="close" data-dismiss="alert">&times;</button>
  							<h4>Dude, you have not added any twitter account yet.</h4>
  							Go ahead, add a twitter account and discover all that this wonderful app has to offer you :D
						</div>
					@else
						<h3>Twitter accounts linked to your Tweestand account:</h3>
						<table class="table table-striped table-bordered">
							<thead> 
								<tr> 
									<th class="hidden-phone">photo</th> 
									<th class="hidden-phone">name</th>
									<th>user</th>
									<th class="hidden-phone">followers</th>
									<th class="hidden-phone">friends</th>
									<th class="hidden-phone">status</th>
									<th>action</th>
								</tr> 
							</thead> 
							<tbody> 

								@foreach (Session::get('myauth_login')['twitter_accounts'] as $account)
		    						<tr>
										<td class="hidden-phone"><img src="{{ isset($account['profile_image_url']) ? $account['profile_image_url'] : NULL }}" alt="{{ isset($account['screen_name']) ? $account['screen_name'] : NULL }} twitter photo" /></td>
										<td class="hidden-phone">{{ isset($account['name']) ? $account['name'] : NULL }}</td>
										<td>{{ isset($account['screen_name']) ? $account['screen_name'] : NULL }}</td> 
										<td class="hidden-phone">{{ isset($account['followers_count']) ? $account['followers_count'] : NULL }}</td> 
										<td class="hidden-phone">{{ isset($account['friends_count']) ? $account['friends_count'] : NULL }}</td> 

										@if($account['enable'] == 1)
											<td class="hidden-phone"><span class="label">active</span></td>
											<td><button data-id="{{ $account['id'] }}" class="btn btn-mini btn-danger account-action deactivate-account" title="click to disable this account">deactivate</button></td>
										@else
											<td class="hidden-phone"><span class="label">inactive</span></td>
											<td><button data-id="{{ $account['id'] }}" class="btn btn-mini btn-success account-action activate-account" title="click to enable this account">activate</button></td>
										@endif
									</tr>
								@endforeach
								
							</tbody> 
						</table>
					@endif
					<p><span class="label label-info">Heads up: </span> reports are generated only for active accounts.</p>
					@if(empty($user['twitter_accounts']))
						@if(array_key_exists('auth_url', $user))
							<a href="{{ $user['auth_url'] }}" class="btn btn-info">Add a new twitter account »</a>
						@endif
					@endif

				</div>
				<footer class="section-footer">
					
				</footer>
			</section>
	    </div>
	</div>
</div>

<script>
	head.ready("jquery", function(){
		$('.account-action').on({
		    click: function() {
		    	if ($(this).hasClass('deactivate-account')) {

		    		var modal_body = $('<p>If you disable this account we will not generate more reports to it until it is activated again.</p>');
		    		var add_button_class = 'btn-danger';
		    		var remove_button_class = 'btn-success';
		    		var link_url = '{{ URL::to('deactivate-account') }}?id=';

		    	} else if($(this).hasClass('activate-account')) {

		    		var modal_body = $('<p>if you activate this account we will generate daily reports to her until you disable it again.</p>');
		    		var add_button_class = 'btn-success';
		    		var remove_button_class = 'btn-danger';
		    		var link_url = '{{ URL::to('activate-account') }}?id=';

		    	}
				
				//set the modal body text
				$('#user-confirm .modal-body').html(modal_body);
		    	
				//configure the confirm button
				$('#user-confirm .modal-footer a.btn').removeClass(remove_button_class).addClass(add_button_class).attr('href',link_url+$(this).attr('data-id'));
				
				//launch the modal
		        $('#user-confirm').modal();
	    	}
		});

		$('#user-confirm').on('hide', function () {
			//reset the link url to default
			$(this).find('a.btn-danger').attr('href', '#');
		});
	});
</script>