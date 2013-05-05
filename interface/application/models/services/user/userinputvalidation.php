<?php

namespace Services\User;
use Services\Validation as Validation_Service;
 
class UserInputValidation extends Validation_Service {

 	/**
	 * validate_new_user
	 *
	 * @throws ValidateException
	 * @return void
	*/
	public function validate_new_user() {

		$this->rules = array(
			'name'       => array('required','between:3,20','alpha_space'),
			'username'   => array('required','between:3,20','alpha_num'),
			'email'      => array('required','email','between:10,60'),
			'password'   => array('required','min:6'),
			'repassword' => array('required','same:password'),
			'terms'		 => array('accepted')
		);

		$this->messages = array(
    		'alpha_space' => 'The :attribute may only contain letters and spaces.',
    		'accepted'    => 'Accept the terms of service and privacy policy.'
		);


		$this->validate();
	}

	/**
	 * validate_edit_user
	 *
	 * @throws ValidateException
	 * @return void
	*/
	public function validate_edit_user_profile() {

		$this->rules = array(
			'name'       => array('required','between:3,20','alpha_space'),
			'username'   => array('required','between:3,20','alpha_num'),
			'email'      => array('required','email','between:10,60')
		);

		$this->messages = array(
    		'alpha_space' => 'The :attribute may only contain letters and spaces.',
    		'alpha_num'   => 'The :attribute may only contain letters and numbers.'
		);

		$this->validate();

	}

	/**
	 * validate_edit_user_password
	 *
	 * @throws ValidateException
	 * @return void
	*/
	public function validate_edit_user_password() {

		$this->rules = array(
			'current_password' => array('required','min:6'),
			'password'   	   => array('required','min:6'),
			'repassword' 	   => array('required','same:password')
		);

		$this->validate();

	}

	/**
	 * validate_edit_user_username
	 * @param  integer   $id
	 * @throws ValidateException
	 * @return void
	*/
	public function validate_edit_user_username($id) {

		$this->rules = array(
			'username'  => array('required','unique:users,username,'.$id,'between:3,20','alpha_num')
		);

		$this->validate();

	}

	/**
	 * validate_edit_user_email
	 * @param  integer   $id
	 * @throws ValidateException
	 * @return void
	*/
	public function validate_edit_user_email($id) {

		$this->rules = array(
			'email'  => array('required','unique:users,email,'.$id,'email','between:10,60')
		);

		$this->validate();

	}
	
	/**
	 * validate_edit_user_email
	 * @throws ValidateException
	 * @return void
	*/
	public function validate_login_user() {

		$this->rules = array(
			'username' => array('required','between:3,20','alpha_num'),
			'password' => array('required','min:6')
		);
 
		$this->validate();		

	}

	/**
	 * validate_edit_user_email
	 * @param  integer   $id
	 * @throws ValidateException
	 * @return void
	*/
	public function validate_forgot_password() {

		$this->rules = array(
			'username' => array('between:3,20','alpha_num'),
			'email'    => array('email','between:10,60')
		);
 
		$this->validate();		
	}

	/**
	 * validate_resend_instructions
	 *
	 * @throws ValidateException
	 * @return void
	*/
	public function validate_resend_instructions() {

		$this->rules = array(
			'email'  => array('required','email','between:10,60')
		);

		$this->validate();
		
	}

}