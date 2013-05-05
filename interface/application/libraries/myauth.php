<?php


class Myauth extends Laravel\Auth\Drivers\Driver {
	
	public function attempt($arguments = array()) {

    	$request = new RestRequest('http://localhost/webservice/public/user/auth', 'POST');
		
		$request->buildPostBody(array(
			'username' => $arguments['username'],
			'password' => $arguments['password']
		));

		$request->execute();
		$response = $request->getResponseBody();

		if($response['status'] === 'sucess') {

			$user = $response['user'];
			Session::flash('msg', $response);
			
			
			/*foreach ($user['twitter_accounts'] as $account) {
				if($account['enable'] == 1){
					$followers = $account['followers'];
					break;
				}
			}

			if(isset($followers)){
				Session::flash('followers', $followers);
			}*/
			
        	return $this->login($user);

		} else {

			return false;

		}

	}

	public function retrieve($id) {
    	
    	return Session::get('myauth_login');

	}

}
