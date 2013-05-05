<?php

class Base_Controller extends Controller {

	protected $consumer_key    = 'odjQeeQovobM7xSq7SzAZg';
	protected $consumer_secret = 'mQ74OyHx8uagCDhsMKSp89OlFLbjXI580p2DrPSc';

	/**
	 * Catch-all method for requests that can't be matched.
	 *
	 * @param  string    $method
	 * @param  array     $parameters
	 * @return Response
	 */
	public function __call($method, $parameters)
	{
		return Response::error('404');
	}

}