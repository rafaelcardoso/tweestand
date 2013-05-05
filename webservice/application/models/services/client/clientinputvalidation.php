<?php namespace Services\Client;

use Services\Validation as Validation_Service;
 
class ClientInputValidation extends Validation_Service {

 	/**
	 * validate_new_app
	 *
	 * @throws ValidateException
	 * @return void
	*/
	public function validate_keys() {

		$this->rules = array(
			'app_token' => array('required','size:40','alpha_num'),
			'app_rand' => array('required','max:100')
		);

		$this->validate();

	}

	public function validate_secure_request() {

		$this->rules = array(
			'uid'	=> array('required','integer'),
			'utoken' => array('required','size:53')
		);

		$this->messages = array(
    		'size' => 'invalid utoken'
		);

		$this->validate();

	}

}