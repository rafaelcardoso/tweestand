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

	public function user_timeline($twitter_connection, $params) {
		
		$get_tweets = json_decode($twitter_connection->get('/statuses/user_timeline.json', $params)->responseText);
		
		$filtered_data = array();
		$last_tweet_sent_id = null;
		
		if(empty($get_tweets)) {

			$filtered_data[date("Y-m-d")] = array(
				'tweets_sent_count' => 0,
				'retweets_sent_count' => 0,
				'retweets_receveid_count' => 0,
				'mentions_sent_count' => 0,
				'mentions_received_count' => 0
			);

			$last_tweet_sent_id = empty($params['since_id']) ? null : $params['since_id'];
			
		} else {

			$last_tweet_sent_id = $get_tweets[0]->id;

			foreach ($get_tweets as $tweet) {

				$index = new DateTime($tweet->created_at);
				$index = $index->format('Y-m-d');

				if(array_key_exists($index, $filtered_data)) {

					$filtered_data[$index] = $this->filter_tweets($filtered_data, $tweet, $index);

				} else {

					$filtered_data[$index] = array(
						'tweets_sent_count' => 0,
						'retweets_sent_count' => 0,
						'retweets_receveid_count' => 0,
						'mentions_sent_count' => 0,
						'mentions_received_count' => 0
					);
					
					$filtered_data[$index] = $this->filter_tweets($filtered_data, $tweet, $index);

				}

			}

			if(!array_key_exists(date("Y-m-d"), $filtered_data)) {
				$filtered_data[date("Y-m-d")] = array(
					'tweets_sent_count' => 0,
					'retweets_sent_count' => 0,
					'retweets_receveid_count' => 0,
					'mentions_sent_count' => 0,
					'mentions_received_count' => 0
				);
			}

			//ksort($filtered_data);
			
		}


		return array('data' => $filtered_data, 'last_tweet_sent_id' => $last_tweet_sent_id);

	}

	public function mentions_timeline($twitter_connection, $params, $data, $last_update) {

		$mentions = json_decode($twitter_connection->get('/statuses/mentions_timeline.json', $params)->responseText);
		$last_mention_received_id = null;

		if(empty($mentions)) {

			$last_mention_received_id = empty($params['since_id']) ? null : $params['since_id'];

		} else {
			
			foreach ($mentions as $mention) {

				$index = new DateTime($mention->created_at);
				$index = $index->format('Y-m-d');

				if(array_key_exists($index, $data)){

					$data[$index]['mentions_received_count'] += 1;

				}else{

					$data[$index] = array(
						'tweets_sent_count' => 0,
						'retweets_sent_count' => 0,
						'retweets_receveid_count' => 0,
						'mentions_sent_count' => 0,
						'mentions_received_count' => 1
					);

				}

			}

			$last_mention_received_id = $mentions[0]->id;

		}
		
		$period = new DatePeriod(
     		new DateTime(date('Y-m-d', strtotime($last_update. ' + 1 days'))),
     		new DateInterval('P1D'),
     		new DateTime(date('Y-m-d'))
		);
		
		foreach($period as $date){
    		if(!array_key_exists($date->format("Y-m-d"), $data)){
    			$data[$date->format("Y-m-d")] = array(
					'tweets_sent_count' => 0,
					'retweets_sent_count' => 0,
					'retweets_receveid_count' => 0,
					'mentions_sent_count' => 0,
					'mentions_received_count' => 0
				);
    		}

		}

		ksort($data);

		return array('data' => $data, 'last_mention_received_id' => $last_mention_received_id);

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

	public function filter_tweets($filter, $tweet, $index) {

		if (array_key_exists('retweeted_status', $tweet)) {

			$filter[$index]['retweets_sent_count'] += 1;
			//echo "dia do twitter: ".$tweet->created_at.'<br/>';
			//echo "dia ".$index." retweetei: ".$tweet->text." <br/><br/>";

		} else if (empty($tweet->entities->user_mentions)) {
			
			$filter[$index]['tweets_sent_count'] += 1;
			$filter[$index]['retweets_receveid_count'] += $tweet->retweet_count;

			//echo "dia do twitter: ".$tweet->created_at.'<br/>';
			//echo "dia ".$index." enviei o tweet: ".$tweet->text." id: ".$tweet->id_str."<br/>";
			//echo "e recebi ".$tweet->retweet_count." retweets <br/><br/>";
			
		} else {

			$filter[$index]['mentions_sent_count'] += count($tweet->entities->user_mentions);
			$filter[$index]['retweets_receveid_count'] += $tweet->retweet_count;

			//echo "dia do twitter: ".$tweet->created_at." ".$tweet->text." id: ".$tweet->id_str."<br/>";
			//echo "dia ".$index." enviei a mention: ".$tweet->text." <br/>";
			//echo "e recebi ".$tweet->retweet_count." retweets <br/><br/>";

		}

		return $filter[$index];

	}

	
}