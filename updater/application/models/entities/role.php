<?php 

class Role extends Eloquent{

	private $id;
	private $type;
	private $description;

	public function user()
  	{
    	return $this->has_many('User');
  	}
	
}