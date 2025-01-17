<?php
include 'config.php';
session_start();
$action = $_REQUEST['action'];
$path = $_REQUEST['path'];

if($action=="delete_file"){
    $path_array = explode('@',$path);
    unset($path_array[count($path_array) - 1]);
    unset($path_array[0]);
    
    $delete_file = str_replace('@','/',$path);
        
    
    if(file_exists($delete_file)){
        unlink($delete_file);
    }
    
    $new_path = "";
    foreach($path_array as $p){
         $new_path = $new_path.'@'.$p;
    }
    $new_path = substr($new_path,1);
    header("Location: index.php?folder_path=".$new_path);
}

if($action=="download_file"){
    $file = str_replace('@','/',$path);
    download_file($file);    
}