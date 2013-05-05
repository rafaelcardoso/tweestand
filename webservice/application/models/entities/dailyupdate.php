<?php 

class DailyUpdate extends Eloquent{

  public function twitter_account() {

    return $this->belongs_to('TwitterAccount');
    
  }

    
}