<?php
	/**
	* Chuyen cac thuc the hmtl trong mot chuoi sang dang thuc the cua chung
	*/
	function escape($string) {
		return htmlentities($string, ENT_QUOTES, 'UTF-8');
	}
?>