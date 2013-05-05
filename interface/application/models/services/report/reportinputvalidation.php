<?php

namespace Services\Report;
use Services\Validation as Validation_Service;
 
class ReportInputValidation extends Validation_Service {

 	/**
	 * validate_get_report
	 *
	 * @throws ValidateException
	 * @return void
	*/
	public function validate_report() {

		$this->rules = array(
			'from'		 => array('required','date_format:"Y-m-d"'),
			'to'  		 => array('required','date_format:"Y-m-d"','before:'.date("Y-m-d", strtotime("+1 day")))
		);

		$this->messages = array(
			'before'	  => 'the end date can not exceed the current date.',
			'date_format' => 'the date range must follow the pattern yyyy-mm-dd'
		);

		$this->validate();

	}
	

}

