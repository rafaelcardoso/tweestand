<?php namespace Services\TwitterAccount;

use \EpiTwitter, Repositories\TwitterAccount\TwitterAccountRepositorie, \TooManyFollowersException;

class TwitterAccountCreator {
	
	public function create($user, $twitter_credentials, $twitter_util, $twitter_connection) {

		
		$twitter_connection = null;
		$twitter_connection = new EpiTwitter(CONSUMER_KEY, CONSUMER_SECRET, $twitter_credentials['oauth_token'], $twitter_credentials['oauth_token_secret']);
		$twitter_connection->useSSL(true);
		
		$twitter_authenticated = $twitter_util->verify_credentials($twitter_connection, array('skip_status' => false, 'include_entities' => false));
		
		$last_retweet_receveid = $twitter_util->retweets_of_me($twitter_connection, array('count' => 1, 'trim_user' => true, 'include_entities' => false, 'include_user_entities' => false));
		$last_mention_receveid = $twitter_util->mentions_timeline($twitter_connection, array('count' => 1, 'trim_user' => true, 'include_entities' => false));
		$followers_id 		   = $twitter_util->followers_id($twitter_connection, array('user_id' => $twitter_credentials['twitter_id'], 'cursor' => -1));

		if(count($followers_id) > 1000){
			throw new TooManyFollowersException("sorry, you have more than 1000 followers");
		}

		$twitter_account_repositorie = new \Repositories\TwitterAccount\TwitterAccountRepositorie();
		$enable = ($twitter_account_repositorie->has_enable_account($user->id)) ? 0 : 1;

		$twitter_account = array(
			'user_id' 		 		   => $user->id,
			'identification' 		   => $twitter_authenticated['identification'],
			'oauth_token' 	   	 	   => $twitter_credentials['oauth_token'],
			'oauth_token_secret' 	   => $twitter_credentials['oauth_token_secret'],
			'enable' 			 	   => $enable,
			'last_tweet_sent_id' 	   => $twitter_authenticated['last_tweet_sent_id'],
			'last_mention_received_id' => $last_mention_receveid['last_mention_id']
		);

		$twitter_account_repositorie->save($twitter_account, $twitter_authenticated, $followers_id);
		
		$account = array(
			'id'				 => $twitter_account_repositorie->get_account_id_by_identification($twitter_authenticated['identification'])[0]->id,
            'identification'	 => $twitter_authenticated['identification'],
            'enable'			 => $enable,
            'oauth_token'		 => $twitter_credentials['oauth_token'],
            'oauth_token_secret' => $twitter_credentials['oauth_token_secret'],
			'name'				 => $twitter_authenticated['name'],
			'screen_name' 		 => $twitter_authenticated['screen_name'],
			'profile_image_url'  => $twitter_authenticated['profile_image_url'],
			'followers_count' 	 => $twitter_authenticated['followers_count'],
			'friends_count' 	 => $twitter_authenticated['friends_count']
		);

		return $account;

	}
	
}
