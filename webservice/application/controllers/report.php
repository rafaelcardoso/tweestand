<?php

class Report_Controller extends Base_Controller {

	public $restful = true;

	public function post_report() {

		try {

			$data = Input::get();
			$period = new DatePeriod(new DateTime($data['from']), new DateInterval('P1D'), new DateTime($data['to']));
			$totalDays = 0;
			foreach($period as $date){$totalDays++;}
			if($totalDays>30){
				$this->response_data = array('status' => 'error','messages' => array('error' => array(array('default' => 'Sorry, but the number of days limit is 30 per report.'))));
				return Response::json($this->response_data);
			}

			$validate = new Services\Report\ReportInputValidation($data);
			$validate->validate_report();
			$report_repositorie = new \Repositories\Report\ReportRepositorie();
			$report = $report_repositorie->get_report(Session::get('user')->id, $data['twitter_id'], $data['from'], $data['to']);

			/*if(!empty($report)) {

				$twitter_account_repositorie = new Repositories\TwitterAccount\TwitterAccountRepositorie();
				$account_tokens = $twitter_account_repositorie->get_tokens_by_account_id($data['twitter_id']);

				if(!empty($account_tokens)) {
						
					$won_followers_id = array();
					$lost_followers_id = array();

					for ($i=0; $i == 0; $i++) {
						foreach ($report as $update) {
							if (array_key_exists($i, $update->lost_followers)) {
								$lost_followers_id[] = $update->lost_followers[$i]->id;
							}
							if (array_key_exists($i, $update->won_followers)) {
								$won_followers_id[] = $update->won_followers[$i]->id;
							}

							if(count($lost_followers_id) == 10 AND count($lost_followers_id) == 10){
								break;
							}
						}
						if(count($lost_followers_id) == 10 AND count($lost_followers_id) == 10){
							break;
						}
					}


					$followers = array_merge(array_unique($lost_followers_id), array_unique($won_followers_id));

					
					if(!empty($followers)){

						$twitter_util = new TwitterUtil();
	 					$twitter_connection = new EpiTwitter(CONSUMER_KEY, CONSUMER_SECRET, $account_tokens[0]->oauth_token, $account_tokens[0]->oauth_token_secret);
						$lookup = $twitter_util->users_lookup($twitter_connection, array('include_entities' => false, 'user_id' => implode(',', $followers)));

						foreach ($lookup as $account) {
							foreach ($report as $update) {

								foreach($update->lost_followers as $follower){
									if($follower->id == $account['identification']){
										$follower->name = $account['name'];
										$follower->screen_name = $account['screen_name'];
										$follower->profile_image_url = $account['profile_image_url'];
										$follower->followers_count = $account['followers_count'];
										$follower->friends_count = $account['friends_count'];
									}
								}
							
								foreach($update->won_followers as $follower){
									if($follower->id == $account['identification']){
										$follower->name = $account['name'];
										$follower->screen_name = $account['screen_name'];
										$follower->profile_image_url = $account['profile_image_url'];
										$follower->followers_count = $account['followers_count'];
										$follower->friends_count = $account['friends_count'];
									}
								}
							}
						}
					}
				}
			}*/

		} catch (ValidateException $e) {
			 
			$this->response_data = array(
				'status' => 'error',
				'messages' => array(
					'error' => $e->get()->messages
				)
			);

		} catch (EpiTwitterNotAuthorizedException $e) {

			$this->response_data = array(
				'status' => 'error',
				'messages' => array(
					'error' => array(
						array(
							'default' => 'you has revoked the tweestand access to your twitter account, please go to twitter and authorize the tweestand to use your account again on settings > applications.'
						)
					)
				)
			);

		 } catch (Exception $e) {
			$this->response_data = array(
				'status' => 'error',
				'messages' => array(
					'error' => array(
						array(
							'default' => $e->getMessage()#'could not complete the operation, wait a few minutes and try again.'
						)
					)
				)
			);
			
		}

		if (!array_key_exists('status', $this->response_data)) {

			$this->response_data = array(
				'status' => 'success',
				'messages' => array(
					'success' => array(
						array(
							'default' => 'report generated successfully'
						)
					)
				),
				'report' => $report
			);

		}

		return Response::json($this->response_data);
		

	}

}