<?php

class TwitterAccount_Controller extends Base_Controller {

	public $restful = true;
	
	public function post_auth_url() {

		try {

			$twitter_util = new TwitterUtil();
			$twitter_connection = new EpiTwitter(CONSUMER_KEY, CONSUMER_SECRET);
			$url = $twitter_util->authenticate_url($twitter_connection);
			
		} catch (Exception $e) {

			$this->response_data = array(
				'status' => 'error',
				'messages' => array(
					'error' => array(
						array(
							'default' => 'We can not get the authorization url from twitter. Please, wait a few minutes and try again.'
						)
					)
				)
			);

		}

		if (!array_key_exists('status', $this->response_data)) {

			$this->response_data = array(
				'status' => 'sucess',
				'messages' => array(
					'sucess' => array(
						array(
							'default' => 'twitter authentication url taken successfully.'
						)
					)
				),
				'url' => $url
			);

		}

		return Response::json($this->response_data);		

	}

	public function post_new_twitter_account() {

		try {

		
			$validate = new Services\TwitterAccount\TwitterAccountValidation(Input::get());
			$validate->validate_auth_keys();

			$twitter_util = new TwitterUtil();
			$twitter_connection = new EpiTwitter(CONSUMER_KEY, CONSUMER_SECRET);
			$twitter_credentials = $twitter_util->access_token($twitter_connection);
			
			$validate = new Services\TwitterAccount\TwitterAccountValidation(array('twitter_id' => $twitter_credentials['twitter_id'], 'uid' => Session::get('user')->attributes['id']));
			$validate->validate_twitter_account();
			
			$twitter_account_creator = new Services\TwitterAccount\TwitterAccountCreator();
			$twitter_account = $twitter_account_creator->create(Session::get('user'), $twitter_credentials, $twitter_util, $twitter_connection);
			
		} catch (ValidateException $e) {
			
			$this->response_data = array(
				'status' => 'error',
				'messages' => array(
					'error' => $e->get()->messages
				)
			);

		} catch (EpiTwitterException $e) {
			
			$this->response_data = array(
				'status' => 'error',
				'messages' => array(
					'error' => array(
						array(
							'default' => $e->getMessage()#'a problem occurred while establishing the connection to twitter, wait a few minutes and try again.'
						)
					)
				)
			);

		} catch (EpiOAuthException $e){
			
			$this->response_data = array(
				'status' => 'error',
				'messages' => array(
					'error' => array(
						array(
							'default' => $e->getMessage()#'a problem occurred while establishing the connection to twitter, wait a few minutes and try again.'
						)
					)
				)
			);

		} catch (TooManyFollowersException $e){

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
		
		} catch (Exception $e) {

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

		if (!array_key_exists('status', $this->response_data)) {

			$this->response_data = array(
				'status' => 'success',
				'messages' => array(
					'success' => array(
						array(
							'default' => $twitter_account['screen_name'].' authorized sucessfully'
						)
					)
				),
				'twitter_account' => $twitter_account
			);

		}

		return Response::json($this->response_data);

	}

	public function post_deactivate(){

		try {
			
			$data = Input::get();
			$validate = new Services\TwitterAccount\TwitterAccountValidation($data);
			$validate->validate_action_account();

			$twitter_account_repositorie = new \Repositories\TwitterAccount\TwitterAccountRepositorie();
			$affected = $twitter_account_repositorie->change_account_status(Session::get('user')->id, $data['twitter_account_id'], 0);

			if(!$affected){
				$this->response_data = array(
					'status' => 'error',
					'messages' => array(
						'error' => array(
							array(
								'default' => 'this account is already deactivated.'
							)
						)
					)
				);
			}
			

		} catch (ValidateException $e) {

			$this->response_data = array(
				'status' => 'error',
				'messages' => array(
					'error' => $e->get()->messages
				)
			);

		} catch (Exception $e) {

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

		if (!array_key_exists('status', $this->response_data)) {

			$this->response_data = array(
				'status' => 'success',
				'messages' => array(
					'success' => array(
						array(
							'default' => 'account disabled successfully'
						)
					)
				),
				'account' => array(
					'id' => $data['twitter_account_id']
				)
			);

		}

		return Response::json($this->response_data);
		
	}

	public function post_activate(){

		try {
			
			$data = Input::get();
			$validate = new Services\TwitterAccount\TwitterAccountValidation($data);
			$validate->validate_action_account();

			$twitter_account_repositorie = new \Repositories\TwitterAccount\TwitterAccountRepositorie();
			$affected = $twitter_account_repositorie->change_account_status(Session::get('user')->id, $data['twitter_account_id'], 1);

			if(!$affected){
				$this->response_data = array(
					'status' => 'error',
					'messages' => array(
						'error' => array(
							array(
								'default' => 'this account is already activated.'
							)
						)
					)
				);
			}
			

		} catch (ValidateException $e) {

			$this->response_data = array(
				'status' => 'error',
				'messages' => array(
					'error' => $e->get()->messages
				)
			);

		} catch (Exception $e) {

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

		if (!array_key_exists('status', $this->response_data)) {

			$this->response_data = array(
				'status' => 'success',
				'messages' => array(
					'success' => array(
						array(
							'default' => 'account enabled successfully'
						)
					)
				),
				'account' => array(
					'id' => $data['twitter_account_id']
				)
			);

		}

		return Response::json($this->response_data);
		
	}

}