<?php

class TwitterAccount extends Eloquent{

  public static $table = 'twitter_accounts';


  public function user() {

    return $this->belongs_to('User');
    
  }

  public function daily_updates(){

    return $this->has_many('DailyUpdates');
    
  }

}