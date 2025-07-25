<?php
class DB{

  // Property untuk koneksi ke database mysql
  private $_host = '127.0.0.1';
  private $_dbname = 'nabaty';
  private $_username = 'moriza';
  private $_password = 'pororo0857';

  // Property internal dari class DB
  private static $_instance = null;
  private $_pdo;
  private $_columnName = "*";
  private $_orderBy = "";
  private $_limit = "";
  private $_count = 0;
  private $_groupBy = ""; //For grouping chat from conversation

  // Constructor untuk pembuatan PDO Object
  private function __construct(){
    try {
      $this->_pdo = new PDO('mysql:host='.$this->_host.';dbname='.$this->_dbname,
                             $this->_username, $this->_password);
      $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e){
      die("Koneksi / Query bermasalah: ".$e->getMessage(). " (".$e->getCode().")");
    }
  }

  // Singleton pattern untuk membuat class DB
  public static function getInstance(){
    if(!isset(self::$_instance)) {
      self::$_instance = new DB();
    }
    return self::$_instance;
  }

  // Method dasar untuk menjalankan prepared statement query
  public function runQuery($query, $bindValue = []){
    try {
      $stmt = $this->_pdo->prepare($query);
      $stmt->execute($bindValue);
    }
    catch (PDOException $e){
      die("Koneksi / Query bermasalah: ".$e->getMessage(). " (".$e->getCode().")");
    }
    return $stmt;
  }

  // Method untuk menampilkan hasil query SELECT sebagai fetchAll (object)
  public function getQuery($query,$bindValue = []){
    return $this->runQuery($query,$bindValue)->fetchAll(PDO::FETCH_OBJ);
  }

  // Method untuk menentukan kolom yang akan ditampilkan
  public function select($columnName){
    $this->_columnName = $columnName;
    return $this;
  }

  // Method untuk menentukan urutan hasil tabel (query ORDER BY)
  public function orderBy($columnName, $sortType = 'ASC'){
    $this->_orderBy = "ORDER BY {$columnName} {$sortType}";
    return $this;
  }

  //Grouping rows with same values into summary rows
  public function groupBy($columnName){
    $this->_groupBy = "GROUP BY {$columnName}";
    return $this;
  }

  public function limit($number){
    $this->_limit = "LIMIT $number";
    return $this;
  }

  // Method utama untuk mengambil isi tabel
  public function get($tableName, $condition = "", $bindValue = []){
    $query = "SELECT {$this->_columnName} FROM {$tableName} {$condition} {$this->_orderBy} {$this->_limit}";
    $this->_columnName = "*";
    $this->_orderBy = "";
    $this->_limit = "";
    return $this->getQuery($query, $bindValue);
  }

  // Method untuk mengambil isi tabel dengan kondisi WHERE
  public function getWhere($tableName, $condition){
    $queryCondition ="WHERE {$condition[0]} {$condition[1]} ? ";
    return $this->get($tableName,$queryCondition,[$condition[2]]);
  }

  // Method untuk mengambli isi tabel dengan multiple kondisi WHERE
  public function getWhereConditionsAnd($tableName, $conditions){
    $queryCondition = "WHERE";
    foreach($conditions as $condition){
      $queryCondition .= " {$condition[0]} {$condition[1]} ? AND";
      $conditionValue[] = $condition[2];
    }

    $queryCondition = substr($queryCondition, 0, -3);
    return $this->get($tableName, $queryCondition, $conditionValue);
  }

  // Method untuk mengambil isi tabel dengan kondisi WHERE dan hanya baris pertama saja
  public function getWhereOnce($tableName, $condition){
    $result = $this->getWhere($tableName,$condition);
    if (!empty($result)) {
      return $result[0]; 
    } else {
      return false;
    }
  }

  // Method untuk mengambil isi tabel dengan pencarian (query LIKE)
  public function getLike($tableName, $columnLike, $search){
    $queryLike = "WHERE {$columnLike} LIKE ?";
    return $this->get($tableName,$queryLike,[$search]);
  }

  //Method untuk mengambil isi 2 tabel Left Join
  public function getLeftJoinTwoTables($tableName1, $tableName2, $columnJoin, $condition = "", $bindValue = []){
    $query = "SELECT {$this->_columnName} FROM {$tableName1} LEFT JOIN {$tableName2} ON {$tableName1}.{$columnJoin} = {$tableName2}.{$columnJoin} {$condition} {$this->_orderBy}";
    $_columnName = "*";
    $_orderBy = "";
    return $this->getQuery($query, $bindValue);
  }

  //Method untuk mengambil isi 2 tabel dengan kondisi where
  public function getLeftJoinTwoTablesWhere($tableName1, $tableName2, $columnJoin, $condition){
    $queryWhere = "WHERE {$condition[0]} {$condition[1]} ? ";
    return $this->getLeftJoinTwoTables($tableName1, $tableName2, $columnJoin, $queryWhere, [$condition[2]]);
  }

  //Method untuk mengambil isi 2 tabel dengan multiple condition where
  public function getLeftJoinTwoTablesWhereConditionsAnd($tableName1, $tableName2, $columnJoin, $conditions){
    $queryCondition = "WHERE ";
    foreach($conditions as $condition){
      $queryCondition .= " {$condition[0]} {$condition[1]} ? AND";
      $conditionValue[] = $condition[2];
    }
    $queryCondition = substr($queryCondition, 0, -3);
    return $this->getLeftJoinTwoTables($tableName1, $tableName2, $columnJoin, $queryCondition, $conditionValue);
  }

  //Method untuk mengambil isi 2 tabel dengan kondisi where, tapi baris pertama saja
  public function getLeftJoinTwoTablesWhereOnce($tableName1, $tableName2, $columnJoin, $condition){
    $result = $this->getLeftJoinTwoTablesWhere($tableName1, $tableName2, $columnJoin, $condition);
    if(!empty($result)){
      return $result[0];
    } else {
      return false;
    }
  }

  //Method untuk mengambil isi 2 tabel untuk pencarian (query LIKE)
  public function getLeftJoinTwoTablesLike($tableName1, $tableName2, $columnJoin, $columnLike, $search){
    $queryLike = "WHERE {$columnLike} LIKE ? ";
    return $this->getLeftJoinTwoTables($tableName1, $tableName2, $columnJoin, $queryLike, [$search]);
  }

  //Method to get a list of inner join data
  public function getInnerJoinTwoTables($tableName1, $tableName2, $columnJoin, $condition = "", $bindValue = []){
    $query = "SELECT {$this->_columnName} FROM {$tableName1} INNER JOIN {$tableName2} ON {$tableName1}.{$columnJoin} = {$tableName2}.{$columnJoin} {$condition} {$this->_orderBy}";
    $_columnName = "*";
    $_orderBy = "";
    return $this->getQuery($query, $bindValue);
  }

  //Method to select two table with where conditions
  public function getInnerJoinTwoTablesWhere($tableName1, $tableName2, $columnJoin, $condition) {
    $queryWhere = "WHERE {$condition[0]} {$condition[1]} ?";
    return $this->getInnerJoinTwoTables($tableName1, $tableName2, $columnJoin, $queryWhere, [$condition[2]]);
  }

  //Method to select two table with multiple where conditions
  public function getInnerJoinTwoTablesWhereConditionAnd ($tableName1, $tableName2, $columnJoin, $conditions){
    $conditionQuery = "WHERE ";
    foreach($conditions as $condition) {
      $conditionQuery .= " {$condition[0]} {$condition[1]} ? AND";
      $conditionValue[] = $condition[2];
    }
    $conditionQuery = substr($conditionQuery, 0, -3);
    return $this->getInnerJoinTwoTables($tableName1, $tableName2, $columnJoin, $conditionQuery, $conditionValue);
  } 

  //Method to select from two tables with 'where' condition, but only first line
  public function getInnerJoinTwoTablesWhereOnce($tableName1, $tableName2, $columnJoin, $condition) {
    $result = $this->getInnerJoinTwoTablesWhere($tableName1, $tableName2, $columnJoin, $condition);
    if(!empty($result)){
      return $result[0];
    } else {
      return false;
    }
  }

  //Method to select from two tables based on user query search (LIKE query)
  public function getInnerJoinTwoTablesLike($tableName1, $tableName2, $columnJoin, $likeColumn, $search) {
    $likeQuery = "WHERE {$likeColumn} LIKE ? ";
    return $this->getInnerJoinTwoTables($tableName1,$tableName2, $columnJoin, $likeQuery, [$search]);
  }

  // Method untuk check nilai unik, akan berguna untuk form
  public function check($tableName, $columnName, $dataValues){
    $query = "SELECT {$columnName} FROM {$tableName} WHERE {$columnName} = ? ";
    return $this->runQuery($query,[$dataValues])->rowCount();
  }

  // Ambil nilai kolom, hasil dari rowCount()
  public function count(){
    return $this->_count;
  }

  // Method untuk menginput data tabel (query INSERT)
  public function insert($tableName, $data){
    $dataKeys = array_keys($data);
    $dataValues = array_values($data);
    $placeholder = '('.str_repeat('?,', count($data)-1) . '?)';

    $query = "INSERT INTO {$tableName} (".implode(', ',$dataKeys).") VALUES {$placeholder}";
    $this->_count = $this->runQuery($query,$dataValues)->rowCount();
    return true;
  }

  // Method untuk mengupdate data tabel (query UPDATE)
  public function update($tableName, $data, $condition){
    $query = "UPDATE {$tableName} SET ";
    foreach ($data as $key => $val){
      $query .= "$key = ?, " ;
    }
    $query = substr($query,0,-2);
    $query .= " WHERE {$condition[0]} {$condition[1]} ?";

    $dataValues = array_values($data);
    array_push($dataValues,$condition[2]);

    $this->_count = $this->runQuery($query,$dataValues)->rowCount();
    return true;
  }

  //Method to update table data with two more conditions (UPDATE)
  public function updateConditions($tableName, $data, $conditions){
    $query = "UPDATE {$tableName} SET ";
    foreach($data as $key => $val){
      $query .= "$key = ?, ";
    }
    $query = substr($query, 0, -2)." WHERE ";
    $dataValues = array_values($data);
    for($i = 0; $i < count($conditions); $i++){
      $query .= "{$conditions[$i][0]} {$conditions[$i][1]} ? AND ";
      $dataValues[] = $conditions[$i][2];
    }
    $query = substr($query, 0, -5);
    
    $this->_count = $this->runQuery($query,$dataValues)->rowCount();
    return $this->_count;
  }

  //Method to get data from multiple tables(3 or more)
  public function getMultipleTable($joinArray, $conditions = "", $bindValue  = []){
    $query = "SELECT {$this->_columnName} FROM {$joinArray[0][1][0]} {$joinArray[0][0]} {$joinArray[0][1][1]} ON {$joinArray[0][1][0]}.{$joinArray[0][2]} = {$joinArray[0][1][1]}.{$joinArray[0][2]}";
    for($i = 1; $i<count($joinArray); $i++){
      $query .= " {$joinArray[$i][0]} {$joinArray[$i][1][1]} ON {$joinArray[$i][1][0]}.{$joinArray[$i][2]} = {$joinArray[$i][1][1]}.{$joinArray[$i][2]}";
    }
    $query .= " {$conditions} {$this->_orderBy} {$this->_limit}";
    $this->_columnName = "*";
    $this->_orderBy = "";
    $this->_limit = "";
    return $this->getQuery($query, $bindValue);
  }

  //Method to get data from multiple tables and conditions
  public function getMultipleTableConditions($joinArray, $conditions, $operators = []){
    //check whether the $operators var is empty or not
    if(count($operators) === 0){
      $conditionQuery = " WHERE {$conditions[0]} {$conditions[1]} ?";
      return $this->getMultipleTable($joinArray, $conditionQuery, [$conditions[2]]);
    }
    else{
      $conditionQuery = " WHERE {$conditions[0][0]} {$conditions[0][1]} ?";
      $bindValue = [$conditions[0][2]];
      for($i = 0; $i < count($operators); $i++){
        $conditionQuery .= " {$operators[$i]} {$conditions[$i+1][0]} {$conditions[$i+1][1]} ?";
        $bindValue[] = $conditions[$i+1][2];
      }
      return $this->getMultipleTable($joinArray, $conditionQuery, $bindValue);
    }
  }
  
  // Method untuk menghapus data tabel (query DELETE)
  public function delete($tableName, $condition){
    $query = "DELETE FROM {$tableName} WHERE {$condition[0]} {$condition[1]} ? ";
    $this->_count = $this->runQuery($query,[$condition[2]])->rowCount();
    return true;
  }

  // Method to delete a data array (DELETE ... WHERE ... IN)
  public function deleteDataArray($tableName, $condition){
    $placeholder = '('.str_repeat('?,', count($condition[1])-1) . '?)';
    $query = "DELETE FROM {$tableName} WHERE {$condition[0]} IN {$placeholder}";
    $this->_count = $this->runQuery($query, $condition[1])->rowCount();
    return $this->_count;
  }

  public function beginTransaction(){
    $this->_pdo->beginTransaction();
  }

  public function rollBack(){
    $this->_pdo->rollBack();
  }

  public function commit(){
    $this->_pdo->commit();
  }

}
