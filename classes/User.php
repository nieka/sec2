<?php
	class User {
		private $_db,
				$_data,
				$_sessionName,
				$_cookieName,
				$_isLoggedIn;

		public function __construct($user = null) {
			$this->_db 			= Db::getInstance();
			$this->_sessionName = Config::get('session/session_name');
			$this->_cookieName 	= Config::get('remember/cookie_name');
			if (!$user) {
				if (Session::exists($this->_sessionName)) {
					$user = Session::get($this->_sessionName);
					if ($this->find($user)) {
						$this->_isLoggedIn = true;
					} else {
                        echo'ik log out';
						self::logout();
					}
				}
			} else {
				$this->find($user);
			}
		}

		public function update($fields = array(), $id = null) {

			if (!$id && $this->isLoggedIn()) {
				$id = $this->data()->ID;
			}

			if (!$this->_db->update('Gebruiker', $id, $fields, "UserId")) {
				throw new Exception("There was a problem updating your details");
			}
		}

		public function create($fields = array()) {
			if (!$this->_db->insert('Gebruiker', $fields)) {
				throw new Exception("There was a problem creating your account");
			}
		}

		public function find($user = null) {

			if ($user) {
				$fields = (is_numeric($user)) ? 'UserId' : 'Username';	//Numbers in username issues
				$data 	= $this->_db->get('Gebruiker', array('Username', '=', $user));

				if ($data->count()) {
					$this->_data = $data->first();
					return true;
				}
			}
			return false;
		}

		public function login($username = null, $password = null, $remember = false) {

			if (!$username && !$password && $this->exists()) {
				Session::put($this->_sessionName, $username);

			} else {
				
				$user = $this->find($username);

				if ($user) {
					if ($this->data()->Wachtwoord === Hash::make($password, $this->data()->Salt)) {
						
						Session::put($this->_sessionName, $username);
						return true;
					}
				}
			}
			return false;
		}

		public function hasPermission($key) {
			$group = $this->_db->get('groups', array('ID', '=', $this->data()->userGroup));
			if ($group->count()) {
				$permissions = json_decode($group->first()->permissions,true);

				if ($permissions[$key] == true) {
					return true;
				}
			}
			return false;
		}

		public function exists() {
			return (!empty($this->_data)) ? true : false;
		}

		public function logout() {
			//$this->_db->delete('usersSessions', array('userID', '=', $this->data()->ID));
			Session::delete($this->_sessionName);
			//Cookie::delete($this->_cookieName);
		}

		public function data() {
			return $this->_data;
		}

		public function isLoggedIn() {
			return $this->_isLoggedIn;
		}

        public function getAllUsers(){
            $this->_db->getAll("Gebruiker");
            return $this->_db->results();
        }

        public function deleteUser($id){
        	if($this->_db->get('Gebruiker',array('UserId','=',$id))){
	            if($this->_db->delete('gebruiker',array('UserId','=',$id))){
	                return true;
	            }else {
	                return false;
	            }
        	} else {
            	return false;
        	}
        }

        public function getUserById($id){

    		return $this->_db->get("Gebruiker", array("UserId", "=", $id))->first();
        }
        public function hasminrigth($rigth){
            if($this->data()->Rol_ID >= $rigth){
                return true;
            }else {
                return false;
            }
        }

    }
?>
