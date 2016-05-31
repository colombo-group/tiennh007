<?php
	/**
	* 	Tao doi tuong de ket noi database
	*	@category classes
	*	@var object $_instance The hien cua doi tuong thuoc class DB
	* 	@var object $_pdo Doi tuong ket noi database
	*	@var object $_query Doi tuong truy van database
	*	@var boolean $_error Gia tri the hien co loi hay khong khi thao tac voi database
	*	@var object $_results Gia tri truy van database
	*	@var int $_count So luong record trong ket qua truy van
	*/
	class DB {
		
		private static $_instance = null;
		private $_pdo,
				$_query,
				$_error = false,
				$_results,
				$_count = 0;
		/**
		*	Khoi tao doi tuong thuoc class DB
		*/
		public function __construct() {
			try {
				$this->_pdo = new PDO('mysql:host='.Config::get('mysql/host').';dbname='.Config::get('mysql/db'),Config::get('mysql/username'),Config::get('mysql/password'));
				//echo "Connected";
			} catch(PDOException $e) {
				die($e->getMessage());
			}
		}

		/**
		*	Tra ve the hien cua doi tuong thuoc class DB
		*/
		public static function getInstance() {
			if(!isset(self::$_instance)) {
				self::$_instance = new DB();
			}

			return self::$_instance;
		}

		/**
		*	Thuc hien truy van co so du lieu
		*	@param string $sql Cau lenh truy van database
		* 	@param array $params Mang cac gia tri cua cau lenh truy van database
		*	@return $this
		*/
		public function query($sql, $params = array()) {
			$this->_error = false;
			if($this->_query = $this->_pdo->prepare($sql)) {
				//echo "Success";
				$x = 1;
				if(count($params)) {
					foreach ($params as $param) {
						$this->_query->bindValue($x, $param);
						$x++;
					}
				}

				if($this->_query->execute()) {
					$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
					$this->_count = $this->_query->rowCount();
				} else {
					$this->_error = true;
				}
			}

			return $this;
		}

		/**
		*	Thuc hien truy van co so du lieu
		*	@param string $action Ten hanh dong khi thuc hien truy van database
		*	@param string $table Ten table khi thuc hien truy van
		*	@param array $where Dieu kien where khi thuc hien truy van
		*	@return mixed
		*/
		private function action($action, $table, $where = array()) {
			if(count($where) === 3) {
				$operators = array('=', '>', '<', '>=', '<=');

				$field = $where[0];
				$operator = $where[1];
				$value = $where[2];

				if(in_array($operator, $operators)) {
					$sql = "{$action} from {$table} where {$field} {$operator}?";
					if(!$this->query($sql, array($value))->error()) {
						return $this;

					}
				}
			}

			return false;
		}

		/**
		*	Tra ve doi tuong khi thuc hien cau lenh select
		*	@param string $table Ten table khi thuc hien truy van 
		*	@param array $where Dieu kien where khi thuc hien truy van
		*	@return mixed
		*/
		public function get($table, $where) {
			return $this->action('SELECT *', $table, $where);
		}

		/**
		*	Tra ve doi tuong khi thuc hien cau lenh delete
		*	@param string $table Ten table khi thuc hien truy van
		*	@param array $where Dieu kien where khi thuc hien truy van 
		*	@return mixed
		*/
		public function delete($table, $where) {
			return $this->action('DELETE *', $table, $where);
		}

		/**
		*	Chen du lieu vao database
		*	@param string $table Ten table khi thuc hien truy van
		*	@param array $fields Mang cac gia tri cua cau lenh insert
		*	@return bool
		*/
		public function insert($table, $fields = array()) {
			if(count($fields)) {
				$keys = array_keys($fields);
				$values = null;
				$x = 1;

				foreach($fields as $field) {
					$values .= "?";
					if($x < count($fields)) {
						$values .= ',';
					}
					$x++;
				}
				$sql = "insert into {$table} (`".implode('`,`', $keys) ."`) values ({$values})";
				if(!$this->query($sql, $fields)->error()) {
					return true;
				}
			}

			return false;
		}

		/**
		*	Cap nhat du lieu database
		*	@param string $table Ten table khi thuc hien truy van
		*	@param string $id Id cua record thuc hien update du lieu
		*	@param array $fields Mang cac gia tri cua cau lenh update
		*	@return bool
		*/

		public function update($table, $id, $fields = array()) {
			$set = '';
			$x = 1;

			foreach($fields as $name => $value) {
				$set .= "{$name} = ?";
				if($x < count($fields)) {
					$set .= ', ';
				}

				$x++;
			}

			$sql = "update {$table} set {$set} where id = {$id}";
			if(!$this->query($sql, $fields)->error()) {
				return true;
			} 
			return false;
		}

		/**
		*	Tra ve ket qua truy van database
		*	@return mixed
		*/
		public function results() {
			return $this->_results;
		}

		/**
		*	Tra ve record dau tien khi truy van database
		*	@return mixed
		*/
		public function first() {
			return $this->results()[0];
		}

		/**
		*	Tra ve gia tri the hien co loi hay khong
		*	@return string 
		*/
		public function error() {
			return $this->_error;
		}

		/**
		*	Tra ve so luong record khi truy van database
		*	@return number
		*/
		public function count() {
			return $this->_count;
		}
	}
?>