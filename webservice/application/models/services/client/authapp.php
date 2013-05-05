<?php

namespace Services\Client;

use \Input, \Hash, \DB, \ValidateException;

class AuthApp {

	private $secret_token    = '^&E:F,4*¢)A.Ç;h>"_T$5d#("%p}s1|5<-~m';
	private $app_credentials = array();
	private $response_data   = array();

	public function __construct($rand, $token) {

		$this->app_credentials['app_rand']  = $rand;
		$this->app_credentials['app_token'] = $token;

	}
	
	public function auth() {
			
    	try {
    	/*
			$client_input_validation = new ClientInputValidation($this->app_credentials);
			$client_input_validation->validate_keys();
			
			if ((sha1($this->secret_token . $this->app_credentials['app_rand'])) == $this->app_credentials['app_token']) {

				if (DB::query('SELECT COUNT(*) AS aggregate FROM access_keys WHERE token = ? LIMIT 1',array($this->app_credentials['app_token']))[0]->aggregate) {

					throw new ValidateException('invalid application');
				
				} else {

					if (!DB::query('insert into access_keys values (?)', array($this->app_credentials['app_token']))) {

						throw new Exception('could not complete the operation, wait a few minutes and try again.');

					}

				}
            
        	} else {

				throw new ValidateException('invalid application');

        	}*/
			
		} catch (ValidateException $e) {
			 
		$this->response_data = array(
			'error' => array(
				'messages' => array(
					'default' => 'invalid application.' #$e->get()
				)
			)
		);

		} catch(Exception $e) {

			$this->response_data = array(
				'error' => array(
					'messages' => array(
						'default' => 'could not complete the operation, wait a few minutes and try again.'
					)
				)
			);
		}

		return $this->response_data;

	}

	public function generate_tokens() {

		$avalue = substr(Hash::make(uniqid(rand(), true)), -53);
		$atoken = sha1($this->secret_token . $avalue);
		return array('avalue' => $avalue, 'atoken' => $atoken);

	}

}