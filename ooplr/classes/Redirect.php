<?php
	/**
	* 	Doi tuong chuyen huong trang web
	*	@category classes
	*/
	class Redirect {

		/**
		*	Chuyen huong trang web
		*	@param string $location Trang muon chuyen den
		*	@return void
		*/
		public static function to($location = null) {
			if($location) {
				if(is_numeric($location)) {
					switch ($location) {
						case '404':
							header('HTTP/1.0 404 Not Found');
							include 'includes/errors/404.php';
							exit();
							break;
					}
				}
				header('Location:' . $location);
				exit();
			}
		}
	}
?>