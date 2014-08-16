<?php
namespace Root\App\Models;

use Root\Basic\Model;

class AuthModel extends Model {
	
	private function encrypt($password){
		// A higher "cost" is more secure but consumes more processing power
		$cost = 10;
		
		// Create a random salt
		$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
		
		// Prefix information about the hash so PHP knows how to verify it later.
		// "$2a$" Means we're using the Blowfish algorithm. The following two digits are the cost parameter.
		$salt = sprintf("$2a$%02d$", $cost) . $salt;
		
		// Hash the password with the salt
		return crypt(trim($password), $salt);

		
	}
	public function register($post){
		
		$params['name'] = array(trim($post['name']), PDO::PARAM_STR);
		$params['email'] = array(trim($post['email']), PDO::PARAM_STR);
		
		
		$params['password_hash'] = array($this->encrypt($post['password']), PDO::PARAM_STR);
		
		// Generate activation key
		$activation_key = sha1(mt_rand(10000,99999).time().$_post['email']);
		
		$params['activation_key'] = array($activation_key, PDO::PARAM_STR);
		 
		
		foreach ($params as $key => $val){
			$PDOparams[':'.$key] = $val;
			$SQLparams[$key] = $key."=:".$key;
		}
		
		$sql = "INSERT auth (registered, ".implode(", ", array_keys($params)).") 
					VALUES (NOW(), " . implode(", ", array_keys($PDOparams) ) . ") ";
				
		$rs = $this->query($sql, $PDOparams);
		
		$this->connection->commit();
		if($rs->rowCount() > 0)
			return $activation_key; // to send in mail from controller
		else 
			return false;
			
		$this->connection = NULL;
		
	}
	
	public function activate_account($activation_key){
		
		$PDOparams[':activation_key'] = array(trim($activation_key), PDO::PARAM_STR);
		$sql = "UPDATE auth SET activated = 1 WHERE activation_key = :activation_key LIMIT 1";
		
		$rs = $this->query($sql, $PDOparams);
		$this->connection->commit();
		
		if($rs->rowCount() > 0)
			return true; 
		else 
			return false;
			
		
	}
	public function getAccount($email){
		
		$PDOparams[':email'] = array(trim($email), PDO::PARAM_STR);
		
		$sql = "SELECT * FROM auth WHERE email = :email AND activated = 1 LIMIT 1";
		
		$rs = $this->query($sql, $PDOparams);
		$this->connection->commit();
		
		if($rs->rowCount() > 0)
			return $rs->fetch();
		else 
			return false;
	}
	
	public function setTmpPassword($id, $tmp_password){
		
		$PDOparams[':id'] = array($id, PDO::PARAM_STR);
		$PDOparams[':password_hash'] = array($this->encrypt($tmp_password), PDO::PARAM_STR);
		
		$sql = "UPDATE auth SET password_hash = :password_hash WHERE id = :id AND activated = 1 LIMIT 1";
		
		$rs = $this->query($sql, $PDOparams);
		$this->connection->commit();
		
		
		if($rs->rowCount() > 0)
			return true;
		else 
			return false;
		
		
	}
	
	public function login($email, $password){

		$PDOparams[':email'] = array(trim($email), PDO::PARAM_STR);
		$sql = "SELECT id, name, password_hash FROM auth WHERE email = :email AND activated = 1 LIMIT 1";
		$rs = $this->query($sql, $PDOparams);
		$this->connection->commit();
		if($rs->rowCount() > 0){
			$row = $rs->fetch();
			if (crypt($password, $row['password_hash']) === $row['password_hash']) {
				$_SESSION['auth_access'] = true;
				$_SESSION['user_id'] = $row['id'];
				$_SESSION['username'] = $row['name'];
				session_write_close();
				return true;
			}else 
				return false;
		}else 
			return false;
		$this->connection = NULL;
		
	}

}