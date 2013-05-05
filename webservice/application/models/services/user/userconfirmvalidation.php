<?php

namespace Services\User;
use Services\Validation as Validation_Service;
 
class UserConfirmValidation extends Validation_Service {

 	/**
	 * validate_new_user
	 *
	 * @throws ValidateException
	 * @return void
	*/
	public function validate_user_confirm() {

		$this->rules = array(
			'confirm_token' => array('required', 'size:53'),
			'uid'		    => array('required','integer')
		);

		$this->messages = array(
    		'size' => 'invalid token.',
		);

		$this->validate();
	}

	
}