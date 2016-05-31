<?php
	/**
	*	Quan ly cookie
	*	@category classes
	*/
	class Cookie {

		/**
		*	Kiem tra cookie co ton tai hay khong
		*	@param string $name Ten cookie muon kiem tra
		*	@return bool True neu ton tai, False neu khong ton tai
		*/
		public static function exists($name) {
			return (isset($_COOKIE[$name])) ? true : false;
		}

		/**
		*	Tra ve mot cookie
		*	@param string $name Ten cookie muon tra ve
		*	@return string Gia tri cua cookie
		*/
		public static function get($name) {
			return $_COOKIE[$name];
		}

		/**
		*	Tao mot cookie
		*	@param string $name Ten cookie muon tao
		*	@param string $value Gia tri cua cookie
		*	@param int $expiry Thoi gian ton tai cua cookie
		*	@return bool 
		*/
		public static function put($name, $value, $expiry) {
			if(setcookie($name, $value, time() + $expiry, '/')) {
				return true;
			}
			return false;
		}

		/**
		*	Xoa mot cookie
		*	@param string $name Ten cookie muon xoa 
		*/
		public static function delete($name) {
			self::put($name, '', time() -1);
		}
	}
?>