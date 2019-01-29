<?php declare(strict_types=1);

//header('Content-Type: application/json');
//header("Content-type: image/jpg");
require_once(__DIR__.'/vendor/autoload.php');
require_once(__DIR__.'/app/autoload.php');
require_once(__DIR__.'/app/config/configurations.php');
// Tamanho do display do DEBUG
//ini_set('xdebug.var_display_max_depth', '10000');
//ini_set('xdebug.var_display_max_children', '1000000');
//ini_set('xdebug.var_display_max_data', '2000000');

use laudirbispo\Uploader\Handler\BlobUpload;

$up = new BlobUpload();
$up->savePath($_SERVER['DOCUMENT_ROOT'].'/assets/docs/teste');
$up->saveAs('png');
$up->move($_POST['image']);
$up->rename('laudirbispo');
echo $up->getNewFile();
var_dump($up->getErrors());

//var_dump($_POST['image']);