# Salam Hormat
Halo, mohon untuk di coba dbhelper php yang saya buat, semoga bisa membantu dalam kode kamu. 

~Terima Kasih

## Cara Pakai
Lihat Index.php untuk implementasi

### 1. Config
##### 1.1 copy or clone dbhelper ke app kamu. 
##### 1.2 config koneksi database di folder /config file db.json. 

### 2. Buat Table Class
buat table class sesuai database kamu didalam folder dbclass
```bash
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
```

### 3. required / include _dbloader.php
require / include _dbloader.php di atas dokumen php kamu agar bantuan database / tabel bisa di panggil
```bash
require ("dbhelper/_dbloader.php");
```

### 4. Intansiasi Tabel
Instansiasi tabel yang diperlukan
```bash
$tbl = new Tbl();
```

### 5. Good luck & Have fun with your app 

##### 5.1 Create 
###### 5.1.1 Insert 1
$tbl->insert(associativeArray)
```bash
$argsInsert = [
     "namatest" => "nilai" 
    ,"info" => "nilai record yang di insert" 
];

$tbl -> insert($argsInsert);
```
###### 5.1.2 Insert 2
one by one set value field then call $tbl -> new();
```bash
$tbl -> setValue("namatest", "nilai" ); //field name, value record
$tbl -> setValue("info", "nilai record yang di insert"); 
$tbl -> new();
```

##### 5.2 Update 
###### 5.2.1 Update 1
by primary keys value
```bash
$argsUpdate = [
  "namatest" => "nilai Update" 
  ,"info" => "nilai record yang di update edit" 
];

$tbl -> update('1',$argsUpdate);
```

###### 5.2.2 Update 2
by filter
```bash
$argsWhereFilter = [
  "namatest" => "nilai Update" 
];

$argsUpdate = [
  "namatest" => "nilai Update" 
  ,"info" => "nilai record yang di update edit" 
];

$tbl -> updateBy($argsWhereFilter,$argsUpdate);
```

##### 5.3 Delete 
###### 5.3.1 Delete 1
by primary keys value
```bash
$tbl -> delete('1');
```

###### 5.3.2 Delete 2
by filter
```bash
$argsDeleteFilter = [
  "namatest" => "nilaid" 
];

$tbl -> deleteBy($argsDeleteFilter);
```
##### 5.4 Get
Get only get one data and add to fields table
###### 5.4.1 Get 1
get by keys arrays by primary key
```bash
$record = $tbl -> get(['5']);
```
###### 5.4.2 Get 2
if you have other unique value can use getBy
```bash
$tbl -> getBy('field','value');
$record = $tbl -> getBy('id','4');
```
##### 5.5 Query
even you can query many data
```bash
$records = $tbl -> query("id,namatest,info",[
                                          "namatest='nilai'"
                                          //," and "                                          
                                          //,"id = '5'"
                                        ]);
```

### 6. Other Feature
##### 6.1 Get Next Number
```bash
$number = $tbl -> nextno("id");
echo $number;

$number = $tbl -> nextno("id", ["namatest='nilaidf'"]);
echo $number;
```
##### 6.2 encrypt & verify value field
```bash
$tbl -> encrypt("namatest", "realvalue");
echo $tbl -> value("namatest");

//verify value field will return 1 if verified
$isVerify = $tbl -> verify("namatest", "realvalue");
echo $isVerify;
```
##### 6.3 time
```bash
$time = $tbl -> timeNow();
echo $time;
```
##### 6.4 setvalue & unset field
```bash
$tbl->setValue("infodsf",7);
$tbl->unset(["infodsf"]);
echo $tbl->value("infodsf");
```