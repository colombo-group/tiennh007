<?php
	/**
	* 	Doi tuong xu ly session
	*	@category classes
	*/
	class Session {
		/**
		*	Kiem tra mot session da ton tai hay chua
		*	@param string $name Ten session muon kiem tra
		*	@return bool
		*/
		public static function exists($name) {
			return (isset($_SESSION[$name])) ? true : false;
		}

		/**
		*	Gan gia tri cho session 
		*	@param string $name Ten session muon gan gia tri
		*	@param string $value Gia tri muon gan vao session
		*	@return mixed
		*/
		public static function put($name, $value) {
			return $_SESSION[$name] = $value;
		}

		/**
		*	Tra ve gia tri cua session
		*	@param string $name Ten session muon tra ve
		*	@return mixed
		*/	
		public static function get($name) {
			return $_SESSION[$name];
		}

		/**
		*	Xoa mot session
		*	@param string $name Ten session muon xoa
		*	@return void
		*/
		public static function delete($name) {
			if(self::exists($name)) {
				unset($_SESSION[$name]);
			}
		}

		/**
		*	Tao mot session moi
		*	@param string $name Ten cua session muon tao
		*	@param string $string Gia tri cua session muon tao
		*	@return mixed
		*/
		public static function flash($name, $string = '') {
			if(self::exists($name)) {
				$session = self::get($name);
				self::delete($name);
				return $session;
			} else {
				self::put($name, $string);
			}

			//return '';
		}
	}
?>