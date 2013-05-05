<?php namespace Services\Updater;

use Repositorie\TwitterAccount\TwitterAccountRepositorie, \TwitterUtil, \EpiTwitter, \DB, \Config;

class Updater {


	private $params = array(
		'user_timeline' 	 => array('user_id' => null, 'count' => '200', 'trim_user' => 'true', 'exclude_replies' => 'false'),
		'mentions_received'  => array('count' => 200, 'trim_user' => true, 'include_entities' => false),
		'followers' 		 => array('user_id' => null, 'cursor' => -1)
  	);

	public function update($amount = 1) {

		$twitter_account_repositorie = new TwitterAccountRepositorie();
		$twitter_accounts = $twitter_account_repositorie->get_twitter_accounts_for_update($amount);

		$twitter_util = new TwitterUtil();
		
		Config::set('database.fetch', 7); # Set PDO Fetch Style to FETCH_NUM

		foreach ($twitter_accounts as $twitter) {

			//echo "ultimo tweet no banco: ".$twitter->last_tweet_sent_id.'<br/><br/>';

			$twitter_connection = new EpiTwitter(CONSUMER_KEY, CONSUMER_SECRET, $twitter->oauth_token, $twitter->oauth_token_secret);
			
			if(!is_null($twitter->last_mention_received_id)) {$this->params['mentions_received']['since_id'] = $twitter->last_mention_received_id;}
			if(!is_null($twitter->last_tweet_sent_id)) {
				$this->params['user_timeline']['since_id'] = $twitter->last_tweet_sent_id;
			}


			$this->params['followers']['user_id'] = $twitter->identification;
			$this->params['user_timeline']['user_id'] = $twitter->identification;

			$user_timeline		= $twitter_util->user_timeline($twitter_connection, $this->params['user_timeline']);
			$mentions_timeline  = $twitter_util->mentions_timeline($twitter_connection, $this->params['mentions_received'], $user_timeline['data'], $twitter->last_update);
			$updated_followers  = $twitter_util->followers_id($twitter_connection, $this->params['followers']);
			$database_followers = $twitter_account_repositorie->get_followers($twitter->id);
			
			$followers_won = array_diff($updated_followers, $database_followers);
			$followers_lost = array_diff($database_followers, $updated_followers);
			//$current_followers = (count($database_followers) + count($followers_won)) - count($followers_lost);
			$current_followers = count($updated_followers);
			
			$today = date("Y-m-d");

			foreach ($mentions_timeline['data'] as $index => $update) {
				
				$mentions_timeline['data'][$index]['twitter_account_id'] = $twitter->id;
				$mentions_timeline['data'][$index]['date'] = $index;
				if($index == $today){
					$mentions_timeline['data'][$index]['followers_count'] = $current_followers;	
				}else{
					$mentions_timeline['data'][$index]['followers_count'] = count($database_followers);	
				}
				

			}

			$data_update = array(
				'twitter_account_updated' => array(
					'id'					   => $twitter->id,
					'enable'				   => 1,
					'last_tweet_sent_id' 	   => $user_timeline['last_tweet_sent_id'],
					'last_mention_received_id' => $mentions_timeline['last_mention_received_id']
				),
				'daily_update' => $mentions_timeline['data'],
				'followers' => array(
					'won'  			=> $followers_won,
					'lost' 			=> $followers_lost,
					'current_count' => $current_followers
				)
			);

			

			$twitter_account_repositorie->save_update($data_update);

			unset(
				  $this->params['mentions_received']['since_id'],
				  $this->params['user_timeline']['since_id']
			);




			
		}


	}
	
		
}
