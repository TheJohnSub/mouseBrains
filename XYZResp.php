<?php

class XYZResp
{
	public $X;
	public $Y;
	public $Z;

	function __construct($x, $y, $z)
	{
		$this->X = $x;
		$this->Y = $y;
		$this->Z = $z;
	}

	function toJSON()
	{
	    return json_encode($this);
	}
}

?>