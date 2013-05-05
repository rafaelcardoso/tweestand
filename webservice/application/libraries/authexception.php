<?php


class AuthException extends Exception {
 
	/**
	 * @var object $errors
	 */
	private $error;
 
	/**
	 * __construct
	 *
	 * @param  object  $container
	 * @return void
	 */
	public function __construct($error)
	{
		$this->error = $error;
		parent::__construct(null);
	}
 
	/**
	 * get
	 *
	 * @return object
	 */
	public function get()
	{
		return $this->error;
	}
 
}