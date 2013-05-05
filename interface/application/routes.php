<?php


//die(print_r(Session::get('myauth_login')));

Route::get('/', array('uses' => 'home@index'));
Route::get('features', array('uses' => 'home@features'));
Route::get('how-it-works', array('uses' => 'home@how'));
Route::get('donate', array('uses' => 'home@donate'));
Route::any('login', array('uses' => 'home@login'));
Route::any('register', array('uses' => 'home@register'));
Route::any('confirmation', array('uses' => 'home@confirm'));
Route::any('forgot', array('uses' => 'home@forgot'));

Route::group(array('before' => 'auth|auth_url'), function(){
	Route::get('dashboard', array('uses' => 'dashboard@home'));
	Route::any('edit-profile', array('uses' => 'user@edit_profile'));
	Route::any('edit-password', array('uses' => 'user@edit_password'));
    Route::get('manage-twitter-accounts', array('uses' => 'twitteraccount@manage'));
    Route::get('authorize', array('uses' => 'twitteraccount@authorize'));    
    Route::get('activate-account', array('uses' => 'twitteraccount@activate'));
    Route::get('deactivate-account', array('uses' => 'twitteraccount@deactivate'));
    Route::post('report', array('uses' => 'dashboard@report'));
});

Route::get('logout', function(){
		
	Auth::logout();
	
	return Redirect::to_action('home@login')->with('msg', array('response_data' => array(
			'status' => 'sucess',
			'messages' => array(
				'info' => array(
					array(
						'default' => 'Successfully logged out, we are expecting you back.'
					)
				)
			)
	)));
	
});


Event::listen('404', function()
{
	return Response::error('404');
});

Event::listen('500', function()
{
	return Response::error('500');
});

Route::filter('auth_url', function() {
	if(empty(Session::get('myauth_login')['twitter_accounts'])){
		try {
			$auth_url = new Services\Twitter\AuthUrl();
			$auth_url->config_session_auth_url();
		} catch (Exception $e) {
			
		}
	}
	
});

Route::filter('before', function()
{
	// Do stuff before every request to your application...
});

Route::filter('after', function($response)
{
	// Do stuff after every request to your application...
});

Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});

Route::filter('auth', function()
{

	if (Auth::guest()){

		if (Request::ajax()){
    		return Response::json(array(
				'status' => 'error',
				'messages' => array(
					'error' => array(
						array(
							'default' => 'Your session has expired. Please, log in again.'
						)
					)
				)
			));

		}else{
			return Redirect::to('login');
		}

	} 	

});