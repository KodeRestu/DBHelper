<?php

function _dbloader($class) {
  $pathFolder = "{$_SERVER['DOCUMENT_ROOT']}/dbhelper/dbclass/";
  $parts = explode('\\', $class);
  $cls = end($parts);
  $phpFile = "{$pathFolder}{$cls}.php";
  if (file_exists($phpFile)) {
    require_once $phpFile;
  } else {
    die ("File $phpFile tidak tersedia");
  }
}
 
spl_autoload_register('_dbloader');
?>
