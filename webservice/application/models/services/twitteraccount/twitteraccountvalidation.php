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

	public function validate_twitter_account() {

		$this->rules = array(
			'twitter_id' => array('required','integer','unique:twitter_accounts,identification'),
			'uid' => array('required','integer','unique:twitter_accounts,user_id'),
		);

		$this->messages = array('unique' => 'this twitter account is already registered or you have exceeded the limit of registered accounts.');

		$this->validate();

	}

	public function validate_action_account() {

		$this->rules = array(
			'twitter_account_id' => array('required','integer','exists:twitter_accounts,id')
		);

		$this->messages = array(
			'required' => 'you need to specify a twitter account.',
    		'integer'  => 'invalid twitter account.',
    		'exists'   => 'this account does not exists.'
		);

		$this->validate();	

	}


}