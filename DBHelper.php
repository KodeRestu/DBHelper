<?php

class DBHelper
{    
    public static $Connection;

    private static $_instance = null;

    private $_selectColumn = "";
    private $_fromTable = "";
    private $_whereCondition = "";
    private $_orderBy = "";

    private $_execKind = "";
    private $_execTable = "";
    private $_execSet = "";

    public function __construct() {
        $this -> connectDB();
    }

    public function connectDB(){        
        $strFile = file_get_contents(self::$Connection);
        $cfg= (object) json_decode($strFile, true);

        $dsn = "$cfg->type:host=$cfg->host;dbname=$cfg->dbname";       

        try {
            $this -> conn = new PDO($dsn, $cfg->username, $cfg->passcode);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //echo "DB Connected";
        } catch (Exception $err) {
            $this._errorConnection($err);
        }
    }
        
    public static function Instance($connection){
        if(!isset(self::$_instance)) {
            self::$Connection=$connection;
        self::$_instance = new DBHelper();
        }
        return self::$_instance;
    }

    function _errorConnection($err){
        die("Connection / Query have problem('s): ".$err->getMessage(). " (".$err->getCode().")");
    }

    function disconnectDB(){
        $this -> conn = NULL;        
    }

    function select($column){      
        $this -> _resetSelect();  
        $this -> _selectColumn = "select {$column} ";
        return $this;
    }

    function from($table){
        $this -> _selectColumn = $this -> _selectColumn == "" ? "select * " : $this -> _selectColumn;
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
            $this._errorConnection($err);
        } 
       
    }

    function queryRow() {
        $sql = $this->_selectColumn.$this->_fromTable.$this->_whereCondition.$this->_orderBy;

        try {
            return $this -> queryObject($sql);
        } catch (Exception $err) {
            $this._errorConnection($err);
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
        
        return $this -> conn->query($sql);
    }

    function execute($sql) {
        try {
            $this -> conn->exec($sql);
        } catch (Exception $err) {
            $this._errorConnection($err);
        }          
    }   

    

}

?>