<?php 

class TwitterUtil {

	public function __construct() {

		define('CONSUMER_KEY', 'odjQeeQovobM7xSq7SzAZg');
		define('CONSUMER_SECRET', 'mQ74OyHx8uagCDhsMKSp89OlFLbjXI580p2DrPSc');

	}

	public function access_token($twitter_connection) {

		$twitter_connection->setToken(Input::get('oauth_token'));
		$tokens = $twitter_connection->getAccessToken(array('oauth_verifier' => Input::get('oauth_verifier')));

		return array(
			'oauth_token'		 => $tokens->oauth_token,
			'oauth_token_secret' => $tokens->oauth_token_secret,
			'twitter_id' 		 => $tokens->user_id
		);

	}

	public function authenticate_url($twitter_connection){

		return $twitter_connection->getAuthenticateUrl();

	}

	public function verify_credentials($twitter_connection, $params) {
		
		$credentials = json_decode($twitter_connection->get('/account/verify_credentials.json', $params)->responseText);
		
		$last_tweet_sent_id = null;
		
		if(array_key_exists('status', $credentials)) {

			$last_tweet_sent_id = $credentials->status->id;

		}

		return array(
			'identification' 	 => $credentials->id,
			'last_tweet_sent_id' => $last_tweet_sent_id,
			'name'				 => $credentials->name,
			'screen_name' 		 => $credentials->screen_name,
			'profile_image_url'  => $credentials->profile_image_url,
			'followers_count' 	 => $credentials->followers_count,
			'friends_count'		 => $credentials->friends_count
		);

	}

	public function users_lookup($twitter_connection, $params){
		
		$lookup = json_decode($twitter_connection->get('/users/lookup.json', $params)->responseText);

		$info = array();

		foreach ($lookup as $account) {
			$user = array(
				'name' 				=> $account->name,
				'screen_name' 		=> $account->screen_name,
				'profile_image_url' => $account->profile_image_url,
				'followers_count'	=> $account->followers_count,
				'friends_count' 	=> $account->friends_count,
				'identification' 	=> $account->id,
				'location'			=> $account->location,
				'lang'				=> $account->lang
			);

			$info[] = $user;
		}
		
		return $info;

	}

	public function user_timeline($twitter_connection, $params) {

		$tweets = json_decode($twitter_connection->get('/statuses/user_timeline.json', $params)->responseText);
		
		$tweets_sent 	 = 0;
		$retweets_sent 	 = 0;
		$mentions_sent 	 = 0;

		if(empty($tweets)) {

			$last_tweet_id = empty($params['since_id']) ? null : $params['since_id'];

		} else {

			$last_tweet_id = $tweets[0]->id;
			
			foreach ($tweets as $tweet) {
					
				if (array_key_exists('retweeted_status', $tweet)) {

					$retweets_sent += 1;

				} else if (empty($tweet->entities->user_mentions)) {

					$tweets_sent += 1;

				} else {

					$mentions_sent += count($tweet->entities->user_mentions);

				}

			}

		}
	
		return array('tweets_sent' => $tweets_sent, 'retweets_sent' => $retweets_sent, 'mentions_sent' => $mentions_sent, 'last_tweet_id' => $last_tweet_id);

	}

	public function retweets_of_me($twitter_connection, $params) {

		$retweets = json_decode($twitter_connection->get('/statuses/retweets_of_me.json', $params)->responseText);

		$retweets_sum = 0;

		if(empty($retweets)) {

				$last_retweet_id = empty($params['since_id']) ? null : $params['since_id'];
					
		} else {

			foreach ($retweets as $retweet) {

				$retweets_sum += $retweet->retweet_count;

			}

			$last_retweet_id = $retweets[0]->id;

		}

		return array('retweets_sum' => $retweets_sum, 'last_retweet_id' => $last_retweet_id);

	}

	public function mentions_timeline($twitter_connection, $params) {

		$mentions = json_decode($twitter_connection->get('/statuses/mentions_timeline.json', $params)->responseText);

		$mentions_sum = 0;

		if(empty($mentions)) {

			$last_mention_id = empty($params['since_id']) ? null : $params['since_id'];

		} else {
			
			$mentions_sum    = count($mentions);
			$last_mention_id = end($mentions)->id;

		}

		return array('mentions_sum' => $mentions_sum, 'last_mention_id' => $last_mention_id);

	}

	public function followers_id($twitter_connection, $params) {
	
		$followers_id = array();

		while($params['cursor'] != 0) {

  			$followers = json_decode($twitter_connection->get('/followers/ids.json', $params)->responseText);

  			$followers_id = array_merge($followers_id, $followers->ids);

  			$params['cursor'] = $followers->next_cursor;

		}

		return $followers_id;

	}
		
}