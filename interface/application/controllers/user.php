<?php

class User_Controller extends Base_Controller {

	public $restful = true;

	public function get_edit_profile() {

		$this->layout->title = 'Tweestand | User profile';
		$this->layout->content = View::make('restricted.editprofile');

	}

	public function post_edit_profile() {
		
		try {
			
			$user = Session::get('myauth_login');
			$data = Input::get();
			$data = array(
				'name'	   => $data['name'],
				'username' => $data['username'],
				'email'    => $data['email'],
				'uid'      => $user['keys']['uid'],
				'utoken'   => $user['keys']['utoken']
			);

			$validation = new Services\User\UserInputValidation($data);
 			$validation->validate_edit_user_profile();

 			$request = new RestRequest('http://localhost/webservice/public/user/edit', 'POST');
			$request->buildPostBody($data);
			$request->execute();
			$this->response_data = $request->getResponseBody();


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
				'error' => array(
					'messages' => array(
						array(
							'default' => 'could not complete the operation, wait a few minutes and try again.'
						)
					)
				)
			);

		}

		if($this->response_data['status'] != 'error') {

			$user['name'] = $data['name'];
			$user['username'] = $data['username'];
			$user['email'] = $data['email'];
			Session::put('myauth_login', $user);

		}

		$this->response_data['profile'] = true;

		$this->layout->title = 'Tweestand | User profile';
		$this->layout->content = View::make('restricted.editprofile', array('response_data' => $this->response_data));

		

	}

	public function post_edit_password() {

		try {
			
			$user = Session::get('myauth_login');
			$data = Input::get();
			$data = array(
				'current_password' => $data['current_password'],
				'password' 		   => $data['password'],
				'repassword'       => $data['repassword'],
				'uid'      	  	   => $user['keys']['uid'],
				'utoken'   		   => $user['keys']['utoken']
			);

			$validation = new Services\User\UserInputValidation($data);
 			$validation->validate_edit_user_password();

 			$request = new RestRequest('http://localhost/webservice/public/user/edit_password', 'POST');
			$request->buildPostBody($data);
			$request->execute();
			$this->response_data = $request->getResponseBody();


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
				'error' => array(
					'messages' => array(
						array(
							'default' => 'could not complete the operation, wait a few minutes and try again.'
						)
					)
				)
			);

		}

		$this->response_data['password'] = true;
		#die(print_r($this->response_data));

		$this->layout->title = 'Tweestand | User profile';
		$this->layout->content = View::make('restricted.editprofile', array('response_data' => $this->response_data));
		
	}


	public function get_edit_password() {

		$this->layout->title = 'Tweestand | User profile';
		$this->layout->content = View::make('restricted.editprofile', array('response_data' => $this->response_data));

	}

}