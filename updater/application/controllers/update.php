<?php

class Update_Controller extends Base_Controller {

	public $restful = true;

	public function get_update_twitter_accounts() {

		
		try {

    		$updater = new Services\Updater\Updater();
			$updater->update();

		} catch (Exception $e) {

     		echo ($e->getMessage());

     	}
		

		

	}



}