<?php
	/**
	* 	Doi tuong xu ly cac user
	*	@category classes
	*	@var object $_db Doi tuong ket noi database
	*	@var object $_data Du lieu truy van tu database
	*	@var string $_sessionName Ten session dang xu ly
	*	@var string $_cookieName Ten cookie dang xu ly
	*	@var boolean $_isLoggedIn The hien da login hay chua
	*/
	class User {
		
		private $_db,
				$_data,
				$_sessionName,
				$_cookieName,
				$_isLoggedIn;

		/**
		*	Tao phien lam viec voi user
		*	@param string $user Ten user muon xu ly
		*/
		public function __construct($user = null) {
			$this->_db = DB::getInstance();
			$this->_sessionName = Config::get('session/session_name');
			$this->_cookieName = Config::get('remember/cookie_name');

			if(!$user) {
				if(Session::exists($this->_sessionName)) {
					$user = Session::get($this->_sessionName);
					if($this->find($user)) {
						$this->_isLoggedIn = true;
					} else {
						// process logout
					}
				} 
			}	else {
					$this->find($user);
				}
		}

		/**
		*	Update database
		*	@param array $fields Du lieu can update
		*	@param int $id Id cua record can update
		*/
		public function update($fields = array(), $id = null) {
			if(!$id && $this->isLoggedIn()) {
				$id = $this->data()->ID;
			}

			if(!$this->_db->update('users', $id, $fields)) {
				throw new Exception('There was a problem updating.');
			}
		}

		/**
		*	Tao mot user moi trong database
		*	@param array $fields Cac thong tin muon insert`
		*/
		public function create($fields = array()) {
			if(!$this->_db->insert('users', $fields)) {
				throw new Exception('There was a problem creating an account.');
			}
		}

		/**
		*	Tim du lieu trong database
		*	@param string $user Ten user muon tim trong database
		*	@return bool
		*/
		public function find($user = null) {
			if($user) {
				$field = (is_numeric($user)) ? 'id' : 'username';
				$data = $this->_db->get('users', array($field, '=', $user));

				if($data->count()) {
					$this->_data = $data->first();
					return true;
				}
			}
			return false;
		}

		/**
		*	Xu ly khi login thanh cong
		*	@param string $username Ten username khi dang nhap
		*	@param string $password Mat khau khi dang nhap
		*	@return bool
		*/
		public function login($username = null, $password = null, $remember) {
			if(!$username && !$password &&$this->exists()) {
				Session::put($this->_sessionName, $this->data()->ID);
			} else {
				$user = $this->find($username);
				if($user) {
					if($this->data()->password === Hash::make($password, $this->data()->salt)) {
						Session::put($this->_sessionName, $this->data()->ID);

						if($remember) {
							$hash = Hash::unique();
							$hashCheck = $this->_db->get('userssessions', array('userID', '=', $this->data()->ID));
							if(!$hashCheck->count()) {
								$this->_db->insert('userssessions', array(
									'userID' => $this->data()->ID,
									'hash' => $hash
									));
							} else {
								$hash = $hashCheck->first()->hash;
							}

							Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
						}

						return true;
					}
				}
			}

			return false;
		}
		
		/**
		*	Kiem tra permissions cua user
		*	@param string $key Ten permission can kiem tra
		*	@return bool
		*/
		public function hasPermission($key) {
			$group = $this->_db->get('groups', array('ID', '=', $this->data()->userGroup));
			if($group->count()) {
				$permission = json_decode($group->first()->permissions, true);
				if($permission[$key] == true) {
					return true;
				}
			}
			return false;
		}

		/**
		*	Tra ve ket qua du lieu co ton tai hay khong
		*	@return bool
		*/
		public function exists() {
			return (!empty($this->_data)) ? true : false;
		}

		/**
		*	Logout khoi he thong
		*	@return void
		*/
		public function logout() {
			$this->_db->delete('userssession', array('userID', '=', $this->data()->ID));

			Session::delete($this->_sessionName);
			Cookie::delete($this->_cookieName);
		}

		/**
		*	Tra ve du lieu truy van tu database
		*	@return Database|null|string
		*/
		public function data() {
			return $this->_data;
		}

		/**
		*	Ham kiem tra da login hay chua
		*	@return bool|Database|null|string
		*/
		public function isLoggedIn() {
			return $this->_isLoggedIn;
		}
	}
?>