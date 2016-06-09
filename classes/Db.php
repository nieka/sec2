<?php
	class Db {
		private static $_instance = null;
		private $_pdo,
		$_query,
		$_error = false,
		$_results,
		$_count = 0;

		private function __construct() {
			try {
				$this->_pdo = new PDO('mysql:host='.Config::get('mysql/host').';dbname='.Config::get('mysql/db'),Config::get('mysql/username'),Config::get('mysql/password'));
			} catch (PDOException $e) {
				die($e->getMessage());
			}
		}

		public static function getInstance() {
			if (!isset(self::$_instance)) {
				self::$_instance = new Db();
			}
			return self::$_instance;
		}

		public function query($sql, $params = array()) {
			$this->_error = false;
			if ($this->_query = $this->_pdo->prepare($sql)) {
				$x = 1;
				if (count($params)) {
					foreach ($params as $param) {
						$this->_query->bindValue($x, $param);
						$x++;
					}
				}

				if ($this->_query->execute()) {
					$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
					$this->_count	= $this->_query->rowCount();
				} else {
					$this->_error = true;
				}
			}

			return $this;
		}

		public function action($action, $table, $where = array()) {
			if (count($where) === 3) {    //Allow for no where
                $operators = array('=', '>', '<', '>=', '<=', '<>','LIKE');

                $field = $where[0];
                $operator = $where[1];
                $value = $where[2];

                if (in_array($operator, $operators)) {
                    $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                    if (!$this->query($sql, array($value))->error()) {
                        return $this;
                    }
                } else {
                    $sql = "{$action} FROM {$table}";
                    if (!$this->query($sql)->error()) {
                        return $this;
                    }
                }
            }
            else{
                $sql = "{$action} FROM {$table}";
                if (!$this->query($sql)->error()) {
                    return $this;
                }
            }
			return false;
		}

        public function actionWithLimit($table,$where = array(),$limit = array()){
            if(count($where) === 3 && count($limit) === 2){
                $operators = array('=', '>', '<', '>=', '<=', '<>');

                $field = $where[0];
                $operator = $where[1];
                $value = $where[2];

                if(in_array($operator, $operators)){
                    $sql = "SELECT * from {$table} WHERE {$field} {$operator} ? LIMIT {$limit[0]} , {$limit[1]}";
                    if (!$this->query($sql, array($value))->error()) {
                        return $this;
                    }
                }else {
                    return false;
                }

            }else {
                return false;
            }
        }

		public function actionWithOrder($action, $table, $where = array(), $order = array()) {
			if(count($where) === 3 && count($order) === 2 ) {    //Allow for no where
                $operators = array('=', '>', '<', '>=', '<=', '<>');
                $sequences	=	array('ASC', 'DESC');

                $field = $where[0];
                $operator = $where[1];
                $value = $where[2];

                $by = $order[0];
                $sequence = $order[1];

                if (in_array($operator, $operators) && in_array($sequence, $sequences)) {
                    $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ? ORDER BY {$by} {$sequence}";
                    if (!$this->query($sql, array($value))->error()) {
                        return $this;
                    }
                } else {
                    $sql = "{$action} FROM {$table}";
                    if (!$this->query($sql)->error()) {
                        return $this;
                    }
                }
            }
            else{
                $sql = "{$action} FROM {$table}";
                if (!$this->query($sql)->error()) {
                    return $this;
                }
            }
			return false;
		}

		public function get($table, $where) {
			return $this->action('SELECT *', $table, $where); //ToDo: Allow for specific SELECT (SELECT username)
		}

		public function getWithOrder($table, $where, $order) {
			return $this->actionWithOrder('SELECT *', $table, $where, $order);
		}

		public function delete($table, $where) {
			return $this->action('DELETE', $table, $where);
		}

		public function insert($table, $fields = array()) {
			if (count($fields)) {
				$keys 	= array_keys($fields);
				$values = null;
				$x 		= 1;

				foreach ($fields as $field) {
					$values .= '?';
					if ($x<count($fields)) {
						$values .= ', ';
					}
					$x++;
				}

				$sql = "INSERT INTO {$table} (`".implode('`,`', $keys)."`) VALUES({$values})";
				if (!$this->query($sql, $fields)->error()) {
			
					return true;
				}
			}
			return false;
		}


		public function getAll($table){
			$this->action('SELECT *', $table);
			return $this->results();
		}


		public function update($table, $id, $fields = array(),$key) {
			$set 	= '';
			$x		= 1;
			
			foreach ($fields as $name => $value) {
				$set .= "{$name} = ?";
				if ($x<count($fields)) {
					$set .= ', ';
				}
				$x++;
			}

			$sql = "UPDATE {$table} SET {$set} WHERE {$key} = {$id}";
			if (!$this->query($sql, $fields)->error()) {
				return true;
			}
			
			return false;
		}

		public function results() {
			return $this->_results;
		}

		public function first() {
			return $this->_results[0];
		}

		public function error() {
			return $this->_error;
		}

		public function count() {
			return $this->_count;
		}
	}
?>