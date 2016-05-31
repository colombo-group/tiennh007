<?php
	/**
	* 	Tao doi tuong config xu ly cac thong tin config
	*	@category classes 
	*/
	class Config {
		/**
		* Lay gia tri config theo duong dan
		* @param string $path Duong dan chuyen vao
		* @return string|false Neu ton tai index tra ve gia tri cua no, neu khong ton tai tra ve false
		*/
		public static function get($path = null) {
			if($path) {
				$config = $GLOBALS['config'];
				$path = explode('/', $path);

				//print_r($path);
				foreach($path as $bit) {
					if(isset($config[$bit])) {
						$config = $config[$bit];
						//var_dump($config);
						//echo "<br>";
					}				
				}

				return $config;
			}

			return false;

		}
	}
?>