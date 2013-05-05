<?php

class Dashboard_Controller extends Base_Controller {

	public $restful = true;

	public function get_home() {
		$this->layout->title = 'Tweestand | Dashboard';
		$this->layout->content = View::make('restricted.dashboard',array('response_data' => Session::get('msg')));
		
	}

	public function get_set_account(){

		

	}

	public function post_report() {

		try {
			
			$user = Session::get('myauth_login');
			$data = Input::get();

			foreach($user['twitter_accounts'] as $account){
				if($account['enable'] == 1){
					$twitter_id = $account['id'];
					break;
				}
			}
			
			$data = array(
				'from'	     => $data['from'],
				'to' 	     => $data['to'],
				'twitter_id' => $twitter_id,
				'uid'        => $user['keys']['uid'],
				'utoken'     => $user['keys']['utoken']
			);

			$validate = new Services\Report\ReportInputValidation($data);
			$validate->validate_report();

 			$request = new RestRequest('http://localhost/webservice/public/twitter/report', 'POST');
			$request->buildPostBody($data);
			$request->execute();
			$this->response_data = $request->getResponseBody();


		} catch (ValidateException $e) {
			 
			$this->response_data = array(
				'status' => 'error',
				'messages' => array(
					'error' => $e->get()->messages
				)
			);

		} catch (RequestErrorException $e) {

			$this->response_data = Session::get('error');

		} catch (RequestException $e) {

			$this->response_data = array(
				'status' => 'error',
				'messages' => array(
					'error' => array(
						array(
							'default' => 'could not complete the operation, wait a few minutes and try again.'
						)
					)
				)
			);

		} catch(Exception $e) {

			$this->response_data = array(
				'error' => array(
					'messages' => array(
						array(
							'default' => $e->getMessage() #'could not complete the operation, wait a few minutes and try again.'
						)
					)
				)
			);

		}

		return Response::json($this->response_data, 200, array());

	}



}