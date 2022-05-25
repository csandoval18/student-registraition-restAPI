<?php

class ScheduleModel extends Model {
	public function schedulebylocation($parameter_values) {
		if (count($parameter_values) !== 1) {
			return [];
		}
    
		$location = $parameter_values[0];
		$sql = "SELECT id, subject, number, instructor, section, displayTime, location FROM `uww_schedule` WHERE location = :location
			ORDER BY subject ASC";
		$this->setSql($sql);
		$parameters = [":location" => $location];
		$schedules = $this->getAll($parameters);
		return  $schedules;
	}

	public function schedulebysubject($parameter_values) {
		if (count($parameter_values) !== 1) {
			return [];
		}
    
		$subject = $parameter_values[0];
		$sql = "SELECT id, subject, number, instructor, section, displayTime, location FROM `uww_schedule` WHERE subject = :subject
			ORDER BY subject ASC";
		$this->setSql($sql);
		$parameters = [":subject" => $subject];
		$schedules = $this->getAll($parameters);
		return $schedules;
	}


	public function addsemesterplan() {
		$input_data = file_get_contents('php://input');
		$dataArray = json_decode($input_data, true);  // convert data to an associative array
		try {
			$columns = array_keys($dataArray[0]);
			$columnList = "";
			$parameterList = "";
			$parameterValues = [];
      
			foreach ($columns as $column) {
				$columnList .= $column . ", ";
				$parameterList .= ":" . $column . ", ";
			}
      
			$columnList = substr($columnList, 0, -2);
			$parameterList = substr($parameterList, 0, -2);
			$sql = "INSERT INTO `user_schedule` ({$columnList}) VALUES ({$parameterList})";
			$stm = $this->_db->prepare($sql);
      
			foreach ($dataArray as $item) {
				// Each element is an array
				foreach ($item as $column => $value) {
					$parameterValues[":{$column}"] = $value;
				}
				$stm->execute($parameterValues);
			}
			return 1;
		} catch (Exception $e) {
			echo -1;
		}
	}

	public function semesterplan() {
    $input_data = file_get_contents('php://input');
		$data = json_decode($input_data, true);
		$sql = "SELECT s.id,s.subject, s.number, s.instructor, s.section, s.displayTime, s.location 
    FROM `uww_schedule` as s, `user_schedule` as u WHERE u.sectionid = s.id and u.username = :username";
		// set SQL statement
		$this->setSql($sql);
		// define parameters
		$parameters = [":username" => $data["username"]];
		// return results
		$schedules = $this->getAll($parameters);
		return $schedules;
	}
  
  public function saveselectedsection() {
		// There should be only one parameter in the request
    $input_data = file_get_contents('php://input');
		$data = json_decode($input_data, true);  // 
    $sql = "SELECT username, sectionid FROM `user_schedule` WHERE username=:username AND sectionid=:sectionid";
    $this->setSql($sql);
    $parameters = [":username" => $data["username"], ":sectionid" => $data["sectionid"]];
    $result = $this->getAll($parameters);
    if (count($result) == 0) {
      $sql = "INSERT INTO `user_schedule` (username, sectionid) VALUES(:username, :sectionid)";
      try {
        // Prepare SQL statement
        $stm = $this->_db->prepare($sql);
        // define parameters
        $parameters = [":username" => $data["username"], ":sectionid" => $data["sectionid"]];
        $stm->execute($parameters);
        return "success";
      } catch (Exception $exception) {
        return "failed";
      } 
    } else return "class already in cart";
  }
  
  public function removeselectedsection() {
    $input_data = file_get_contents('php://input');
    $data = json_decode($input_data, true);
    
    $sql = "DELETE FROM `user_schedule` WHERE username=:username AND sectionid=:sectionid";
    try {
      $stm = $this->_db->prepare($sql);
      $parameters = [":username" => $data["username"], ":sectionid" => $data["sectionid"]];
      $stm->execute($parameters);
      return "delete successful";
    } catch (Exception $exception) {
      return $exception;
    }
  }
	
	public function displayplan() {
		// Prepare SQL statement
		$sql = "SELECT `uww_schedule`.id, subject, number, section, displayTime, instructor, 
		location FROM `uww_schedule` INNER JOIN `user_schedule` 
		ON `user_schedule`.sectionid=`uww_schedule`.id";
		$this->setSql($sql);
		return $this->getAll();
	}
	
	public function removesection($parameter_values) {
		$sectionid = $parameter_values[0];
		$sql = "DELETE FROM  `user_schedule` WHERE sectionid=:sectionid";
		$this->setSql($sql);
		$parameters = [":sectionid" => $sectionid];  
		$this->getOne($parameters);
		return "section deleted";
	}
}
?>

