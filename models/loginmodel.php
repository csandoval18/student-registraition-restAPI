<?php

class LoginModel extends Model {
	public function checklogin() {
		$input_data = file_get_contents('php://input');
		$data = json_decode($input_data, true);  // convert data to an associative array
		// define SQL statement
		$sql = "SELECT  username from `users` where username=:username and password = :password";
		// set SQL statement
		$this->setSql($sql);
		$parameters = [":username" => $data["username"], ":password" => md5($data["password"])];
		$result = $this->getOne($parameters);
		if (is_object($result) && isset($result->username)) {
			return "valid";
		}
		return "invalid";
	}
}
?>

