<?php namespace Repositories\Report;

use \DB;

class ReportRepositorie {

	public $updates = array();

	public function get_report($user_id, $twitter_account_id, $from, $to){

		$this->updates = DB::query(
			'SELECT DISTINCT d.id, d.date, d.followers_count, d.tweets_sent_count,
			 d.mentions_sent_count, d.mentions_received_count, d.retweets_sent_count, d.retweets_receveid_count
			 FROM daily_updates d, twitter_accounts t
			 WHERE t.user_id = ?
			 AND d.twitter_account_id = ?
			 AND d.date BETWEEN CAST(? AS DATE) AND CAST(? AS DATE) ORDER BY d.date',
			 array($user_id, $twitter_account_id, $from, $to)
		);

		if(!empty($this->updates)){

			foreach ($this->updates as $update) {
				$update->lost_followers = array();
				$update->won_followers = array();
				$updates_id[] = $update->id;
			}
		
			$lost_followers = DB::query(
				'SELECT l.id, l.daily_update_id
			 	 FROM lost_followers l
			 	 WHERE l.daily_update_id in('.implode(',', $updates_id).')'
			);

			$won_followers = DB::query(
				'SELECT w.id, w.daily_update_id
			 	 FROM won_followers w
			 	 WHERE w.daily_update_id in('.implode(',', $updates_id).')'
			);

			if(!empty($lost_followers)) {
				foreach ($this->updates as $update) {
					foreach ($lost_followers as $follower) {
						if($update->id == $follower->daily_update_id) {
							$update->lost_followers[] = $follower;
						}
					}
				}
			}

			if(!empty($won_followers)) {
				foreach ($this->updates as $update) {
					foreach ($won_followers as $follower) {
						if($update->id == $follower->daily_update_id) {
							$update->won_followers[] = $follower;
						}
					}
				}
			}

		}

		foreach ($this->updates as $update) {
			foreach ($update->lost_followers as $follower) {
				unset($follower->daily_update_id);
			}
		}
		foreach ($this->updates as $update) {
			foreach ($update->won_followers as $follower) {
				unset($follower->daily_update_id);
			}
		}

		return $this->updates;

	}

}