<?php

class TwitterAccount_Controller extends Base_Controller {

	public $restful = true;

	public function get_authorize() {

		try {

			$user = Session::get('myauth_login');
			$data = Input::get();
			
			$validate = new Services\TwitterAccount\TwitterAccountValidation($data);
			$validate->validate_auth_keys();
			
			$request = new RestRequest('http://localhost/webservice/public/twitter/new', 'POST');
			$request->buildPostBody(array(
				'uid' 			 => $user['keys']['uid'],
				'utoken' 		 => $user['keys']['utoken'],
				'oauth_token'    => $data['oauth_token'],
				'oauth_verifier' => $data['oauth_verifier']
			));
			$request->execute();
			$this->response_data = $request->getResponseBody();
			$user['twitter_accounts'][] = $this->response_data['twitter_account'];
			Session::put('myauth_login', $user);

		} catch (ValidateException $e) {
			
			$this->response_data = array(
				'status' => 'error',
				'messages' => array(
					'error' => $e->get()->messages
				)
			);

		} catch (RequestErrorException $e) {

			$this->response_data = Session::get('error');

		} catch (RequestException $e) {

			$this->response_data = array(
				'status' => 'error',
				'messages' => array(
					'error' => array(
						array(
							'default' => 'could not complete the operation, wait a few minutes and try again.'
						)
					)
				)
			);

		} catch(Exception $e) {

			$this->response_data = array(
				'status' => 'error',
				'messages' => array(
					'error' => array(
						array(
							'default' => 'could not complete the operation, wait a few minutes and try again.'
						)
					)
				)
			);

		}

		$this->layout->title = 'Tweestand | Authorize';
		$this->layout->content = View::make('restricted.managetwitteraccounts', array('response_data' => $this->response_data));

	}

	public function get_manage(){
			
		$this->layout->title = 'Tweestand | Twitter accounts manager';
		$this->layout->content = View::make('restricted.managetwitteraccounts');

	}

	public function get_deactivate(){
		
		try {

			$user = Session::get('myauth_login');
			$data = Input::get();

			$validate = new Services\TwitterAccount\TwitterAccountValidation($data);
			$validate->validate_action_account();
			
			$request = new RestRequest('http://localhost/webservice/public/twitter/deactivate', 'POST');
			$request->buildPostBody(array(
				'uid' 				 => $user['keys']['uid'],
				'utoken' 			 => $user['keys']['utoken'],
				'twitter_account_id' => $data['id']
			));
			$request->execute();
			$this->response_data = $request->getResponseBody();

			if($this->response_data['status'] == 'success'){
				foreach ($user['twitter_accounts'] as $index => $account) {
					if($account['id'] == $this->response_data['account']['id']){
						$user['twitter_accounts'][$index]['enable'] = 0;
						Session::put('myauth_login', $user);
						break;
					}
				}	
			}

			
			
		} catch (ValidateException $e) {
			
			$this->response_data = array(
				'status' => 'error',
				'messages' => array(
					'error' => $e->get()->messages
				)
			);

		} catch (RequestErrorException $e) {

			$this->response_data = Session::get('error');

		} catch (RequestException $e) {

			$this->response_data = array(
				'status' => 'error',
				'messages' => array(
					'error' => array(
						array(
							'default' => 'we could not complete the operation, wait a few minutes and try again.'
						)
					)
				)
			);

		} catch(Exception $e) {

			$this->response_data = array(
				'error' => array(
					'messages' => array(
						array(
							'default' => 'we could not complete the operation, wait a few minutes and try again.'
						)
					)
				)
			);

		}

		$this->layout->title = 'Tweestand | Twitter accounts manager';
		$this->layout->content = View::make('restricted.managetwitteraccounts', array('response_data' => $this->response_data));
		
	}

	public function get_activate(){
		
		try {

			$user = Session::get('myauth_login');
			$data = Input::get();

			$validate = new Services\TwitterAccount\TwitterAccountValidation($data);
			$validate->validate_action_account();
			
			foreach($user['twitter_accounts'] as $account) {
				if($account['enable'] == 1) {
					throw new Exception('To activate an account you need disable the others.');
				}
			}
			
			$request = new RestRequest('http://localhost/webservice/public/twitter/activate', 'POST');
			$request->buildPostBody(array(
				'uid' 				 => $user['keys']['uid'],
				'utoken' 			 => $user['keys']['utoken'],
				'twitter_account_id' => $data['id']
			));
			$request->execute();
			$this->response_data = $request->getResponseBody();

			if($this->response_data['status'] == 'success'){
				foreach ($user['twitter_accounts'] as $index => $account) {
					if($account['id'] == $this->response_data['account']['id']) {
						$user['twitter_accounts'][$index]['enable'] = 1;
						$user['twitter_accounts'][$index]['in_use'] = true;
						Session::put('myauth_login', $user);
						break;
					}
				}	
			}
			
		} catch (ValidateException $e) {
			
			$this->response_data = array(
				'status' => 'error',
				'messages' => array(
					'error' => $e->get()->messages
				)
			);

		} catch (RequestErrorException $e) {

			$this->response_data = Session::get('error');

		} catch (RequestException $e) {

			$this->response_data = array(
				'status' => 'error',
				'messages' => array(
					'error' => array(
						array(
							'default' => 'we could not complete the operation, wait a few minutes and try again.'
						)
					)
				)
			);

		} catch(Exception $e) {

			$this->response_data = array(
				'status' => 'error',
				'messages' => array(
					'error' => array(
						array(
							'default' => $e->getMessage()
						)
					)
				)
			);

		}

		$this->layout->title = 'Tweestand | Twitter accounts manager';
		$this->layout->content = View::make('restricted.managetwitteraccounts', array('response_data' => $this->response_data));
		
	}

}