<?php

class Response
{
	public $ResponseCode;
	public $ResponseMessage;
	public $ObjectInsertID;
	public $ResponseObject;


	function __construct($code, $message, $object)
	{
		$this->ResponseCode = $code;
		$this->ResponseMessage = $message;
		$this->ResponseObject = $object;
	}

	function toJSON()
	{
	    return json_encode($this);
	}
}

?>