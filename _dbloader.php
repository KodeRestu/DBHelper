<?php
function _dbloader($class) {
  $path = "./dbclass/{$class}.php";
  if (file_exists($path)) {
    require $path;
  } else {
    die ("File $path tidak tersedia");
  }
}
 
spl_autoload_register('_dbloader');
?>
