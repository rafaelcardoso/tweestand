<?php 

return array(

	/*
	|--------------------------------------------------------------------------
	| Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| The following language lines contain the default error messages used
	| by the validator class. Some of the rules contain multiple versions,
	| such as the size (max, min, between) rules. These versions are used
	| for different input types such as strings and files.
	|
	| these language lines may be easily changed to provide custom error
	| messages in your application. Error messages for custom validation
	| rules may also be added to this file.
	|
	*/

	"accepted"       => "the :attribute must be accepted.",
	"active_url"     => "the :attribute is not a valid URL.",
	"after"          => "the :attribute must be a date after :date.",
	"alpha"          => "the :attribute may only contain letters.",
	"alpha_dash"     => "the :attribute may only contain letters, numbers, and dashes.",
	"alpha_num"      => "the :attribute may only contain letters and numbers.",
	"alpha_space"      => "the :attribute may only contain letters and spaces.",
	"array"          => "the :attribute must have selected elements.",
	"before"         => "the :attribute must be a date before :date.",
	"between"        => array(
		"numeric" => "the :attribute must be between :min - :max.",
		"file"    => "the :attribute must be between :min - :max kilobytes.",
		"string"  => "the :attribute must be between :min - :max characters.",
	),
	"confirmed"      => "the :attribute confirmation does not match.",
	"count"          => "the :attribute must have exactly :count selected elements.",
	"countbetween"   => "the :attribute must have between :min and :max selected elements.",
	"countmax"       => "the :attribute must have less than :max selected elements.",
	"countmin"       => "the :attribute must have at least :min selected elements.",
	"date_format"   => "The :attribute must have a valid date format.",
	"different"      => "the :attribute and :other must be different.",
	"email"          => "the :attribute format is invalid.",
	"exists"         => "the selected :attribute is invalid.",
	"image"          => "the :attribute must be an image.",
	"in"             => "the selected :attribute is invalid.",
	"integer"        => "the :attribute must be an integer.",
	"ip"             => "the :attribute must be a valid IP address.",
	"match"          => "the :attribute format is invalid.",
	"max"            => array(
		"numeric" => "the :attribute must be less than :max.",
		"file"    => "the :attribute must be less than :max kilobytes.",
		"string"  => "the :attribute must be less than :max characters.",
	),
	"mimes"          => "the :attribute must be a file of type: :values.",
	"min"            => array(
		"numeric" => "the :attribute must be at least :min.",
		"file"    => "the :attribute must be at least :min kilobytes.",
		"string"  => "the :attribute must be at least :min characters.",
	),
	"not_in"         => "the selected :attribute is invalid.",
	"numeric"        => "the :attribute must be a number.",
	"required"       => "the :attribute field is required.",
	"same"           => "the :attribute and :other must match.",
	"size"           => array(
		"numeric" => "the :attribute must be :size.",
		"file"    => "the :attribute must be :size kilobyte.",
		"string"  => "the :attribute must be :size characters.",
	),
	"unique"         => "the :attribute has already been taken.",
	"url"            => "the :attribute format is invalid.",

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| Here you may specify custom validation messages for attributes using the
	| convention "attribute_rule" to name the lines. This helps keep your
	| custom validation clean and tidy.
	|
	| So, say you want to use a custom validation message when validating that
	| the "email" attribute is unique. Just add "email_unique" to this array
	| with your custom message. the Validator will handle the rest!
	|
	*/

	'custom' => array(),

	/*
	|--------------------------------------------------------------------------
	| Validation Attributes
	|--------------------------------------------------------------------------
	|
	| The following language lines are used to swap attribute place-holders
	| with something more reader friendly such as "E-Mail Address" instead
	| of "email". Your users will thank you.
	|
	| The Validator class will automatically search this array of lines it
	| is attempting to replace the :attribute place-holder in messages.
	| It's pretty slick. We think you'll like it.
	|
	*/

	'attributes' => array(),

);