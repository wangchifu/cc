<?php
include 'config.php';
session_start();
$action = $_REQUEST['action'];
$path = $_REQUEST['path'];


if($action == "make_dir"){
    $new_dir = str_replace('@','/',$_POST['path'])."/".$_POST['folder'];
    mkdir($new_dir);
    $new_path = str_replace('uploads@','',$path);
    if($new_path=="uploads"){
        header("Location: index.php");
    }else{
        header("Location: index.php?folder_path=".$new_path);
    }
}

if($action == "delete"){
    $delete_dir = str_replace('@','/',$path);
    
    //echo $delete_dir;
    //die();
    if(is_dir($delete_dir)){
        deleteDirectory($delete_dir);
    }
    

    header("Location: index.php");
}

if($action == "download"){
    $path_array = explode('@',$path);
    $file_name = $path_array[count($path_array) - 1].".zip";

    $download_dir = str_replace('@','/',$path);
    if(is_dir($download_dir)){
        createZipAndDownload($download_dir,$file_name);
    }
    $new_path = str_replace('uploads@','',$path);
    header("Location: index.php?folder_path=".$new_path);
}

