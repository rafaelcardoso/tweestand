<?php namespace Services\TwitterAccount;

use Services\Validation as Validation_Service;
 
class TwitterAccountValidation extends Validation_Service {

 	/**
	 * validate_auth_keys
	 *
	 * @throws ValidateException
	 * @return void
	*/
	public function validate_auth_keys() {

		$this->rules = array(
			'oauth_token'    => array('required','alpha_num'),
			'oauth_verifier' => array('required','alpha_num'),
			'user_id'		 => array('empty')
		);

		$this->validate();
	}

	public function validate_action_account() {

		$this->rules = array(
			'id' => array('required','integer')
		);

		$this->messages = array(
    		'integer' => 'invalid twitter account.',
    		'required' => 'you need to specify a twitter account'
		);

		$this->validate();	

	}

}