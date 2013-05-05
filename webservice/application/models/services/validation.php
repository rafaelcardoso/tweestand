<?php namespace Services;

use Validator, Exception, ValidateException;

abstract class Validation {
 
  /**
   * @var object $validator
   */
  protected $validator;
 
  /**
   * @var array $data
   */
  protected $data;
 
  /**
   * @var array $rules
   */
  public $rules = array();
 
  /**
   * @var array $messages
   */
  public $messages = array();
 
  /**
   * __construct
   *
   * @param  array  $input
   * @return void
   */
  public function __construct($input)
  {
    $this->input = $input;
  }
 
  /**
   * validate
   *
   * @return void
   */
  protected function validate()
  {
    $this->validator = Validator::make($this->input, $this->rules, $this->messages);
 
    if($this->validator->invalid())
    {
      throw new ValidateException($this->validator);
    }
  }
 
  /**
   * __set
   *
   * @param  string  $key
   * @param  mixed   $value
   * @return void
   */
  public function __set($key, $value)
  {
    $this->data[$key] = $value;
  }
 
  /**
   * __get
   *
   * @param  string  $key
   * @return mixed
   */
  public function __get($key)
  {
    if(!array_key_exists($key, $this->data))
    {
      throw new Exception('Could not get [' . $key . '] from Services\\Validation data array.');
    }
 
    return $this->data[$key];
  }
 
}