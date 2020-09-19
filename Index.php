<?php
require_once __DIR__ . '/vendor/autoload.php';

use mod\backend\dbhelper;

$db = dbhelper::Instance("Config/DB_mysql.json"); //set Instan koneksi database dengan json di folder Config

#region Select Query 
//Select mengikuti syntax sql, menggunakan metode rantai
$data = $db->select("fullname,email")               //kolom 
             ->from("user")                         //tabel
             //->where(["fullname =", "'hrth'"])    //Kondisi dalam bentuk array yang nanti akan di gabung semua elemennya jika value string pada sql adalah kutip satu 'value'
             ->orderBy("fullname desc")             //sama seperti sql, 'kolom' dulu kemudian sequen 'asc/desc'
             ->query();                             //akhiri dengan metode query(); untuk banyak baris/ atau queryRow(); untuk satu baris
             
             

$data2 = $db->from("user")                          // tanpa select akan otomatis select * from(tabel)
//->query();                         
->queryRow();                                       //akhiri dengan metode queryRow(); untuk satu baris

echo json_encode($data);
echo "<br><br>";
echo json_encode($data2);
#endregion

#region Insert Database
//$db->Insert(table,insert object);

// $db->insertInto(
//     "user"
//     ,(object) array(
//         "fullname" => "fullname123"
//         ,"email" => "tro765y4reg"
//     )
// );

#endregion

#region Update Database
//$db->update("table")->set(set object)
//->where(["email =", "'tro765y4reg'"])             //Kondisi dalam bentuk array yang nanti akan di gabung semua elemennya jika value string pada sql adalah kutip satu 'value'
//->exec()                                          //akhiri dengan metode exec(); untuk menjalankan update

// $db->update("user")
// ->set(
//     (object) array(
//         "fullname" => "tro765y4reg_update1"
//     )
// )
// ->where(
//     ["email =", "'tro765y4reg'"]
// )
// ->exec();


//$db->updateTo(table,where object,update object);

// $db->updateTo(
//     "user"
//     ,(object) array(
//         "email" => "tro765y4reg"
//     )
//     ,(object) array(
//         "fullname" => "tro765y4reg_update2"
//     )
// );

#endregion

#region Delete Database
//$db->deleteFrom(table,where object);


// $db->deleteFrom(
//     "user"
//     ,(object) array(
//         "email" => "tro765y4reg"
//     )
// );

#endregion


?>