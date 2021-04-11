<?php
namespace dbhelper;

//table class must extends from _tbl
class Tbl extends _tbl{    
    protected $_tbl = 'tbl';
    protected $_pk = 'id'; //must have primary key

    //define fields
    protected $_fields = [
      'id' => null
      ,'namatest'  => null
      ,"info" => null
    ];    
  
}
?>
