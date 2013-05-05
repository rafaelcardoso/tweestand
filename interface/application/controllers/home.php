<?php

class Home_Controller extends Base_Controller {

	public $restful = true;

	public function get_index() {

		$this->layout->title = 'Tweestand | Twitter Analytics tool';
		$this->layout->content = View::make('public.index');

	}

	public function get_login() {
		
		$this->layout->title = 'Tweestand | Sign in';
		$this->layout->content = View::make('public.login', Session::get('msg'));

	}

	public function post_login() {

		try {

			$credentials = Input::all();

			$validation = new Services\User\UserInputValidation($credentials);
 			$validation->validate_login_user();

			if (Auth::attempt($credentials)) {
				
				return Redirect::to_action('dashboard@home');

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
							'default' => 'could not complete the operation, wait a few minutes and try again.'
						)
					)
				)
			);

		} catch (Exception $e) {

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

		if($this->response_data['status'] == 'error') {
			
			$this->layout->title = 'login page';
			$this->layout->content = View::make('public.login', array('response_data' => $this->response_data));	

		}
		
	}

	public function get_register() {

		$this->layout->title = 'Tweestand | Sign up';
		$this->layout->content = View::make('public.register');

	}

	public function post_register() {

		try {

			$data = Input::all();

			$validation = new Services\User\UserInputValidation($data);
 			$validation->validate_new_user();

 			$request = new RestRequest('http://localhost/webservice/public/user/new', 'POST');
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

		if($this->response_data['status'] === 'error') {

			$this->layout->title = 'register page';
			$this->layout->content = View::make('public.register', array(
				'response_data' => $this->response_data,
				'fields' 		=> $data
			));	

		} else {

			$this->layout->title = 'successfully registered';
			$this->layout->content = View::make('public.registered');	

		}

	}

	public function get_confirm(){

		$this->layout->title = 'Tweestand | Confirmation instructions';
		$this->layout->content = View::make('public.resendinstructions');	

	}

	public function post_confirm(){

		try {

			$data = array(
				'email' => Input::get('email')
			);


			$validation = new Services\User\UserInputValidation($data);
 			$validation->validate_resend_instructions();

 			$request = new RestRequest('http://localhost/webservice/public/user/confirm', 'POST');
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

			die(print_r($e));

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

		if($this->response_data['status'] === 'error') {

			$this->layout->title = 'Tweestand | Confirmation instructions';
			$this->layout->content = View::make('public.resendinstructions', array(
				'response_data' => $this->response_data,
				'fields' 		=> $data
			));				

		} else {

			$this->layout->title = 'Tweestand | Confirmation email sent';
			$this->layout->content = View::make('public.confirmationsent');	


		}


	}

	public function get_features() {
		
		$this->layout->title = 'Tweestand | Features';
		$this->layout->content = View::make('public.features');	

	}

	public function get_how() {
		
		$this->layout->title = 'Tweestand | How it works';
		$this->layout->content = View::make('public.how-it-works');

	}

	public function get_donate() {
		
		$this->layout->title = 'Tweestand | Donate';
		$this->layout->content = View::make('public.donate');

	}

}