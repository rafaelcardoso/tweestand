<?php namespace Repositorie\TwitterAccount;

use \DB, \Config;

class TwitterAccountRepositorie {
	
	public function get_twitter_accounts_for_update($amount){
		
		 //die('fuck the police');

		return DB::query(
			'SELECT 
				t.id, t.identification, t.oauth_token, t.oauth_token_secret, t.last_tweet_sent_id,
			 	t.last_mention_received_id, MAX(d.date) as last_update
			 FROM users u, twitter_accounts t, daily_updates d
			 WHERE u.enable = 1
			 AND u.id = t.user_id
			 AND d.twitter_account_id = t.id
			 AND t.enable = 1
			 AND t.id NOT IN (
			 	SELECT t.id
			 	FROM twitter_accounts t, daily_updates d
			 	WHERE d.twitter_account_id = t.id
			 	AND date(d.date) = current_date()
			 )
			GROUP BY t.id ASC
			LIMIT 1
		');
		 
	}



	public function get_followers($id = null) {

		return DB::query('SELECT id FROM current_followers WHERE twitter_account_id ='.$id);

	}

	public function save_update($data_update) {

		Config::set('database.fetch', 8);

		DB::transaction(function() use ($data_update) {

			$twitter_id = $data_update['twitter_account_updated']['id'];

			//die(print_r(expression))
				
			$teste = DB::query('UPDATE twitter_accounts 
				 SET
				 	last_tweet_sent_id = '.$data_update['twitter_account_updated']['last_tweet_sent_id'].',
				 	last_mention_received_id = '.$data_update['twitter_account_updated']['last_mention_received_id'].',
				 	enable = '.$data_update['twitter_account_updated']['enable'].'
				 WHERE id = '.$data_update['twitter_account_updated']['id']); #atualiza a conta do twitter com o id do ultimo tweet enviado, menção recebida e retweet enviado

			foreach ($data_update['daily_update'] as $update) {
				
				$affected = DB::query(
					'SELECT
						id, tweets_sent_count, mentions_sent_count, mentions_received_count, retweets_sent_count, retweets_receveid_count
					FROM daily_updates
					WHERE 
						twitter_account_id = '.$twitter_id.'
					AND
						date = "'.$update['date'].'"
					LIMIT 1'
				);

				if(!empty($affected[0])) {

					//echo $update['date']." já existe no banco, então vou só dar um update<br/><br/>";
					
					DB::query('UPDATE daily_updates SET tweets_sent_count = ?, mentions_sent_count = ?, mentions_received_count = ?, retweets_sent_count = ?, retweets_receveid_count = ?
						WHERE id = ?', array(
							'tweets_sent_count' => $affected[0]->tweets_sent_count + $update["tweets_sent_count"],
							'mentions_sent_count' => $affected[0]->mentions_sent_count + $update["mentions_sent_count"],
							'mentions_received_count' => $affected[0]->mentions_received_count + $update["mentions_received_count"],
							'retweets_sent_count' => $affected[0]->retweets_sent_count + $update["retweets_sent_count"],
							'retweets_receveid_count' => $affected[0]->retweets_receveid_count + $update["retweets_receveid_count"],
							'id' => $affected[0]->id,
						)
					);
					
				} else {
					
					//echo $update['date']." não existe no banco, então vou dar um insert<br/><br/>";
					$daily_update_id = DB::table('daily_updates')->insert_get_id($update); #cadastra o daily_update para poder utilizar o id como fk em seguidores perdidos e ganhos
				
				}

				

				$date = $update['date'];

			}

			if(!isset($daily_update_id)){
				$daily_update_id = DB::connection()->query(
					'SELECT id FROM daily_updates
					 WHERE twitter_account_id = '.$twitter_id.'
						AND date = '.$date
				);
			}
			
			if(!empty($data_update['followers']['lost'])) {

				DB::connection()->query('DELETE FROM current_followers WHERE twitter_account_id = '.$twitter_id.' AND id IN(?)',$data_update['followers']['lost']); #remove quem deu unfollow da lista de seguidores atuais

				$values = $this->mount_values($data_update['followers']['lost'], $daily_update_id);
				DB::connection()->query('INSERT INTO lost_followers (id, daily_update_id) VALUES '.$values); #adiciona na tabela de seguidores perdidos quem deixou de seguir

			}

			if(!empty($data_update['followers']['won'])) {

				$values = $this->mount_values($data_update['followers']['won'], $twitter_id);
				DB::connection()->query('INSERT INTO current_followers (id, twitter_account_id) VALUES '.$values); #adiciona quem deu follow a lista de seguidores atuais

				$values = $this->mount_values($data_update['followers']['won'], $daily_update_id);
				DB::connection()->query('INSERT INTO won_followers (id, daily_update_id) VALUES '.$values); #adiciona na tabela de seguidores ganhos quem passou a seguir

			}

			

		});

		Config::set('database.fetch', 7);

	}

	public function mount_values($data, $id) {

		$unfollowers_ids = '';
					
		foreach ($data as $value) {

			$unfollowers_ids .= '('.$value.','.$id.'),';

		}

		return rtrim($unfollowers_ids, ',');

	}

}