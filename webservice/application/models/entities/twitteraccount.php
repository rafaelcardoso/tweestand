<?php 

class TwitterAccount extends Eloquent{

 	public static $table = 'twitter_accounts';
 	public static $timestamps = false;


  	public function user() {

    	return $this->belongs_to('User');
    
  	}

  	public function daily_updates(){

    	return $this->has_many('DailyUpdates');
    
	}


}

