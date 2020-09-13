<?php
require ("DBHelper.php");

$db = DBHelper::Instance("Config/DB_mysql_test.json"); //set Instan koneksi database dengan json di folder Config

#region Select Query 
//Select mengikuti syntax sql, menggunakan metode rantai
$data = $db->select("fullname,email")               //kolom 
             ->from("user")                         //tabel
             //->where(["fullname =", "'hrth'"])    //Kondisi dalam bentuk array yang nanti akan di join semua elemennya jika value string pada sql adalah kutip satu 'value'
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
$db->Insert(
    "user"
    ,(object) array(
        "fullname" => "fullname123"
        ,"email" => "tro765y4reg"
    )
);
#endregion

#region Update Database
//$db->Update(table,where object,update object);
$db->Update(
    "user"
    ,(object) array(
        "email" => "tro765y4reg"
    )
    ,(object) array(
        "fullname" => "tro765y4reg_update"
    )
);
#endregion

#region Delete Database
//$db->Delete(table,where object);
$db->Delete(
    "user"
    ,(object) array(
        "email" => "tro765y4reg"
    )
);
#endregion


?>