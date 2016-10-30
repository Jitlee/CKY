<?php
/**
 * Add version to the file for cache problem
 * @param string $url to add version
 * @return string
 */
function autoVer($filename){
	$ext = substr(strrchr($filename, '.'), 1);
	$path = $_SERVER['DOCUMENT_ROOT'].'/Apps/M/View/default/'.$ext.'/'.$filename;
    $ver = filemtime($path);
//	$ver='0415';
    echo $filename.'?v='.$ver;
}

?>