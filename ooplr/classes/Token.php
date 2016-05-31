<?php
	/**
	*	Doi tuong xu ly token
	*	@category classes 
	*/
	class Token {
		
		/**
		*	Tao gia tri token va truyen vao session
		*	@return mixed
		*/
		public static function generate() {
			return Session::put(Config::get('session/token_name'), md5(uniqid()));
		}

		/**
		*	Kiem tra token
		*	@param string $token Gia tri cua token can kiem tra
		*	@return bool
		*/
		public static function check($token) {
			$tokenName = Config::get('session/token_name');

			if(Session::exists($tokenName) && $token === Session::get($tokenName)) {
				Session::delete($tokenName);
				return true;
			}

			return false;
		}
	}
?>