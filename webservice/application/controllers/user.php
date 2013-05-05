<?php 

class User_Controller extends Base_Controller {

	public $restful = true;
	
	public function post_new() {
/*
		$this->response_data = array(
				'status' => 'error',
				'messages' => array(
					'error' => array(
						array(
							'default' => 'Sorry but we exceeded our quota of registered users. If you want to know more about our limits and help to increase them, please visit our donation page.'
						)
					)
				)
			);

		return Response::json($this->response_data);*/

		try {

			$validation = new Services\User\UserInputValidation(Input::all());
 			$validation->validate_new_user();
 			
 			$user_repositorie = new Repositories\User\UserRepositorie();
 			$user = $user_repositorie->save();

 			$confirmation_email = new Services\User\ConfirmationEmailSender();
 			$confirmation_email->send($user);

		} catch (ValidateException $e) {

			$this->response_data = array(
				'status' => 'error',
				'messages' => array(
					'error' => $e->get()->messages
				)
			);

		} catch (Swift_SwiftException $e){

			$this->response_data = array(
				'status' => 'error',
				'messages' => array(
					'error' => array(
						array(
							'default' => 'your account has been created, but we can not send the confirmation email. please visit the link tweestand.com/confirmation for us to try sending again.'
						)
					)
				)
			);

		} catch(Exception $e) { #para ver o erro detalhado: } catch(Database\Exception $e) {

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

		if (!array_key_exists('status', $this->response_data)){

			$this->response_data = array(
				'status' => 'success',
				'messages' => array(
					'success' => array(
						array(
							'default' => 'we send a confirmation link to '.$user['user']->email.', please check this email to activate your account.'
						)
					)
				)
			);

		}
		 

		return Response::json($this->response_data);

	}

	public function get_confirm() {

		try {

			$validate = new Services\User\UserConfirmValidation(Input::all());
			$validate->validate_user_confirm();

			$user_repositorie = new Repositories\User\UserRepositorie();
 			$user = $user_repositorie->confirm(Input::all());


		} catch(ValidateException $e){

			$this->response_data = array(
				'error' => $e->get()
			);

		} catch(Exception $e){

			if($e->getCode()==666){$message=$e->getMessage();}
			else{$message='could not complete the operation, wait a few minutes and try again.';}

			$this->response_data = array(
					'error' => array(
						'messages' => array(
							'default' => $message
						)
					)
				);
		}

		if (!array_key_exists("error", $this->response_data)) {

			$this->response_data = array(
				'sucess' => array(
					'messages' => array(
						'default' => 'your account has been confirmed, you can now log in'
					)
				)
			);

		}

		return Response::json($this->response_data);

	}

	public function post_confirm() {

		try {

			$data = Input::all();

			$validation = new Services\User\UserInputValidation($data);
	 		$validation->validate_resend_instructions();

	 		$mail_sender = new Services\User\ConfirmationEmailSender();
	 		$mail_sender->resend($data['email']);
	 		
			
		} catch (ValidateException $e) {

			$this->response_data = array(
				'status' => 'error',
				'messages' => array(
					'error' => $e->get()->messages
				)
			);

		} catch (Swift_SwiftException $e) {

			$this->response_data = array(
				'status' => 'error',
				'messages' => array(
					'error' => array(
						array(
							'default' => 'we can not send the confirmation email. please try again in a few minutes.'
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

		if (!array_key_exists('status', $this->response_data)) {

			$this->response_data = array(
				'status' => 'success',
				'messages' => array(
					'success' => array(
						array(
							'default' => 'we send a confirmation link to '.$data['email'].' please check this email to activate your account.'
						)
					)
				)
			);

		}

		return Response::json($this->response_data);
		
	}

	public function post_auth() {

		try {

			$credentials = Input::all();

			$validation = new Services\User\UserInputValidation($credentials);
 			$validation->validate_login_user();
 			$user_auth = new Services\User\UserAuth();
 			$user = $user_auth->auth($credentials['username'], $credentials['password']);
 			
 			if(!empty($user['twitter_accounts'])){

 				$twitter_account = new Services\TwitterAccount\TwitterAccount();
 				$twitter_account->get_account_information($user);

 			}
 			
			
		} catch (ValidateException $e) {
			 
			$this->response_data = array(
				'status' => 'error',
				'messages' => array(
					'error' => $e->get()->messages
				)
			);

		} catch (AuthException $e) {

			$this->response_data = array(
				'status' => 'error',
				'messages' => array(
					'error' => array(
						'default' => array(
							$e->get()
						)
					)
				)
			);

		} catch (EpiTwitterNotAuthorizedException $e){

			$this->response_data = array(
				'status' => 'sucess',
				'messages' => array(
					'success' => array(
						array(
							'default' => $user['name'].' logged sucessfully'
						)
					),
					'error' => array(
						array(
							'default' => 'you has revoked the tweestand access to your twitter account, please go to twitter and authorize the tweestand to use your account again on settings > applications.'
						)
					)
				),
				'user' => $user
			);

		} catch (InactiveException $e) {

			$this->response_data = array(
				'status' => 'sucess',
				'messages' => array(
					'success' => array(
						array(
							'default' => $user['name'].' logged sucessfully'
						)
					),
					'error' => array(
						array(
							'default' => $e->getMessage()
						)
					)
				),
				'user' => $user
			);
		} catch(EpiTwitterException $e){

			$this->response_data = array(
				'status' => 'error',
				'messages' => array(
					'error' => array(
						array(
							'default' => 'could not connect to twitter, wait a few minutes and try again.'
						)
					)
				)
			);

		} catch(Exception $e) { #para ver o erro detalhado: } catch(Database\Exception $e) {
			
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
				'status' => 'sucess',
				'messages' => array(
					'success' => array(
						array(
							'default' => $user['name'].' logged sucessfully'
						)
					)
				),
				'user' => $user
			);

		}


		return Response::json($this->response_data);
	
	}

	public function post_edit() {

		try {

			$user = Session::get('user');
			$validation = new Services\User\UserInputValidation(Input::all());
 			$validation->validate_edit_user_profile();

 			if($user->username !== Input::get('username')) {
 				$validation->validate_edit_user_username($user->id);
 			}

 			if($user->email !== Input::get('email')) {
 				$validation->validate_edit_user_email($user->id);
 			}

 			$user->name  	= Input::get('name');
 			$user->email 	= Input::get('email');
 			$user->username = Input::get('username');

 			$user->save();


		} catch (ValidateException $e) {
			 
			$this->response_data = array(
				'status' => 'error',
				'messages' => array(
					'error' => $e->get()->messages
				)
			);

		} catch(Exception $e) { #para ver o erro detalhado: } catch(Database\Exception $e) {
			
			$this->response_data = array(
				'status' => 'error',
				'messages' => array(
					'error' => array(
						array(
							'default' => $e->getMessage()#'could not complete the operation, wait a few minutes and try again.'
						)
					)
				)
			);

		}

		if (!array_key_exists('status', $this->response_data)) {

			$this->response_data = array(
				'status' => 'sucess',
				'messages' => array(
					'success' => array(
						array(
							'default' => 'profile edited sucessfully'
						)
					)
				)
			);

		}


		return Response::json($this->response_data);

	}

	public function post_edit_password() {

		try {

			$user = Session::get('user');
			$data = Input::get();
			
			$validation = new Services\User\UserInputValidation($data);
 			$validation->validate_edit_user_password();
				
			if (Hash::check($data['current_password'], $user->password)) {

				$user->password = Hash::make($data['password']);
 				$user->save();

			} else {

				$this->response_data = array(
					'status' => 'error',
					'messages' => array(
						'error' => array(
							array(
								'default' => 'invalid current password'
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

		} catch(Exception $e) { #para ver o erro detalhado: } catch(Database\Exception $e) {
			
			$this->response_data = array(
				'status' => 'error',
				'messages' => array(
					'error' => array(
						array(
							'default' => $e->getMessage()#'could not complete the operation, wait a few minutes and try again.'
						)
					)
				)
			);

		}

		if (!array_key_exists('status', $this->response_data)) {

			$this->response_data = array(
				'status' => 'sucess',
				'messages' => array(
					'success' => array(
						array(
							'default' => 'password edited sucessfully'
						)
					)
				)
			);

		}


		return Response::json($this->response_data);

	}

	public function post_forgot_password() {

		#TO DO!!
		$validation = new Services\User\UserInputValidation(array('username' => Input::get('username'), 'email' => Input::get('email')));
 		$validation->validate_forgot_password();		



	}

	
}