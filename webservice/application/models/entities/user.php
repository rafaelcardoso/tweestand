<?php 

class User extends Eloquent {
	
  public static $timestamps = true;

  public function role(){

    return $this->belongs_to('Role');

  }

  public function twitter_accounts(){

  	return $this->has_many('TwitterAccount');
  	
  }


   
}