<?php

/*
 * This file is part of the dbhelper package.
 *
 * (c) Restu Adi <lab@fidelstu.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class _db
{    
    private $_pathConfig = 'config/db.json';
    // Property untuk koneksi ke database mysql
    private $_host = '';
    private $_dbname = '';
    private $_username = '';
    private $_password = '';

    private $_pdo;
    private static $_instance = null;

    public static $Connection="";    

    private $_selectColumn = "";
    private $_fromTable = "";
    private $_whereCondition = "";
    private $_orderBy = "";

    private $_execKind = "";
    private $_execTable = "";
    private $_execSet = "";    

    private function configFromJson(){
        $path = "{$_SERVER['DOCUMENT_ROOT']}/{$this->_pathConfig}";
        $cfg= null;
        if (is_file($path)){
            $strFile = file_get_contents($path);
            
            $cfg= (object) json_decode($strFile, true);

            $this->_host = $cfg->_host;
            $this->_dbname = $cfg->_dbname;
            $this->_username = $cfg->_username;
            $this->_password = $cfg->_password;
        }

        return $cfg;
    }

    // Constructor untuk pembuatan PDO Object
    private function __construct(){        
        $this->configFromJson();        
        try {
          $this->_pdo = new PDO('mysql:host='.$this->_host.';dbname='.$this->_dbname,
                                 $this->_username, $this->_password);
    
                                 //echo 'mysql:host='.$this->_host.';dbname='.$this->_dbname.$this->_username. $this->_password;
          $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e){
          // $e;
    
          die("Koneksi / Query bermasalah: ".$e->getMessage(). " (".$e->getCode().")");
        }
    }

    

  // Singleton pattern untuk membuat class DB
  public static function getInstance(){
    if(!isset(self::$_instance)) {        
      self::$_instance = new _db();
    }
    return self::$_instance;
  }


  public function _errorConnection($err){
        die("Connection / Query have problem('s): ".$err->getMessage(). " (".$err->getCode().")");
    }

    
    function select($column){      
        $this -> _resetSelect();  
        $this -> _selectColumn = "select {$column} ";
        return $this;
    }

    function from($table){
        $this -> _selectColumn = empty($this -> _selectColumn) ? "select * " : $this -> _selectColumn;
        $this -> _fromTable ="from {$table}";
        return $this;
    }

    function where($conditionArray){
        $condition = implode("",$conditionArray);
        $this -> _whereCondition = " where {$condition}"; 
        return $this;
    }

    

    function orderBy($order){
        $condition = " order by {$order}";
        $this -> _orderBy = $condition;
        return $this;
    }

    private function _resetSelect(){
        $this -> _whereCondition = "";
        $this -> _selectColumn = "";
        $this -> _orderBy = "";
    }

    

    function queryRows($sql){
        $query = $this -> queryPDO($sql);
        
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    function queryObject($sql){
        $query = $this -> queryPDO($sql);
        return $query->fetch(PDO::FETCH_OBJ);
    }
    
    function insertInto($table,$args) {
        $fieldList = array();
        $valueList = array();

        foreach($args as $f => $val) {
            array_push(
                $fieldList
                , $f
            );

            array_push(
                $valueList
                , "'".$val."'"
            );
        }
        $fields = join(",",$fieldList);
        $values = join(",",$valueList);

        $sql = "INSERT INTO ".$table." (".$fields.") VALUES (".$values.")";
       
        $this -> execute($sql);
    } 

    private function _resetExec(){
        $this -> _execKind = "";
        $this -> _execTable = "";
        $this -> _execSet = "";
        $this -> _whereCondition = "";
    }

    function update($table){
        $this -> _resetExec(); 
        $this->_execKind = "update";
        $this->_execTable = $table;
        return $this;
    }

    function set($argsSet){
        $setList = array();
        foreach($argsSet as $key => $val) {
            array_push(
                $setList
                , $key."='".$val."'"
            );            
        }
        $setSql = join(",",$setList);

        $this->_execSet = $setSql;
        return $this;
    }

    function updateTo($table,$argsWhere,$argsSet) {        
        $setList = array();
        $whereList = array();

        foreach($argsSet as $key => $val) {
            array_push(
                $setList
                , $key."='".$val."'"
            );            
        }

        foreach($argsWhere as $key => $val) {
            array_push(
                $whereList
                , $key."='".$val."'"
            );            
        }

        $setSql = join(",",$setList);
        $whereSql = join(" and ",$whereList);

        $sql = "UPDATE ".$table." SET ".$setSql." WHERE ".$whereSql;
        
        $this -> execute($sql);
    } 

    function deleteFrom($table,$argsWhere) {        
        $whereList = array();       

        foreach($argsWhere as $key => $val) {
            array_push(
                $whereList
                , $key."='".$val."'"
            );            
        }

        $whereSql = join(" and ",$whereList);

        $sql = "DELETE FROM ".$table." WHERE ".$whereSql;      


        $this -> execute($sql);
    } 

    function query() {
        $sql = $this->_selectColumn.$this->_fromTable.$this->_whereCondition.$this->_orderBy;

        try {
            return $this -> queryRows($sql);
        } catch (Exception $err) {
            $this -> _errorConnection($err);
        } 
       
    }

    function queryRow() {
        $sql = $this->_selectColumn.$this->_fromTable.$this->_whereCondition.$this->_orderBy;
        
        try {
            return $this -> queryObject($sql);
        } catch (Exception $err) {
            $this -> _errorConnection($err);

            
        } 
       
    }

    function exec() { 
        $kind = $this->_execKind;
        
        $cmd= "";
        $set= "";
        $where= "";

        if($kind == "update"){
            $cmd= "UPDATE {$this->_execTable} ";
            $set= "SET {$this->_execSet} ";
            $where= "{$this->_whereCondition}";
        }
        

        $sql = $cmd.$set.$where;

        $this->execute($sql);
    }

    function queryPDO($sql) {    
        
        return $this -> _pdo->query($sql);
    }

    function execute($sql) {
        try {
            $this -> _pdo->exec($sql);
        } catch (Exception $err) {
            $this -> _errorConnection($err);
        }          
    }   

    

}

?>