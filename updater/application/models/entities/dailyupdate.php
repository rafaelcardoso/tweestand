<?php

class DailyUpdate extends Eloquent{

  public static $timestamps = false;
  public static $table = 'daily_updates';

  public function twitter_account() {

    return $this->belongs_to('TwitterAccount');
    
  }

    
}