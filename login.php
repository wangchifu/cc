<?php
include 'config.php';
session_start();
if($_POST['username']==$admin and $_POST['password']==$pass){    
    $_SESSION['admin'] = "YES";
    $_SESSION['error'] = "";
    header("Location: index.php");
}else{
    $_SESSION['error'] = "登入失敗";
    header("Location: index.php#login");
}
