<?php
/*
 * This file is part of the dbhelper package.
 *
 * (c) Restu Adi <lab@fidelstu.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace dbhelper;
use \Datetime;
use \DateTimeZone;

class _tbl{    
  protected $_tbl = 'table';
  protected $_pk = 'primarykey';
  protected $_fields = [
    'field'  => 'value'
  ];  

  protected $_timeZone = 'Asia/Jakarta';
    
  protected $_db = null;
  public function __construct(){
    
    $this->_db = _db::getInstance();

    return $this;
  }
  
  public function get($arrKeyValue){
    $keys = explode(",", $this->_pk);
    $where = array();

    $i=0;
    foreach ( $keys as $k) {
      if(!empty($arrKeyValue[$i])){
        $w = "{$k} = '{$arrKeyValue[$i]}'";
        array_push($where , $w);

        if($i < count($arrKeyValue)-1 ){
          array_push($where," and ");
        }
      }

      $i++;
    }
    
    $object = $this->_db->select("*")
                  ->from($this->_tbl)
                  ->where($where)
                  ->queryRow();

    if(empty($object)){
      return null;
    }

    $record = json_decode(json_encode($object), true);
    
    $this->mapping($record);

    return $this->_fields;
  }

  public function getBy($field, $value){
    $this->quickSelect(["{$field} = '{$value}'"]);

    return $this->_fields;
  }

  public function query($select,$where){
    $this->_db->select($select)
                  ->from($this->_tbl);

    if(!empty($where)){
      $this->_db->where($where);
    }

    $object = $this->_db->query(); 
    
    if(empty($object)){
      return null;
    }

    $records = json_decode(json_encode($object), true);

    return $records;
  }

  public function quickSelect($where,$orderBy = null){
    $this->_db->select("*")
              ->from($this->_tbl)
              ->where($where);

    if(!empty($orderBy)){
      $this->_db->orderBy($orderBy);
    }

    $object = $this->_db->queryRow(); 
    
    if(empty($object)){
      return null;
    }

    $record = json_decode(json_encode($object), true);
    $this->mapping($record);
   
    return $this->_fields;
  }

  public function nextno($field,$where = null){
    $this->_db->select("coalesce(max(".$field."),0)+1 nextno")
                  ->from($this->_tbl);

    if(!empty($where)){
       $this->_db->where($where);
    }

    $object = $this->_db->queryRow();                  
    
    if(empty($object)){
      return null;
    }

    return $object->nextno;
  }

  public function insert($args){    
    try{
      $dic = $this->mapping($args);
     

      $this->_db->insertInto(
          $this->_tbl
          ,(object) $dic 
      );
    } catch (Exception $err) {
        $this._error($err);
    }
  }

  public function new(){    
    try{    

      $this->_db->insertInto(
          $this->_tbl
          ,(object) $this->_fields
      );
    } catch (Exception $err) {
        $this._error($err);
    }
  }

  public function update($key,$args){
    $arrKeyValue = explode(",",$key);    
    $keys = explode(",", $this->_pk);

    $whereDic = array();
    $i=0;
    foreach ( $keys as $k) {
      if(!empty($arrKeyValue[$i])){
        $whereDic[$k] = $arrKeyValue[$i];
      }

      $i++;
    }   

    try{
      $dic = $this-> mapping($args);   

      $this->_db->updateTo(
          $this->_tbl
          ,(object) $whereDic 
          ,(object) $dic 
      );
    } catch (Exception $err) {
        $this._error($err);
    }
  }

  public function updateBy($byArgs,$args){    
    $whereDic = $byArgs;

    try{
      $dic = $this-> mapping($args);

      $this->_db->updateTo(
          $this->_tbl
          ,(object) $whereDic 
          ,(object) $dic 
      );
    } catch (Exception $err) {
        $this._error($err);
    }
  }

  public function delete($key){
    $arrKeyValue = explode(",",$key);    
    $keys = explode(",", $this->_pk);
    

    $whereDic = array();
    $i=0;
    foreach ( $keys as $k) {
      if(!empty($arrKeyValue[$i])){
        $whereDic[$k] = $arrKeyValue[$i];
      }

      $i++;
    }

    
    try{
      $this->_db->deleteFrom(
          $this->_tbl
          ,(object) $whereDic 
      );
    } catch (Exception $err) {
        $this._error($err);
    }
  }

  public function deleteBy($whereDic){
    try{
      $this->_db->deleteFrom(
          $this->_tbl
          ,(object) $whereDic 
      );
    } catch (Exception $err) {
        $this._error($err);
    }
  }

  public function isExist($record){
    $keys = explode(",", $this->_pk);
    $exist = false;

    foreach ($keys as $k) {
      
      if($this->_fields[$k] == $record[$k]){
        $exist = true;
      }else{
        $exist = false;
      }
    }

    return $exist;
  }

  protected function mapping($args){
    $ret = array();
    
    foreach ($this->_fields as $key => $val) {

      if(isset($args[$key])){
         $this->_fields[$key] = $args[$key];
         $ret[$key] = $args[$key];
      }

    }

    return $ret;
  }

  public function encrypt($field, $fieldValue){
    $this->_fields[$field] = password_hash($fieldValue,
    PASSWORD_DEFAULT);

    return $this->_fields[$field];
  }

  public function verify($field, $value){
    $ret = password_verify( $value, $this->_fields[$field]);

    return $ret;
  }

  protected function _error($err){
    die("Please check this message(s): ".$err->getMessage(). " (".$err->getCode().")");
  }

  public function value($field){
    return isset($this->_fields[$field]) ? $this->_fields[$field] : '';
  }

  public function unset($fields){
    foreach ( $fields as $field) {
      if(isset($this->_fields[$field])){
        unset($this->_fields[$field]);
      }
    }
  }

  public function setValue($field, $fieldValue){
    $this->_fields[$field] = $fieldValue;
  }

  public function updateField($field, $fieldValue){
    $this->_fields[$field] = $fieldValue;
  }

  public function save(){
    $keys = explode(",", $this->_pk);

    $whereKey = array();
    foreach ( $keys as $k) {
      if(!empty($this->_fields[$k])){
        $whereKey[$k] = $this->_fields[$k];
      }
    }
    try{
      $this->updateBy(
        $whereKey
        ,$this->_fields
      );
    } catch (Exception $err) {
        $this._error($err);
    }
  } 
  
  public function timeNow(){
    $dt = new DateTime("now", new DateTimeZone($this->_timeZone));

    return $dt->format('YmdGis');
  }

  public function today(){
    return substr($this->timeNow(),0,8);
  }



      

}
