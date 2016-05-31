<?php
	/**
	*	Doi tuong input xu li cac du lieu input 
	*	@category classes
	*/
	class Input {
		
		/**
		*	Kiem tra xem co ton tai du lieu duoc truyen bang POST va GET
		*	@param string $type Kieu cua phuong thuc truyen du lieu
		*	@return bool
		*/
		public static function exists($type = 'post') {
			switch($type) {
				case 'post' :
					return (!empty($_POST)) ? true : false;
					break;
				case 'get':
					return (!empty($_GET)) ? true : false;
					break;
				default:
					return false;
					break;			
			}
		}

		/**
		*	Tra ve du lieu duoc truyen bang POST va GET
		*	@param string $get Ten cua the input
		*	@return string
		*/

		public static function get($item) {
			if(isset($_POST[$item])) {
				return $_POST[$item];
			} else if(isset($_GET[$item])) {
				return $_GET[$item];
			}
			return '';
		}
	}
?>