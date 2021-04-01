<?php
require ("_dbloader.php"); //- load all dbclass/ php files

//- define db table class for Create, Update, Delete, Read in dbclass folder example Tbl.php

//- instance your table
$tbl = new Tbl();

//- Good Luck & Have Fun with your App
// //Create
// //insert 1
// $argsInsert = [
//     "namatest" => "nilai" 
//     ,"info" => "nilai record yang di insert" 
// ];

// $tbl -> insert($argsInsert);

// //insert 2
// $tbl -> setValue("namatest", "nilai" ); //firt field name, value record
// $tbl -> setValue("info", "nilai record yang di insert"); 
// $tbl -> new();


// //Update
// $argsUpdate = [
//   "namatest" => "nilai Update" 
//   ,"info" => "nilai record yang di update edit" 
// ];

////update 1 by primary keys value
//$tbl -> update('1',$argsUpdate);

// //update 2 by filter

// $argsWhereFilter = [
//   "namatest" => "nilai Update" 
// ];
// $tbl -> updateBy($argsWhereFilter,$argsUpdate);

//Delete
//delete 1 use primary keys value
// $tbl -> delete('1');


// $argsDeleteFilter = [
//   "namatest" => "nilaid" 
// ];

// $tbl -> deleteBy($argsDeleteFilter);


//Read

// //get only 1 record
// //get by keys arrays by primary key
$record = $tbl -> get(['5']);
echo json_encode($record);

//if you have other unique value can use getBy
// $record = $tbl -> getBy('id','4');
// echo json_encode($record);

//even you can query many data
// $records = $tbl -> query("id,namatest,info",[
//                                           "namatest='nilai'"
//                                           //," and "                                          
//                                           //,"id = '5'"
//                                         ]);
// echo json_encode($records);

//other feature

//get Next Number
// $number = $tbl -> nextno("id");
// echo $number;

//get Next Number by where condition
// $number = $tbl -> nextno("id", ["namatest='nilaidf'"]);
// echo $number;

// //encrypt & verify value field
// $tbl -> encrypt("namatest", "realvalue");
// echo $tbl -> value("namatest");

// //verify value field will return 1 if verified
// $isVerify = $tbl -> verify("namatest", "realvalue");
// echo $isVerify;

//time now 'Asia/Jakarta'
// $time = $tbl -> timeNow();
// echo $time;

// $tbl->setValue("infodsf",7);
// $tbl->unset(["infodsf"]);
// echo $tbl->value("infodsf");

?>