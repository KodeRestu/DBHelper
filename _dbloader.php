<?php

function _dbloader($class) {
  $pathFolder = "{$_SERVER['DOCUMENT_ROOT']}/dbhelper/dbclass/";
  $phpFile = "{$pathFolder}{$class}.php";
  if (file_exists($phpFile)) {
    require $phpFile;
  } else {
    die ("File $phpFile tidak tersedia");
  }
}
 
spl_autoload_register('_dbloader');
?>
