<?php
	/**
	* 	Thuc hien bam du lieu
	*	@category classes
	*/
	class Hash {
		/**	
		*	Ham thuc hien bam du lieu
		*	@param string $string Doan du lieu can ban
		*	@param string $salt Muoi them vao truoc khi bam
		*	@return string
		*/
		public static function make($string, $salt = '') {
			return hash('sha256', $string.$salt);
		}

		/**
		*	Ham tao muoi de bam
		*	@param int $length Do dai chuoi can random
		*	@return string
		*/
		public static function salt($length) {
			return mcrypt_create_iv($length);
		}

		/**
		*	Tra ve mot chuoi nhan duoc sau khi bam mot chuoi dac biet
		*	@return string
		*/
		public static function unique() {
			return self::make(uniqid());
		}
	}
?>