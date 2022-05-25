<?php
// define Model class
class Model {
  protected $_db;
  protected $_sql;

  public function __construct() {
    $this->_db = Db::getDb(); // establish database connection 
  }
  
  public function setSql($sql) {
    $this->_sql = $sql; 
  }

  public  function getAll($parameter_values = null) {
    if (!$this->_sql) {
      throw new Exception("No SQL query defined!");
    }
    // Prepare and execute SQL statement
    $stm = $this->_db->prepare($this->_sql);
    $stm->execute($parameter_values);
    //Fetch and return result set
    return $stm->fetchAll();
  }
  
  public function getOne($parameter_values = null) {
      if (!$this->_sql) {
          throw new Exception("No SQL query defined!");
      }
      // Prepare and execute SQL statement
      $stm = $this->_db->prepare($this->_sql);
      $stm->execute($parameter_values);
    // Fetch and return result set
    return $stm->fetch();
  }
} 
