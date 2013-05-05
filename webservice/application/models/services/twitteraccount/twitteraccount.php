<?php namespace Services\TwitterAccount;

use \EpiTwitter, \TwitterUtil;

class TwitterAccount {
	
	public function get_account_information($user) {

		$accounts_enable_status = array();
	 	$accounts_identification = array();

		foreach ($user['twitter_accounts'] as $account) {
			$accounts_identification[] = $account->identification;
			$accounts_enable_status[$account->id] = $account->enable;
		}
	 			
		if(in_array(1,$accounts_enable_status)) {

			$active_account_key_id = array_search(1, $accounts_enable_status);

			foreach ($user['twitter_accounts'] as $account) {
				if($account->id == $active_account_key_id) {
					$oauth_token = $account->oauth_token;
					$oauth_token_secret = $account->oauth_token_secret;
					break;
				}
			}

			$twitter_util = new TwitterUtil();
			$twitter_connection = new EpiTwitter(CONSUMER_KEY, CONSUMER_SECRET, $oauth_token, $oauth_token_secret);
				
			//pegando as informações da conta do usuário
			$lookup = $twitter_util->users_lookup($twitter_connection, array('include_entities' => false, 'user_id' => implode(',', $accounts_identification)));
			for ($i=0; $i < count($user['twitter_accounts']); $i++) {
				$user['twitter_accounts'][$i]->name = $lookup[$i]['name'];
				$user['twitter_accounts'][$i]->screen_name = $lookup[$i]['screen_name'];
				$user['twitter_accounts'][$i]->profile_image_url = $lookup[$i]['profile_image_url'];
				$user['twitter_accounts'][$i]->followers_count = $lookup[$i]['followers_count'];
				$user['twitter_accounts'][$i]->friends_count = $lookup[$i]['friends_count'];
			}

			//pegando informações dos seguidores da conta do usuário
			foreach ($user['twitter_accounts'] as $account) {
				if($account->id == $active_account_key_id) {
					$followers_collections = array_chunk($account->followers, 100);
					$i=0;
					foreach ($followers_collections as $collection) {
						
						foreach ($collection as $follower) {
							$ids[$i][] = $follower->id;
						}
						$lookup = $twitter_util->users_lookup($twitter_connection, array('include_entities' => false, 'user_id' => implode(',', $ids[$i])));
						

						foreach ($collection as $follower) {
							foreach ($lookup as $account_lookup) {
								if($account_lookup['identification'] == $follower->id){
									$follower->name = $account_lookup['name'];
									$follower->screen_name = $account_lookup['screen_name'];;
									$follower->profile_image_url = $account_lookup['profile_image_url'];;
									$follower->location = $account_lookup['location'];;
									$follower->lang = $account_lookup['lang'];;
								}
							}
						}
						$i++;
					}
				}
			}


		}

		return $user;

	}

	public function get_followers_information($user){



	}
}