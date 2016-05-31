<?php
	/**
	*	Doi tuong xu ly viec validate du lieu dau vao
	*	@category classes
	*	@var boolean $_passed The hien du lieu dau vao co hop le hay khong
	*	@var array $_error Danh sach cac loi tim duoc
	*	@var object $_db Doi tuong ket noi database
	*/
	class Validate {
		private $_passed = false,
				$_errors = array(),
				$_db = null;

		/**
		*	Ham khoi tao ket noi database
		*/		
		public function __construct() {
			$this->_db = DB::getInstance();
		}

		/**
		*	Thuc hien kiem tra du lieu dau vao
		*	@param string $source Ten phuong thuc truyen du lieu
		*	@param array $items Tap hop cac dieu kien can kiem tra du lieu dau vao
		*	@return $this
		*/
		public function check($source, $items = array()) {
			foreach($items as $item => $rules) {
				foreach($rules as $rule => $rule_value) {
					$value = $source[$item];
					$item = escape($item);

					if($rule === 'required' && empty($value)) {
						$this->addError("{$item} is required");
					} else if(!empty($value)){
						switch ($rule) {
							case 'min':
								if(strlen($value) < $rule_value) {
									$this->addError("{$item} must be a minimum of {$rule_value} characters.");
								}
								break;
							
							case 'max' :
								if(strlen($value) > $rule_value) {
									$this->addError("{$item} must be a maximum of {$rule_value} characters.");
								}
								break;
							case 'matches' :
								if($value != $source[$rule_value]) {
									$this->addError("{$rule_value} must match {$item}");
								}
								break;
							case 'unique' :
								$check = $this->_db->get($rule_value, array($item, '=', $value));
								if($check->count()) {
									$this->addError("{$item} already exists");
									
								}
								break;
						}

					}
				}
			}
			if(empty($this->_errors)) {
					$this->_passed = true;
				}

			return $this;
		}

		/**
		*	Them loi tim thay vao danh sach loi
		*	@return void
		*/
		private function addError($error) {
			$this->_errors[] = $error;
		}

		/**
		*	Tra ve gia tri cac loi khi validate du lieu
		*	@return array
		*/
		public function errors() {
			return $this->_errors;
		}

		/**
		*	Tra ve gia tri validate true hay false
		*	@return bool
		*/
		public function passed() {
			return $this->_passed;
		}		
	}
?>