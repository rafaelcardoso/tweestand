<?php

class ValidateException extends Exception {
 
	/**
	 * @var object $errors
	 */
	private $errors;
 
	/**
	 * __construct
	 *
	 * @param  object  $container
	 * @return void
	 */
	public function __construct($container)
	{
		$this->errors = ($container instanceof Validator) ? $container->errors : $container;
 
		parent::__construct(null);
	}
 
	/**
	 * get
	 *
	 * @return object
	 */
	public function get()
	{
		return $this->errors;
	}
 
}