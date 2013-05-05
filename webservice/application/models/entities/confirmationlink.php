<?php

class ConfirmationLink extends Eloquent {
	
	public static $table = 'confirmation_links';
	public static $timestamps = false;

	public function user() {

    	return $this->belongs_to('User');

  }
	
}
