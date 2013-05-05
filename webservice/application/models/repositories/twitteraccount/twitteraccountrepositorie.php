<?php namespace Repositories\TwitterAccount;

use \DB;

class TwitterAccountRepositorie {

	public function save($twitter_account, $twitter_authenticated, $followers_id) {

		DB::transaction(function() use ($twitter_account, $twitter_authenticated, $followers_id) {
    			
    		$twitter_id = DB::table('twitter_accounts')->insert_get_id($twitter_account);

			$daily_update = array(
				'twitter_account_id'      => $twitter_id,
				'date' 				      => date("Y-m-d H:i:s"),
				'followers_count' 	 	  => $twitter_authenticated['followers_count'],
				'tweets_sent_count'  	  => 0,
				'mentions_sent_count' 	  => 0,
				'mentions_received_count' => 0,
				'retweets_sent_count' 	  => 0,
				'retweets_receveid_count' => 0
			);

			DB::table('daily_updates')->insert($daily_update);
			
			$current_followers = '';
					
			foreach ($followers_id as $value) {

				$current_followers .= '('.$value.','.$twitter_id.'),';
				
			}

			DB::connection()->query('INSERT INTO current_followers (id, twitter_account_id) VALUES '.rtrim($current_followers, ','));


		});

	}

	public function get_all() {
		
		 return DB::query(
			'SELECT t.id as twitter_id, t.identification, t.oauth_token, t.oauth_token_secret, t.last_tweet_sent_id,
			 t.last_mention_received_id, t.last_retweet_receveid_id
			 FROM twitter_accounts t, daily_updates d
			 WHERE t.enable = 1
			 AND d.twitter_account_id = t.id
			 AND DATE(d.date) < CURDATE()
			 LIMIT 200');
		
	}

	public function get_account_id_by_identification($identification) {

		return DB::query('SELECT id FROM twitter_accounts WHERE identification = ? LIMIT 1', array($identification));

	}

	public function get_tokens_by_account_id($id){

		return DB::query('SELECT oauth_token, oauth_token_secret FROM twitter_accounts WHERE id = ? LIMIT 1', array($id));

	}

	public function has_enable_account($user_id){

		if(DB::query('SELECT COUNT(*) as quantity FROM twitter_accounts WHERE enable = 1 AND user_id = ?', array($user_id))[0]->quantity > 0){
			return true;
		}else{
			return false;
		}

	}

	public function change_account_status($user_id, $twitter_account_id, $status) {

		$check_status = ($status == 0) ? 1 : 0;

		if(DB::query('SELECT COUNT(*) as quantity FROM twitter_accounts WHERE enable = ? AND id = ? LIMIT 1', array($check_status, $twitter_account_id))[0]->quantity > 0){

			return DB::query('UPDATE twitter_accounts SET enable = ? WHERE id = ? AND user_id = ? LIMIT 1', array($status, $twitter_account_id, $user_id));	

		} else {

			return false;

		}

	}

	
	
}