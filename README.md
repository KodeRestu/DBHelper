# Salam Hormat
Halo, mohon untuk di coba dbhelper php yang saya buat, semoga bisa membantu dalam kode kamu. 

~Terima Kasih

## Cara Pakai
Lihat Index.php untuk implemetasi

### 1. Require
- Composer

Install
```bash
composer require restu/dbhelper --dev
```
    
file.php
```bash
require_once __DIR__ . '/vendor/autoload.php';
use mod\backend\dbhelper;
```

- Native
```bash
require_once 'mod/backend/dbhelper.php';
use mod\backend\dbhelper;
```

### 2. Buat Intansiasi DB
set Instan koneksi database dengan json di folder Config
```bash
$db = DBHelper::Instance("Config/DB_mysql_test.json");

//Config/DB_mysql_test.json
{
    "type":"mysql"
    ,"host":"localhost"
    ,"dbname":"nama database"
    ,"username":"nama user"
    ,"passcode":"kata sandi"
}
```


### 3. Select Query
Select mengikuti syntax sql, menggunakan metode rantai
- akhiri dengan metode query(); untuk banyak baris
- akhiri dengan metode queryRow(); untuk satu baris
```bash
$data = $db->select("fullname,email")               //kolom 
             ->from("user")                         //tabel
             //->where(["fullname =", "'hrth'"])    //Kondisi dalam bentuk array yang nanti akan di gabung semua elemennya, value string pada sql adalah kutip satu 'value'
             ->orderBy("fullname desc")             //sama seperti sql, 'kolom' dulu kemudian sequen 'asc/desc'
             ->query();                             //akhiri dengan metode query(); untuk banyak baris/ atau queryRow(); untuk satu baris
             
             

$data2 = $db->from("user")                          // tanpa select akan otomatis select * from(tabel)
//->query();                         
->queryRow();                                       //akhiri dengan metode queryRow(); untuk satu baris

echo json_encode($data);
echo "<br><br>";
echo json_encode($data2);
```

### 4. Insert Database
$db->insertInto(table,insert object column=>value);
```bash
$db->insertInto(
    "user"
    ,(object) array(
        "fullname" => "fullname123"
        ,"email" => "tro765y4reg"
    )
);
```

### 5. Update Database

##### 5.1 update(table)->set(object)->where(condition)
- $db->update(table)->set(set object)
- ->where(["email =", "'tro765y4reg'"])             //Kondisi dalam bentuk array yang nanti akan di gabung semua elemennya, value string pada sql adalah kutip satu 'value'
- ->exec()                                          //akhiri dengan metode exec(); untuk menjalankan update
```bash
$db->update("user")
->set(
    (object) array(
        "fullname" => "tro765y4reg_update1"
    )
)
->where(
    ["email =", "'tro765y4reg'"]
)
->exec()
```
##### 5.2 updateTo(table,where object,update object)
$db->updateTo(table,where object,update object);
```bash
$db->updateTo(
    "user"
    ,(object) array(
        "email" => "tro765y4reg"
    )
    ,(object) array(
        "fullname" => "tro765y4reg_update"
    )
);
```

### 6. Delete Database
$db->deleteFrom(table,where object);
```bash
$db->deleteFrom(
    "user"
    ,(object) array(
        "email" => "tro765y4reg"
    )
);
```

### License Creative Commons Zero v1.0 Universal
Kamu dapat menyalin, memodifikasi, mendistribusikan, dan melakukan pekerjaan, bahkan untuk tujuan komersial, dengan Kode ini tanpa meminta izin sekalipun.



