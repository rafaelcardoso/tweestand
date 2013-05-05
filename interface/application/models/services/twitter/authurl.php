<?php namespace Services\Twitter;

use \RestRequest, \Session, \Input;

class AuthUrl {
	
	public function get_url($uid, $token) {

		$request = new RestRequest('http://localhost/webservice/public/twitter/auth_url', 'POST');
			
		$request->buildPostBody(array(
			'uid' 	 => $uid,
			'utoken' => $token
		));

		$request->execute();
		$response = $request->getResponseBody();
		
		if ($response['status'] === 'sucess') {

        	return $response['url'];

		} else {

			return null;

		}

	}

	public function config_session_auth_url() {

		$user = Session::get('myauth_login');
		
		if(array_key_exists('auth_url', $user)) {
	
			if(!is_null(Input::get('oauth_token'))) {
				$this->put_auth_url($user);
			}

		} else {
			$this->put_auth_url($user);
		}

	}

	public function put_auth_url($user){
		
		$url = $this->get_url($user['keys']['uid'], $user['keys']['utoken']);
			
		if(!is_null($url)) {
			$user['auth_url'] = $url;
			Session::put('myauth_login', $user);
		}

	}
	
}