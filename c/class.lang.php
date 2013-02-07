<?php
class lang {
	private $_language="tr";
	public function __construct($lang="tr")
	{
		$this->_language=$lang;
	}
	public static function trs($str="")
	{
		return $str;//değişkenler burada değiştirilicek burada tanımlanacak
	}
}
?>